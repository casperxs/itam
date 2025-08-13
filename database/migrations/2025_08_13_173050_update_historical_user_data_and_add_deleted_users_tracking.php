<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Agregar campos de tracking para usuarios eliminados
        Schema::table('it_users', function (Blueprint $table) {
            $table->datetime('deleted_at')->nullable()->after('notes');
            $table->string('deleted_reason')->nullable()->after('deleted_at');
        });

        // 2. Actualizar assignments existentes con información de usuarios actuales
        // Esto preservará la información histórica para usuarios que aún existen
        $this->updateHistoricalAssignments();

        // 3. Crear tabla para histórico de usuarios eliminados
        Schema::create('deleted_users_history', function (Blueprint $table) {
            $table->id();
            $table->integer('original_user_id');
            $table->string('name');
            $table->string('email');
            $table->string('employee_id')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->datetime('deleted_at');
            $table->string('deleted_reason')->nullable();
            $table->timestamps();
            
            $table->index('original_user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('deleted_users_history');
        
        Schema::table('it_users', function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'deleted_reason']);
        });
    }

    private function updateHistoricalAssignments()
    {
        // Actualizar assignments que tienen it_user_id pero no tienen user_name guardado
        $assignmentsToUpdate = DB::table('assignments')
            ->join('it_users', 'assignments.it_user_id', '=', 'it_users.id')
            ->whereNotNull('assignments.it_user_id')
            ->whereNull('assignments.user_name')
            ->select(
                'assignments.id',
                'it_users.name',
                'it_users.email',
                'it_users.employee_id',
                'it_users.department',
                'it_users.position'
            )
            ->get();

        foreach ($assignmentsToUpdate as $assignment) {
            DB::table('assignments')
                ->where('id', $assignment->id)
                ->update([
                    'user_name' => $assignment->name,
                    'user_email' => $assignment->email,
                    'user_employee_id' => $assignment->employee_id,
                    'user_department' => $assignment->department,
                    'user_position' => $assignment->position,
                    'updated_at' => now()
                ]);
        }
    }
};