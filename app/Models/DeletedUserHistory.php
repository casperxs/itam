<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedUserHistory extends Model
{
    use HasFactory;

    protected $table = 'deleted_users_history';

    protected $fillable = [
        'original_user_id',
        'name',
        'email',
        'employee_id',
        'department',
        'position',
        'status',
        'notes',
        'deleted_at',
        'deleted_reason',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function getDisplayNameAttribute()
    {
        return $this->name . ' (Eliminado)';
    }
}