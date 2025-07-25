<?php // app/Models/EmailTicket.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'subject',
        'from_email',
        'from_name',
        'body',
        'received_at',
        'status',
        'assigned_to',
        'it_user_id',
        'equipment_id',
        'resolution_notes',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function itUser()
    {
        return $this->belongsTo(ItUser::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
