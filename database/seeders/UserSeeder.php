<?php // database/seeders/UserSeeder.php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'sistemas@bkb.mx',
            'password' => Hash::make('Soporte01$'),
            'role' => 'admin',
            'department' => 'TI',
            'position' => 'Administrador de Sistemas',
            'employee_id' => 'ADM001',
        ]);

        User::create([
            'name' => 'Técnico TI',
            'email' => 'tecnico@bkb.mx',
            'password' => Hash::make('Soporte01$'),
            'role' => 'admin',
            'department' => 'TI',
            'position' => 'Técnico en Sistemas',
            'employee_id' => 'TEC001',
        ]);
    }
}
