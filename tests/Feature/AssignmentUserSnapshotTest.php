<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\ItUser;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignmentUserSnapshotTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function assignment_shows_stored_user_data_when_user_is_deleted()
    {
        $itUser = ItUser::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'employee_id' => 'EMP001',
            'department' => 'IT',
            'position' => 'Developer'
        ]);
        
        $equipment = Equipment::factory()->create();

        $assignment = Assignment::factory()->create([
            'equipment_id' => $equipment->id,
            'it_user_id' => $itUser->id,
            'user_name' => 'Juan Pérez',
            'user_email' => 'juan@example.com',
            'user_employee_id' => 'EMP001',
            'user_department' => 'IT',
            'user_position' => 'Developer',
        ]);

        // Verificar que inicialmente funciona con el usuario
        $this->assertEquals('Juan Pérez', $assignment->getUserName());
        $this->assertEquals('juan@example.com', $assignment->getUserEmail());
        $this->assertEquals('EMP001', $assignment->getUserEmployeeId());
        $this->assertEquals('IT', $assignment->getUserDepartment());
        $this->assertEquals('Developer', $assignment->getUserPosition());

        // Simular eliminación del usuario
        $itUser->delete();
        $assignment->fresh();

        // Verificar que aún muestra los datos almacenados
        $this->assertEquals('Juan Pérez', $assignment->getUserName());
        $this->assertEquals('juan@example.com', $assignment->getUserEmail());
        $this->assertEquals('EMP001', $assignment->getUserEmployeeId());
        $this->assertEquals('IT', $assignment->getUserDepartment());
        $this->assertEquals('Developer', $assignment->getUserPosition());
    }

    /** @test */
    public function assignment_shows_fallback_when_user_deleted_and_no_snapshot()
    {
        $itUser = ItUser::factory()->create([
            'name' => 'María González',
            'email' => 'maria@example.com',
            'employee_id' => 'EMP002',
            'department' => 'Marketing',
            'position' => 'Manager'
        ]);
        
        $equipment = Equipment::factory()->create();

        $assignment = Assignment::factory()->create([
            'equipment_id' => $equipment->id,
            'it_user_id' => $itUser->id,
            'user_name' => null, // Sin snapshot
            'user_email' => null,
            'user_employee_id' => null,
            'user_department' => null,
            'user_position' => null,
        ]);

        // Verificar que inicialmente funciona con el usuario
        $this->assertEquals('María González', $assignment->getUserName());
        $this->assertEquals('maria@example.com', $assignment->getUserEmail());

        // Simular eliminación del usuario
        $itUser->delete();
        $assignment->fresh();

        // Verificar que muestra mensaje de fallback
        $this->assertEquals('Usuario eliminado', $assignment->getUserName());
        $this->assertNull($assignment->getUserEmail());
        $this->assertNull($assignment->getUserEmployeeId());
        $this->assertNull($assignment->getUserDepartment());
        $this->assertNull($assignment->getUserPosition());
    }

    /** @test */
    public function assignment_shows_fallback_when_no_user_exists()
    {
        $equipment = Equipment::factory()->create();
        
        $assignment = Assignment::factory()->create([
            'equipment_id' => $equipment->id,
            'it_user_id' => 999, // Usuario inexistente
            'user_name' => null,
            'user_email' => null,
            'user_employee_id' => null,
            'user_department' => null,
            'user_position' => null,
        ]);

        $this->assertEquals('Usuario eliminado', $assignment->getUserName());
        $this->assertNull($assignment->getUserEmail());
        $this->assertNull($assignment->getUserEmployeeId());
        $this->assertNull($assignment->getUserDepartment());
        $this->assertNull($assignment->getUserPosition());
    }
}
