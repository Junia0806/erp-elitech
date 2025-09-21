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
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            // Relasi 1-ke-1 dengan production_plans
            $table->foreignId('production_plan_id')->unique()->constrained('production_plans');
            $table->enum('status', ['menunggu', 'sedang dikerjakan', 'selesai'])->default('menunggu');
            $table->integer('quantity_actual')->nullable();
            $table->integer('quantity_reject')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};