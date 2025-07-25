<?php // database/seeders/EquipmentTypeSeeder.php
namespace Database\Seeders;

use App\Models\EquipmentType;
use Illuminate\Database\Seeder;

class EquipmentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Laptop', 'category' => 'computer', 'description' => 'Computadoras portátiles'],
            ['name' => 'Desktop', 'category' => 'computer', 'description' => 'Computadoras de escritorio'],
            ['name' => 'Monitor', 'category' => 'computer', 'description' => 'Monitores y pantallas'],
            ['name' => 'Servidor', 'category' => 'computer', 'description' => 'Servidores'],
            ['name' => 'Teléfono Móvil', 'category' => 'phone', 'description' => 'Teléfonos celulares corporativos'],
            ['name' => 'Teléfono Fijo', 'category' => 'phone', 'description' => 'Teléfonos fijos de oficina'],
            ['name' => 'Impresora Láser', 'category' => 'printer', 'description' => 'Impresoras láser'],
            ['name' => 'Impresora Multifuncional', 'category' => 'printer', 'description' => 'Equipos multifuncionales'],
            ['name' => 'Microsoft Office', 'category' => 'license', 'description' => 'Licencias de Microsoft Office 365'],
            ['name' => 'Windows', 'category' => 'license', 'description' => 'Licencias de sistema operativo Windows'],
            ['name' => 'Antivirus', 'category' => 'software', 'description' => 'Software antivirus'],
            ['name' => 'Software ERP', 'category' => 'software', 'description' => 'Sistema de Global'],
        ];

        foreach ($types as $type) {
            EquipmentType::create($type);
        }
    }
}