<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('historia_2022_image')->nullable()->after('historia_image');
            $table->string('historia_2023_image')->nullable()->after('historia_2022_image');
            $table->string('historia_2024_image')->nullable()->after('historia_2023_image');
            $table->string('historia_2025_image')->nullable()->after('historia_2024_image');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['historia_2022_image', 'historia_2023_image', 'historia_2024_image', 'historia_2025_image']);
        });
    }
};
