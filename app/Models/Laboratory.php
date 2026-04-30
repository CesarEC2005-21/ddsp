<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    protected $fillable = ['codigo', 'descripcion', 'is_top', 'estado', 'imagen', 'logo'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
