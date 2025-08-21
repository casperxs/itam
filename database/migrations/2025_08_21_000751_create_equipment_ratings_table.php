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
        Schema::create('equipment_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('maintenance_record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluated_by')->constrained('users');
            $table->json('criteria_evaluations'); // Stores {criterion_id: {value, score}}
            $table->decimal('total_score', 5, 2);
            $table->string('rating_category'); // Excelente, Optimo, etc.
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_ratings');
    }
};
