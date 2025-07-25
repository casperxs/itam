<?php // app/Models/BulkImport.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_path',
        'import_type',
        'status',
        'total_records',
        'processed_records',
        'failed_records',
        'errors',
        'imported_by',
    ];

    protected $casts = [
        'errors' => 'json',
    ];

    public function importedBy()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function getSuccessRateAttribute()
    {
        if ($this->total_records == 0) return 0;
        return round(($this->processed_records / $this->total_records) * 100, 2);
    }
}