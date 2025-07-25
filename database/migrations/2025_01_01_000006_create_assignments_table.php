<?php
// database/migrations/2025_01_01_000006_create_assignments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained();
            $table->foreignId('it_user_id')->constrained();
            $table->foreignId('assigned_by')->constrained('users');
            $table->datetime('assigned_at');
            $table->datetime('returned_at')->nullable();
            $table->text('assignment_notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->string('assignment_document')->nullable();
            $table->boolean('document_signed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignments');
    }
};
