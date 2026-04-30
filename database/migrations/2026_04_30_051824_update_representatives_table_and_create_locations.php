<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('representatives', function (Blueprint $table) {
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('imagen')->nullable();
        });

        Schema::create('representative_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('representative_id')->constrained()->onDelete('cascade');
            $table->foreignId('zona_id')->constrained();
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->string('descripcion_punto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('representative_locations');
        Schema::table('representatives', function (Blueprint $table) {
            $table->dropColumn(['telefono', 'email', 'imagen']);
        });
    }
};
