<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'nombre', 'apellidos', 'telefono', 'tipo_documento', 
        'numero_documento', 'ciudad', 'email', 'observaciones', 
        'total', 'estado'
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
