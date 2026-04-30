<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    protected $fillable = ['nombre', 'ubicacion', 'zona_id', 'latitud', 'longitud', 'estado', 'telefono', 'email', 'imagen'];

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function locations()
    {
        return $this->hasMany(RepresentativeLocation::class);
    }
}
