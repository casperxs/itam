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
        Schema::table('user_documents', function (Blueprint $table) {
            // Eliminar columnas viejas
            $table->dropColumn(['document_type', 'document_name', 'has_signature', 'signature_type']);
            
            // Añadir nuevas columnas
            $table->string('original_name')->after('it_user_id');
            $table->string('filename')->after('original_name');
            $table->integer('file_size')->nullable()->after('file_path');
            $table->string('mime_type')->nullable()->after('file_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_documents', function (Blueprint $table) {
            // Restaurar columnas viejas
            $table->string('document_type')->after('it_user_id');
            $table->string('document_name')->after('document_type');
            $table->boolean('has_signature')->default(false)->after('file_path');
            $table->enum('signature_type', ['physical', 'digital'])->nullable()->after('has_signature');
            
            // Eliminar nuevas columnas
            $table->dropColumn(['original_name', 'filename', 'file_size', 'mime_type']);
        });
    }
};
