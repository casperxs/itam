<?php // database/seeders/EquipmentSeeder.php
namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $laptopType = EquipmentType::where('name', 'Laptop')->first();
        $desktopType = EquipmentType::where('name', 'Desktop')->first();
        $phoneType = EquipmentType::where('name', 'Teléfono Móvil')->first();
        $printerType = EquipmentType::where('name', 'Impresora Láser')->first();

        $dell = Supplier::where('name', 'Dell Technologies')->first();
        $hp = Supplier::where('name', 'HP Inc.')->first();
        $lenovo = Supplier::where('name', 'Lenovo México')->first();
        $samsung = Supplier::where('name', 'Samsung Electronics')->first();

        $equipment = [
            [
                'equipment_type_id' => $laptopType->id,
                'supplier_id' => $dell->id,
                'serial_number' => 'DLL-2024-001',
                'asset_tag' => 'AST-001',
                'brand' => 'Dell',
                'model' => 'Latitude 5520',
                'specifications' => 'Intel i7-1165G7, 16GB RAM, 512GB SSD, Windows 11 Pro',
                'status' => 'available',
                'purchase_price' => 25000.00,
                'purchase_date' => Carbon::now()->subMonths(6),
                'warranty_end_date' => Carbon::now()->addMonths(30),
                'invoice_number' => 'FAC-DELL-001',
                'observations' => 'Equipo para ejecutivos',
            ],
            [
                'equipment_type_id' => $laptopType->id,
                'supplier_id' => $lenovo->id,
                'serial_number' => 'LNV-2024-001',
                'asset_tag' => 'AST-002',
                'brand' => 'Lenovo',
                'model' => 'ThinkPad X1 Carbon',
                'specifications' => 'Intel i5-1135G7, 8GB RAM, 256GB SSD, Windows 11 Pro',
                'status' => 'assigned',
                'purchase_price' => 22000.00,
                'purchase_date' => Carbon::now()->subMonths(4),
                'warranty_end_date' => Carbon::now()->addMonths(32),
                'invoice_number' => 'FAC-LEN-001',
                'observations' => 'Equipo ultraliviano',
            ],
            [
                'equipment_type_id' => $desktopType->id,
                'supplier_id' => $hp->id,
                'serial_number' => 'HP-2024-001',
                'asset_tag' => 'AST-003',
                'brand' => 'HP',
                'model' => 'EliteDesk 800 G8',
                'specifications' => 'Intel i5-11500, 16GB RAM, 1TB HDD, Windows 11 Pro',
                'status' => 'available',
                'purchase_price' => 18000.00,
                'purchase_date' => Carbon::now()->subMonths(3),
                'warranty_end_date' => Carbon::now()->addMonths(33),
                'invoice_number' => 'FAC-HP-001',
                'observations' => 'Equipo de escritorio estándar',
            ],
            [
                'equipment_type_id' => $phoneType->id,
                'supplier_id' => $samsung->id,
                'serial_number' => 'SAM-2024-001',
                'asset_tag' => 'TEL-001',
                'brand' => 'Samsung',
                'model' => 'Galaxy S24',
                'specifications' => '256GB, Android 14, Dual SIM',
                'status' => 'available',
                'purchase_price' => 15000.00,
                'purchase_date' => Carbon::now()->subMonths(2),
                'warranty_end_date' => Carbon::now()->addMonths(10),
                'invoice_number' => 'FAC-SAM-001',
                'observations' => 'Teléfono corporativo premium',
            ],
            [
                'equipment_type_id' => $printerType->id,
                'supplier_id' => $hp->id,
                'serial_number' => 'HP-PRT-001',
                'asset_tag' => 'PRT-001',
                'brand' => 'HP',
                'model' => 'LaserJet Pro 4025dn',
                'specifications' => 'Impresión a doble cara, Red Ethernet, 40ppm',
                'status' => 'available',
                'purchase_price' => 8500.00,
                'purchase_date' => Carbon::now()->subMonths(1),
                'warranty_end_date' => Carbon::now()->addMonths(11),
                'invoice_number' => 'FAC-HP-002',
                'observations' => 'Impresora departamental',
            ],
        ];

        foreach ($equipment as $item) {
            Equipment::create($item);
        }
    }
}
