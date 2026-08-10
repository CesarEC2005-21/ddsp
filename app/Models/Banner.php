<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'section', 'image_path',
        'hero_image_2', 'hero_image_3',
        'gallery_image_1', 'gallery_image_2', 'gallery_image_3',
        'historia_image', 'historia_2022_image', 'historia_2023_image', 'historia_2024_image', 'historia_2025_image',
        'mision_image', 'vision_image',
    ];

    public function imageUrl($field = 'image_path')
    {
        return $this->$field ? asset('storage/' . $this->$field) : null;
    }
}
