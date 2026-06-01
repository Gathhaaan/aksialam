<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan status 'rejected' ke enum kolom status di tabel reports.
     */
    public function up(): void
    {
        // MySQL memerlukan ALTER COLUMN untuk mengubah enum
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending', 'verified', 'resolved', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('pending', 'verified', 'resolved') DEFAULT 'pending'");
    }
};
