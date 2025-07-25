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
}