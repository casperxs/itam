<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentRating extends Model
{
    protected $fillable = [
        'equipment_id',
        'maintenance_record_id',
        'evaluated_by',
        'criteria_evaluations',
        'total_score',
        'rating_category',
        'notes',
    ];

    protected $casts = [
        'criteria_evaluations' => 'array',
        'total_score' => 'decimal:2',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function maintenanceRecord()
    {
        return $this->belongsTo(MaintenanceRecord::class);
    }

    public function evaluatedBy()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public static function calculateCategory($score)
    {
        // Lógica: 100% = Excelente, hacia abajo = peor calidad
        if ($score > 90) return 'Excelente 🟢';    // 100% - 90.1%
        if ($score > 80) return 'Óptimo 🔵';       // 90% - 80.1%  
        if ($score > 70) return 'Regular 🟡';      // 80% - 70.1%
        if ($score > 60) return 'Para Cambio 🟠'; // 70% - 60.1%
        return 'Reemplazo 🔴';                     // 60% - 0%
    }

    public function getLastEquipmentRating($equipmentId)
    {
        return static::where('equipment_id', $equipmentId)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
