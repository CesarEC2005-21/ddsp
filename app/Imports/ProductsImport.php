<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\UnidadMedida;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    protected $laboratory_id;
    public $results = [
        'new_products' => [],
        'updated_products' => [],
        'new_laboratories' => [],
    ];

    public function __construct($laboratory_id = null)
    {
        $this->laboratory_id = $laboratory_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $rowArray = $row->toArray();
            
            // Map keys
            $codigoKey = $this->findKey($rowArray, ['codigo', 'sku', 'code']);
            $descKey = $this->findKey($rowArray, ['descripcion', 'nombre', 'description', 'product']);
            $umKey = $this->findKey($rowArray, ['um', 'unidad', 'medida']);
            $precioKey = $this->findKey($rowArray, ['precio', 'costo', 'price']);
            $labKey = $this->findKey($rowArray, ['laboratorio', 'lab', 'laboratory']);

            $codigo = $codigoKey ? trim($rowArray[$codigoKey]) : null;
            if (empty($codigo)) continue;

            // Determine Laboratory
            $currentLabId = $this->laboratory_id;
            if (!$currentLabId && $labKey && !empty($rowArray[$labKey])) {
                $labName = trim($rowArray[$labKey]);
                $lab = \App\Models\Laboratory::where('descripcion', $labName)->first();
                if (!$lab) {
                    $lab = \App\Models\Laboratory::create(['descripcion' => $labName]);
                    $this->results['new_laboratories'][] = $labName;
                }
                $currentLabId = $lab->id;
            }

            if (!$currentLabId) continue;

            // Determine Unit of Measure
            $umName = $umKey && !empty($rowArray[$umKey]) ? trim($rowArray[$umKey]) : null;
            $unidadMedidaId = null;
            if ($umName) {
                $um = UnidadMedida::where('um', $umName)->first();
                if (!$um) {
                    $um = UnidadMedida::create(['um' => $umName, 'estado' => true]);
                }
                $unidadMedidaId = $um->id;
            } else {
                $firstUm = UnidadMedida::first();
                $unidadMedidaId = $firstUm ? $firstUm->id : null;
            }

            $rawPrecio = ($precioKey && isset($rowArray[$precioKey])) ? $rowArray[$precioKey] : 0;
            if (is_string($rawPrecio)) {
                $rawPrecio = str_replace(['S/', 's/', '$', ' '], '', $rawPrecio);
                $rawPrecio = str_replace(',', '.', $rawPrecio);
            }
            $precio = floatval($rawPrecio);
            $nombre = ($descKey && isset($rowArray[$descKey])) ? $rowArray[$descKey] : 'Sin Nombre';

            $product = Product::where('codigo', $codigo)->first();

            if ($product) {
                // Repeated Product: Update Price and save History if changed (rounded to 2 decimal places to avoid float precision issues)
                $oldPrice = round(floatval($product->precio), 2);
                $newPrice = round(floatval($precio), 2);
                if ($oldPrice != $newPrice) {
                    // Save history
                    \App\Models\ProductPriceHistory::create([
                        'product_id' => $product->id,
                        'precio' => $oldPrice,
                        'precio_nuevo' => $newPrice,
                        'user_id' => auth()->id() ?? 1
                    ]);
                    
                    $product->precio = $precio;
                    $product->usuario_actualizo = auth()->id() ?? 1;
                    $product->save();
                    $this->results['updated_products'][] = "{$product->nombre} ({$product->codigo})";
                }
            } else {
                // New Product
                $product = Product::create([
                    'codigo' => $codigo,
                    'nombre' => $nombre,
                    'laboratory_id' => $currentLabId,
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

    private function findKey(array $row, array $possibleKeys)
    {
        $keys = array_keys($row);
        foreach ($possibleKeys as $pk) {
            foreach ($keys as $k) {
                $cleanK = strtolower(trim($k));
                if (str_contains($cleanK, $pk)) {
                    return $k;
                }
            }
        }
        return null;
    }
}
