<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Renombra la tabla 'noticias' a 'novedades'.
     */
    public function up(): void
    {
        Schema::rename('noticias', 'novedades');
    }

    /**
     * Reverse the migrations.
     * Revierte el renombrado de 'novedades' a 'noticias'.
     */
    public function down(): void
    {
        Schema::rename('novedades', 'noticias');
    }
};
