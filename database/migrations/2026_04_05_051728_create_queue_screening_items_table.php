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
        Schema::create('queue_screening_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained('queues')->cascadeOnDelete();
            $table->foreignId('screening_id')->constrained('screenings')->cascadeOnDelete();
            $table->timestamps(); // acts as selected_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_screening_items');
    }
};
