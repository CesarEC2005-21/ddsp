<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // Inicio - hero carousel slides
            $table->string('hero_image_2')->nullable()->after('image_path');
            $table->string('hero_image_3')->nullable()->after('hero_image_2');
            // Inicio - gallery images (Infraestructura de Excelencia)
            $table->string('gallery_image_1')->nullable()->after('hero_image_3');
            $table->string('gallery_image_2')->nullable()->after('gallery_image_1');
            $table->string('gallery_image_3')->nullable()->after('gallery_image_2');
            // Nosotros - section images
            $table->string('historia_image')->nullable()->after('gallery_image_3');
            $table->string('mision_image')->nullable()->after('historia_image');
            $table->string('vision_image')->nullable()->after('mision_image');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'hero_image_2',
                'hero_image_3',
                'gallery_image_1',
                'gallery_image_2',
                'gallery_image_3',
                'historia_image',
                'mision_image',
                'vision_image',
            ]);
        });
    }
};
