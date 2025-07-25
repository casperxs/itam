<?php
// database/migrations/2025_01_01_000009_create_user_documents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('it_user_id')->constrained();
            $table->string('document_type');
            $table->string('document_name');
            $table->string('file_path');
            $table->boolean('has_signature')->default(false);
            $table->enum('signature_type', ['physical', 'digital'])->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_documents');
    }
};