<?php 
// database/migrations/2025_01_01_000003_create_equipment_types_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // computer, phone, printer, license, software
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment_types');
    }
};
