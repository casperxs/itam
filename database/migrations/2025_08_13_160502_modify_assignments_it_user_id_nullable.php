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
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['it_user_id']);
            $table->foreignId('it_user_id')->nullable()->change();
            $table->foreign('it_user_id')->references('id')->on('it_users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['it_user_id']);
            $table->foreignId('it_user_id')->nullable(false)->change();
            $table->foreign('it_user_id')->references('id')->on('it_users');
        });
    }
};
