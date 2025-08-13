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
            $table->string('user_name')->nullable()->after('it_user_id');
            $table->string('user_email')->nullable()->after('user_name');
            $table->string('user_employee_id')->nullable()->after('user_email');
            $table->string('user_department')->nullable()->after('user_employee_id');
            $table->string('user_position')->nullable()->after('user_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn([
                'user_name',
                'user_email', 
                'user_employee_id',
                'user_department',
                'user_position'
            ]);
        });
    }
};
