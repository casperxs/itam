<?php // app/Models/Supplier.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'phone',
        'address',
        'tax_id',
        'notes',
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}