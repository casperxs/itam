<?php
// database/migrations/2025_01_01_000011_create_bulk_imports_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bulk_imports', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->enum('import_type', ['equipment', 'users']);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed']);
            $table->integer('total_records')->default(0);
            $table->integer('processed_records')->default(0);
            $table->integer('failed_records')->default(0);
            $table->json('errors')->nullable();
            $table->foreignId('imported_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bulk_imports');
    }
};