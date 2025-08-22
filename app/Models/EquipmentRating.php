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
        if ($score <= 10) return 'Excelente';
        if ($score <= 20) return 'Optimo';
        if ($score <= 30) return 'Regulares';
        if ($score <= 40) return 'Para Cambio';
        return 'Reemplazo';
    }

    public function getLastEquipmentRating($equipmentId)
    {
        return static::where('equipment_id', $equipmentId)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
