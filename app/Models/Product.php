<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nombre',
        'laboratory_id',
        'precio',
        'unidad_medida_id',
        'codigo',
        'estado',
        'imagen',
        'descripcion',
        'usuario_origen',
        'usuario_actualizo'
    ];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }
}
