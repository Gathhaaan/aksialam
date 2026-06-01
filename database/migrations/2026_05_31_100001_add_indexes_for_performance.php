<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan index pada kolom-kolom yang sering digunakan untuk
 * filtering dan sorting, guna meningkatkan performa query.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->index('status', 'reports_status_index');
            $table->index('category', 'reports_category_index');
            $table->index('user_id', 'reports_user_id_index');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->index('status', 'campaigns_status_index');
            $table->index('organizer_id', 'campaigns_organizer_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('reports_status_index');
            $table->dropIndex('reports_category_index');
            $table->dropIndex('reports_user_id_index');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropIndex('campaigns_status_index');
            $table->dropIndex('campaigns_organizer_id_index');
        });
    }
};
