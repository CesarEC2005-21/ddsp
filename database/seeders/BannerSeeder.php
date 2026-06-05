<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            'inicio',
            'nosotros',
            'noticias',
            'ejecutivos',
            'productos',
            'contacto'
        ];

        foreach ($sections as $section) {
            \App\Models\Banner::firstOrCreate(
                ['section' => $section],
                ['image_path' => null]
            );
        }
    }
}
