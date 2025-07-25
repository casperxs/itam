<?php // app/Services/BulkImportService.php
namespace App\Services;

use App\Models\BulkImport;
use App\Models\Equipment;
use App\Models\ItUser;
use App\Models\EquipmentType;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BulkImportService
{
    public function processImport(BulkImport $import)
    {
        try {
            $import->update(['status' => 'processing']);

            $content = Storage::disk('private')->get($import->file_path);
            $lines = explode("\n", $content);
            $lines = array_filter(array_map('trim', $lines));

            $import->update(['total_records' => count($lines)]);

            $errors = [];
            $processed = 0;
            $failed = 0;

            foreach ($lines as $index => $line) {
                try {
                    if ($import->import_type === 'equipment') {
                        $this->processEquipmentLine($line, $index + 1);
                    } elseif ($import->import_type === 'users') {
                        $this->processUserLine($line, $index + 1);
                    }
                    $processed++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = [
                        'line' => $index + 1,
                        'data' => $line,
                        'error' => $e->getMessage()
                    ];
                }

                $import->update([
                    'processed_records' => $processed,
                    'failed_records' => $failed,
                ]);
            }

            $import->update([
                'status' => 'completed',
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            $import->update([
                'status' => 'failed',
                'errors' => [['error' => $e->getMessage()]]
            ]);
            Log::error('Bulk import failed', ['import_id' => $import->id, 'error' => $e->getMessage()]);
        }
    }

    private function processEquipmentLine($line, $lineNumber)
    {
        // Formato: tipo|proveedor|numero_serie|etiqueta|marca|modelo|especificaciones|precio|fecha_compra|fecha_garantia|numero_factura|observaciones
        $parts = explode('|', $line);
        
        if (count($parts) < 6) {
            throw new \Exception("Formato inválido en línea {$lineNumber}. Se requieren al menos 6 campos.");
        }

        $equipmentType = EquipmentType::where('name', trim($parts[0]))->first();
        if (!$equipmentType) {
            throw new \Exception("Tipo de equipo '{$parts[0]}' no encontrado en línea {$lineNumber}.");
        }

        $supplier = Supplier::where('name', trim($parts[1]))->first();
        if (!$supplier) {
            throw new \Exception("Proveedor '{$parts[1]}' no encontrado en línea {$lineNumber}.");
        }

        $serialNumber = trim($parts[2]);
        if (Equipment::where('serial_number', $serialNumber)->exists()) {
            throw new \Exception("Número de serie '{$serialNumber}' ya existe en línea {$lineNumber}.");
        }

        Equipment::create([
            'equipment_type_id' => $equipmentType->id,
            'supplier_id' => $supplier->id,
            'serial_number' => $serialNumber,
            'asset_tag' => !empty($parts[3]) ? trim($parts[3]) : null,
            'brand' => trim($parts[4]),
            'model' => trim($parts[5]),
            'specifications' => !empty($parts[6]) ? trim($parts[6]) : null,
            'status' => 'available',
            'purchase_price' => !empty($parts[7]) ? floatval($parts[7]) : null,
            'purchase_date' => !empty($parts[8]) ? date('Y-m-d', strtotime($parts[8])) : null,
            'warranty_end_date' => !empty($parts[9]) ? date('Y-m-d', strtotime($parts[9])) : null,
            'invoice_number' => !empty($parts[10]) ? trim($parts[10]) : null,
            'observations' => !empty($parts[11]) ? trim($parts[11]) : null,
        ]);
    }

    private function processUserLine($line, $lineNumber)
    {
        // Formato: nombre|email|id_empleado|departamento|posicion|estado|notas
        $parts = explode('|', $line);
        
        if (count($parts) < 5) {
            throw new \Exception("Formato inválido en línea {$lineNumber}. Se requieren al menos 5 campos.");
        }

        $email = trim($parts[1]);
        $employeeId = trim($parts[2]);

        if (ItUser::where('email', $email)->exists()) {
            throw new \Exception("Email '{$email}' ya existe en línea {$lineNumber}.");
        }

        if (ItUser::where('employee_id', $employeeId)->exists()) {
            throw new \Exception("ID de empleado '{$employeeId}' ya existe en línea {$lineNumber}.");
        }

        ItUser::create([
            'name' => trim($parts[0]),
            'email' => $email,
            'employee_id' => $employeeId,
            'department' => trim($parts[3]),
            'position' => trim($parts[4]),
            'status' => !empty($parts[5]) ? trim($parts[5]) : 'active',
            'notes' => !empty($parts[6]) ? trim($parts[6]) : null,
        ]);
    }

    public function generateTemplate($type)
    {
        if ($type === 'equipment') {
            return $this->generateEquipmentTemplate();
        } elseif ($type === 'users') {
            return $this->generateUsersTemplate();
        }

        throw new \Exception('Tipo de plantilla no válido');
    }

    private function generateEquipmentTemplate()
    {
        $content = "# Plantilla para importación de equipos\n";
        $content .= "# Formato: tipo|proveedor|numero_serie|etiqueta|marca|modelo|especificaciones|precio|fecha_compra|fecha_garantia|numero_factura|observaciones\n";
        $content .= "# Ejemplo:\n";
        $content .= "Computadora|Dell Inc|SN123456|TAG001|Dell|Latitude 5520|Intel i7, 16GB RAM, 512GB SSD|15000.00|2024-01-15|2027-01-15|FAC-001|Equipo para gerencia\n";
        $content .= "Teléfono|Samsung|IMEI123456789|TEL001|Samsung|Galaxy S24|Android 14, 256GB|8000.00|2024-02-01|2025-02-01|FAC-002|Teléfono corporativo\n";
        
        return $content;
    }

    private function generateUsersTemplate()
    {
        $content = "# Plantilla para importación de usuarios\n";
        $content .= "# Formato: nombre|email|id_empleado|departamento|posicion|estado|notas\n";
        $content .= "# Ejemplo:\n";
        $content .= "Juan Pérez|juan.perez@empresa.com|EMP001|Sistemas|Desarrollador|active|Usuario de desarrollo\n";
        $content .= "María García|maria.garcia@empresa.com|EMP002|Contabilidad|Contador|active|Usuario de contabilidad\n";
        
        return $content;
    }
}
