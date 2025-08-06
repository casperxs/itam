<?php // app/Models/Equipment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_type_id',
        'supplier_id',
        'serial_number',
        'asset_tag',
        'brand',
        'model',
        'specifications',
        'status',
        'valoracion',
        'purchase_price',
        'purchase_date',
        'warranty_end_date',
        'invoice_number',
        'invoice_file',
        'observations',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_end_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)->whereNull('returned_at');
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function emailTickets()
    {
        return $this->hasMany(EmailTicket::class);
    }

    public function isWarrantyExpired()
    {
        return $this->warranty_end_date && $this->warranty_end_date->isPast();
    }

    public function warrantyExpiresIn($days = 30)
    {
        return $this->warranty_end_date && 
               $this->warranty_end_date->isFuture() && 
               $this->warranty_end_date->diffInDays(now()) <= $days;
    }
}
