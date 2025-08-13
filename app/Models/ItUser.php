<?php // app/Models/ItUser.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItUser extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function currentAssignments()
    {
        return $this->hasMany(Assignment::class)->whereNull('returned_at');
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function emailTickets()
    {
        return $this->hasMany(EmailTicket::class);
    }

    public function isDeleted()
    {
        return !is_null($this->deleted_at);
    }

    public function softDelete($reason = null)
    {
        // Guardar en el histórico de usuarios eliminados
        DeletedUserHistory::create([
            'original_user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'employee_id' => $this->employee_id,
            'department' => $this->department,
            'position' => $this->position,
            'status' => $this->status,
            'notes' => $this->notes,
            'deleted_at' => now(),
            'deleted_reason' => $reason,
        ]);

        // Marcar como eliminado
        $this->update([
            'deleted_at' => now(),
            'deleted_reason' => $reason,
        ]);

        return true;
    }

    public function restore()
    {
        $this->update([
            'deleted_at' => null,
            'deleted_reason' => null,
        ]);

        return true;
    }

    // Scope para usuarios activos
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Scope para usuarios eliminados
    public function scopeOnlyDeleted($query)
    {
        return $query->whereNotNull('deleted_at');
    }

    // Scope para todos los usuarios (incluyendo eliminados)
    public function scopeWithDeleted($query)
    {
        return $query;
    }
}