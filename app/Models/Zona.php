<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $fillable = ['nombre_zona', 'estado'];

    public function representatives()
    {
        return $this->hasMany(Representative::class);
    }
}
