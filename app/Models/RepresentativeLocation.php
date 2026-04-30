<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepresentativeLocation extends Model
{
    protected $fillable = ['representative_id', 'zona_id', 'latitud', 'longitud', 'descripcion_punto'];

    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }
}
