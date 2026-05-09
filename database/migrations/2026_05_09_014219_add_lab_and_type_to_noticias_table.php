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
        Schema::table('noticias', function (Blueprint $table) {
            $table->foreignId('laboratory_id')->nullable()->constrained('laboratories')->onDelete('set null');
            $table->string('tipo')->default('PROMOCION'); // PROMOCION o EVENTO
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropForeign(['laboratory_id']);
            $table->dropColumn(['laboratory_id', 'tipo']);
        });
    }
};
