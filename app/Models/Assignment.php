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
        'user_name',
        'user_email',
        'user_employee_id',
        'user_department',
        'user_position',
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

    public function getUserName()
    {
        return $this->user_name ?: ($this->itUser ? $this->itUser->name : 'Usuario eliminado');
    }

    public function getUserEmail()
    {
        return $this->user_email ?: ($this->itUser ? $this->itUser->email : null);
    }

    public function getUserEmployeeId()
    {
        return $this->user_employee_id ?: ($this->itUser ? $this->itUser->employee_id : null);
    }

    public function getUserDepartment()
    {
        return $this->user_department ?: ($this->itUser ? $this->itUser->department : null);
    }

    public function getUserPosition()
    {
        return $this->user_position ?: ($this->itUser ? $this->itUser->position : null);
    }
}