<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan unique constraint pada tabel pivot campaign_user
 * agar satu user tidak bisa mendaftar dua kali di campaign yang sama
 * di level database (sebelumnya hanya dicek di level aplikasi).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_user', function (Blueprint $table) {
            $table->unique(['campaign_id', 'user_id'], 'campaign_user_unique');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_user', function (Blueprint $table) {
            $table->dropUnique('campaign_user_unique');
        });
    }
};
