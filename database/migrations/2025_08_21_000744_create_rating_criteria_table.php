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
        Schema::create('rating_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->integer('weight_percentage');
            $table->json('options'); // Stores the dropdown options with values
            $table->boolean('auto_calculated')->default(false); // For age criterion
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_criteria');
    }
};
