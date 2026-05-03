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

    public function __construct($laboratory_id)
    {
        $this->laboratory_id = $laboratory_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $rowArray = $row->toArray();
            
            // Debugging to see what keys are arriving
            \Illuminate\Support\Facades\Log::info('Excel Row Data: ' . json_encode($rowArray));

            // Attempt to find keys tolerating spaces or different names
            $codigoKey = $this->findKey($rowArray, ['codigo', 'c_digo', 'código', 'code']);
            $descKey = $this->findKey($rowArray, ['descripcion', 'descripci_n', 'descripción', 'nombre']);
            $umKey = $this->findKey($rowArray, ['um', 'unidad', 'medida']);
            $precioKey = $this->findKey($rowArray, ['precio', 'costo', 'price']);

            $codigo = $codigoKey ? $rowArray[$codigoKey] : null;
            if (empty($codigo)) {
                // Check if perhaps it's an indexed array
                if (isset($rowArray[0]) && isset($rowArray[1])) {
                    $codigo = $rowArray[0];
                    $descKey = 1;
                    $umKey = 2;
                    $precioKey = 3;
                    if (strtolower(trim($codigo)) === 'codigo' || strtolower(trim($codigo)) === 'código') {
                        continue; // Skip header
                    }
                } else {
                    continue;
                }
            }

            $umName = $umKey && isset($rowArray[$umKey]) ? $rowArray[$umKey] : null;
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

            Product::updateOrCreate(
                ['codigo' => $codigo],
                [
                    'nombre' => ($descKey && isset($rowArray[$descKey])) ? $rowArray[$descKey] : 'Sin Nombre',
                    'laboratory_id' => $this->laboratory_id,
                    'unidad_medida_id' => $unidadMedidaId,
                    'precio' => ($precioKey && isset($rowArray[$precioKey])) ? floatval($rowArray[$precioKey]) : 0,
                    'estado' => true,
                    'usuario_origen' => auth()->id() ?? 1,
                    'usuario_actualizo' => auth()->id() ?? 1,
                ]
            );
        }
    }

    private function findKey(array $row, array $possibleKeys)
    {
        $keys = array_keys($row);
        foreach ($possibleKeys as $pk) {
            foreach ($keys as $k) {
                if (str_contains(strtolower($k), $pk)) {
                    return $k;
                }
            }
        }
        return null;
    }
}
