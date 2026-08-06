<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Laboratory;
use App\Models\UnidadMedida;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class GeneralProductsImport implements ToCollection
{
    private $currentLaboratoryId = null;
    private $excludedKeywords = ['COTIZACION', 'PANETON', 'LOCALES', 'REBATE', 'PROMOCION', 'FECHA CORTA', 'DONOFRIO', 'TOMATODO'];

    public $results = [
        'new_products' => [],
        'updated_products' => [],
        'new_laboratories' => [],
    ];

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        set_time_limit(0); // Prevents 60-second timeout on large imports

        foreach ($rows as $row) {
            $col0 = isset($row[0]) ? trim((string)$row[0]) : '';
            
            if (empty($col0)) {
                continue;
            }

            // Detect Laboratory (Clase)
            if (str_starts_with(strtoupper($col0), 'CLASE:')) {
                $labName = trim(str_ireplace('CLASE:', '', $col0));
                
                // Check exclusions for Laboratory
                $excludeLab = false;
                $upperLabName = strtoupper($labName);
                foreach ($this->excludedKeywords as $keyword) {
                    if (str_contains($upperLabName, $keyword)) {
                        $excludeLab = true;
                        break;
                    }
                }

                if ($excludeLab) {
                    $this->currentLaboratoryId = null;
                    continue;
                }

                $laboratory = Laboratory::where('descripcion', $labName)->first();
                if (!$laboratory) {
                    $lastId = Laboratory::max('id') ?? 0;
                    $autoCode = 'LAB-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
                    while (Laboratory::where('codigo', $autoCode)->exists()) {
                        $lastId++;
                        $autoCode = 'LAB-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
                    }
                    $laboratory = Laboratory::create([
                        'codigo' => $autoCode,
                        'descripcion' => $labName,
                        'estado' => true
                    ]);
                    $this->results['new_laboratories'][] = $labName;
                }
                $this->currentLaboratoryId = $laboratory->id;
                continue;
            }

            // If it's not a class, and it's numeric, it is likely a product code
            if (is_numeric($col0)) {
                // If there's no active laboratory (e.g. because it was filtered out), skip the products
                if (!$this->currentLaboratoryId) {
                    continue;
                }

                $productCode = $col0;
                $productName = isset($row[3]) ? trim((string)$row[3]) : '';
                $umName = isset($row[39]) ? trim((string)$row[39]) : '';
                
                $precioContado = isset($row[46]) ? floatval($row[46]) : 0;
                $precioCredito = isset($row[48]) ? floatval($row[48]) : 0;
                
                $isPanalOrLeche = preg_match('/pañal|panal|leche/i', $productName);
                $finalPrice = ($precioContado <= 0 || $isPanalOrLeche) ? $precioCredito : $precioContado;

                // Check exclusions
                $exclude = false;
                $upperName = strtoupper($productName);
                foreach ($this->excludedKeywords as $keyword) {
                    if (str_contains($upperName, $keyword)) {
                        $exclude = true;
                        break;
                    }
                }

                if ($exclude || empty($productName)) {
                    continue;
                }

                // Handle Unidad de Medida
                $unidadMedidaId = null;
                if (!empty($umName)) {
                    $um = UnidadMedida::where('um', $umName)->first();
                    if (!$um) {
                        $um = UnidadMedida::create(['um' => $umName, 'estado' => true]);
                    }
                    $unidadMedidaId = $um->id;
                }

                // Handle Price
                $precio = $precioContado > 0 ? $precioContado : $precioCredito;

                // Create or Update Product manually to handle History and Audit properly
                $product = Product::where('codigo', $productCode)->first();

                if ($product) {
                    $oldPrice = round(floatval($product->precio), 2);
                    $newPrice = round(floatval($precio), 2);
                    
                    $updated = false;
                    
                    if ($oldPrice != $newPrice) {
                        \App\Models\ProductPriceHistory::create([
                            'product_id' => $product->id,
                            'precio' => $oldPrice,
                            'precio_nuevo' => $newPrice,
                            'user_id' => auth()->id() ?? 1
                        ]);
                        $product->precio = $precio;
                        $updated = true;
                    }
                    
                    if ($product->nombre != $productName || $product->laboratory_id != $this->currentLaboratoryId || $product->unidad_medida_id != $unidadMedidaId) {
                        $product->nombre = $productName;
                        $product->laboratory_id = $this->currentLaboratoryId;
                        $product->unidad_medida_id = $unidadMedidaId;
                        $updated = true;
                    }
                    
                    if ($updated) {
                        $product->usuario_actualizo = auth()->id() ?? 1;
                        $product->save();
                        $this->results['updated_products'][] = "{$product->nombre} ({$product->codigo})";
                    }
                } else {
                    $product = Product::create([
                        'codigo' => $productCode,
                        'nombre' => $productName,
                        'laboratory_id' => $this->currentLaboratoryId,
                        'unidad_medida_id' => $unidadMedidaId,
                        'precio' => $precio,
                        'estado' => true,
                        'usuario_origen' => auth()->id() ?? 1,
                        'usuario_actualizo' => auth()->id() ?? 1,
                    ]);
                    $this->results['new_products'][] = "{$product->nombre} ({$product->codigo})";
                }
            }
        }
    }
}
