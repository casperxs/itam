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
        // Para MySQL necesitamos hacer esto paso a paso
        Schema::table('assignments', function (Blueprint $table) {
            // Primero eliminar la restricción de clave foránea
            $table->dropForeign(['it_user_id']);
        });

        Schema::table('assignments', function (Blueprint $table) {
            // Luego modificar la columna para permitir NULL
            $table->unsignedBigInteger('it_user_id')->nullable()->change();
        });

        Schema::table('assignments', function (Blueprint $table) {
            // Finalmente crear la nueva restricción con onDelete('set null')
            $table->foreign('it_user_id')
                  ->references('id')
                  ->on('it_users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['it_user_id']);
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('it_user_id')->nullable(false)->change();
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->foreign('it_user_id')
                  ->references('id')
                  ->on('it_users');
        });
    }
};
