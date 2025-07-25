<?php // database/seeders/ItUserSeeder.php
namespace Database\Seeders;

use App\Models\ItUser;
use Illuminate\Database\Seeder;

class ItUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Juan Pérez García',
                'email' => 'juan.perez@empresa.com',
                'employee_id' => 'EMP001',
                'department' => 'Contabilidad',
                'position' => 'Contador Senior',
                'status' => 'active',
                'notes' => 'Usuario con acceso a sistemas contables',
            ],
            [
                'name' => 'María González López',
                'email' => 'maria.gonzalez@empresa.com',
                'employee_id' => 'EMP002',
                'department' => 'Recursos Humanos',
                'position' => 'Gerente de RRHH',
                'status' => 'active',
                'notes' => 'Acceso a sistemas de nómina y personal',
            ],
            [
                'name' => 'Carlos Rodríguez Martín',
                'email' => 'carlos.rodriguez@empresa.com',
                'employee_id' => 'EMP003',
                'department' => 'Ventas',
                'position' => 'Ejecutivo de Ventas',
                'status' => 'active',
                'notes' => 'Usuario móvil, requiere laptop y teléfono',
            ],
            [
                'name' => 'Ana Martínez Silva',
                'email' => 'ana.martinez@empresa.com',
                'employee_id' => 'EMP004',
                'department' => 'Marketing',
                'position' => 'Coordinadora de Marketing',
                'status' => 'active',
                'notes' => 'Acceso a herramientas de diseño y marketing digital',
            ],
            [
                'name' => 'Luis Hernández Torres',
                'email' => 'luis.hernandez@empresa.com',
                'employee_id' => 'EMP005',
                'department' => 'Operaciones',
                'position' => 'Supervisor de Operaciones',
                'status' => 'inactive',
                'notes' => 'Usuario temporal, actualmente sin asignaciones',
            ],
        ];

        foreach ($users as $user) {
            ItUser::create($user);
        }
    }
}
