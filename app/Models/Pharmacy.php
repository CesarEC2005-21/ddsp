<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = ['nombre', 'ubicacion', 'latitud', 'longitud', 'estado'];
}
