<?php // app/Models/MaintenanceRecord.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'performed_by',
        'type',
        'scheduled_date',
        'end_date',
        'completed_date',
        'status',
        'description',
        'performed_actions',
        'cost',
        'notes',
        'checklist_data',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'end_date' => 'datetime',
        'completed_date' => 'datetime',
        'cost' => 'decimal:2',
        'checklist_data' => 'array',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function equipmentRating()
    {
        return $this->hasOne(EquipmentRating::class);
    }

    public function isOverdue()
    {
        return $this->status === 'scheduled' && $this->scheduled_date->isPast();
    }
}
