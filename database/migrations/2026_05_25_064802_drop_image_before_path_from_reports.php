<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menghapus kolom image_before_path yang sudah tidak digunakan.
     * Kolom ini digantikan oleh image_url.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('image_before_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('image_before_path')->nullable()->after('location_name');
        });
    }
};
