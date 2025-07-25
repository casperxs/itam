<?php // app/Models/Assignment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'it_user_id',
        'assigned_by',
        'assigned_at',
        'returned_at',
        'assignment_notes',
        'return_notes',
        'assignment_document',
        'document_signed',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
        'document_signed' => 'boolean',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function itUser()
    {
        return $this->belongsTo(ItUser::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function isActive()
    {
        return is_null($this->returned_at);
    }
}