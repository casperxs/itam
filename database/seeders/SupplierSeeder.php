<?php // database/seeders/SupplierSeeder.php
namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Dell Technologies',
                'contact_name' => 'Carlos Mendoza',
                'email' => 'ventas@dell.com.mx',
                'phone' => '+52 55 1234 5678',
                'address' => 'Av. Santa Fe 495, Ciudad de México',
                'tax_id' => 'DTE123456789',
                'notes' => 'Proveedor principal de equipos de cómputo',
            ],
            [
                'name' => 'HP Inc.',
                'contact_name' => 'Ana García',
                'email' => 'comercial@hp.com.mx',
                'phone' => '+52 55 9876 5432',
                'address' => 'Blvd. Manuel Ávila Camacho 36, Naucalpan',
                'tax_id' => 'HPI987654321',
                'notes' => 'Proveedor de impresoras y equipos multifuncionales',
            ],
            [
                'name' => 'Lenovo México',
                'contact_name' => 'Roberto Silva',
                'email' => 'ventas@lenovo.com.mx',
                'phone' => '+52 55 5555 0000',
                'address' => 'Torre Esmeralda, López Mateos Sur 2077',
                'tax_id' => 'LEN555000111',
                'notes' => 'Proveedor de laptops y equipos ThinkPad',
            ],
            [
                'name' => 'Microsoft México',
                'contact_name' => 'Patricia López',
                'email' => 'licencias@microsoft.com.mx',
                'phone' => '+52 55 2222 3333',
                'address' => 'Santa Fe 505, Ciudad de México',
                'tax_id' => 'MSF222333444',
                'notes' => 'Proveedor de licencias de software',
            ],
            [
                'name' => 'Samsung Electronics',
                'contact_name' => 'Jorge Ramírez',
                'email' => 'corporativo@samsung.com.mx',
                'phone' => '+52 55 7777 8888',
                'address' => 'Av. Ejército Nacional 843B, Miguel Hidalgo',
                'tax_id' => 'SAM777888999',
                'notes' => 'Proveedor de dispositivos móviles corporativos',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
