<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novedad extends Model
{
    /**
     * Nombre de la tabla en la base de datos.
     * Renombrada de 'noticias' a 'novedades'.
     */
    protected $table = 'novedades';

    protected $fillable = [
        'codigo',
        'descripcion',
        'detalle',
        'fecha_inicial',
        'fecha_final',
        'estado',
        'imagen',
        'user_id',
        'laboratory_id',
        'product_id',
        'tipo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
