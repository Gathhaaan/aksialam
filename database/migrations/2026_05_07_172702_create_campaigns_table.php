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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('report_id')->nullable()->constrained('reports')->onDelete('set null');
            $table->string('title');
            $table->dateTime('event_date');
            $table->integer('max_volunteers');
            $table->integer('target_metric');
            $table->string('metric_unit', 50); // misal: "kg sampah"
            $table->string('image_after_path')->nullable();
            $table->enum('status', ['open', 'closed', 'finished'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
