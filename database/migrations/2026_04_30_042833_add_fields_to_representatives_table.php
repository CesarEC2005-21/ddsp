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
            $table->boolean('estado')->default(true)->after('longitud');
            $table->foreignId('zona_id')->nullable()->constrained('zonas')->onDelete('set null')->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('representatives', function (Blueprint $table) {
            //
        });
    }
};
