<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->string('logo_bg_color', 7)->nullable()->default('#f8fafc')->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->dropColumn('logo_bg_color');
        });
    }
};
