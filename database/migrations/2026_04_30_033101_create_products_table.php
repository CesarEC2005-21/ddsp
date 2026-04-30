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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('laboratory_id')->constrained();
            $table->decimal('precio', 10, 2);
            $table->string('um');
            $table->string('codigo')->unique();
            $table->boolean('estado')->default(true);
            $table->foreignId('usuario_origen')->nullable()->constrained('users');
            $table->foreignId('usuario_actualizo')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
