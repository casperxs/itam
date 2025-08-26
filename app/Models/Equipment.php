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
        'next_maintenance_date',
        'invoice_number',
        'invoice_file',
        'observations',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_end_date' => 'date',
        'next_maintenance_date' => 'date',
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

    public function equipmentRatings()
    {
        return $this->hasMany(EquipmentRating::class);
    }

    public function ratings()
    {
        return $this->hasMany(EquipmentRating::class);
    }

    public function latestRating()
    {
        return $this->hasOne(EquipmentRating::class)->latestOfMany();
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

    public function getAgeInMonths()
    {
        return $this->purchase_date ? $this->purchase_date->diffInMonths(now()) : null;
    }

    public function isNewEquipment()
    {
        $age = $this->getAgeInMonths();
        return $age && $age <= 6;
    }

    /**
     * Get a display name for the equipment
     */
    public function getNameAttribute()
    {
        return trim($this->brand . ' ' . $this->model);
    }

    /**
     * Check if the equipment is currently assigned to a user
     */
    public function isCurrentlyAssigned()
    {
        return $this->currentAssignment()->exists();
    }
}
