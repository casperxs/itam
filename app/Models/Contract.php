<?php // app/Models/Contract.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'contract_number',
        'service_description',
        'start_date',
        'end_date',
        'monthly_cost',
        'total_cost',
        'status',
        'alert_days_before',
        'notes',
        'contract_file',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function isExpired()
    {
        return $this->end_date->isPast();
    }

    public function needsAlert()
    {
        return $this->end_date->diffInDays(now()) <= $this->alert_days_before;
    }

    public function scopeExpiringSoon($query, $days = null)
    {
        $days = $days ?? 30;
        return $query->where('end_date', '<=', Carbon::now()->addDays($days))
                    ->where('status', 'active');
    }
}
