<?php // app/Models/UserDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'it_user_id',
        'document_type',
        'document_name',
        'file_path',
        'has_signature',
        'signature_type',
        'description',
    ];

    protected $casts = [
        'has_signature' => 'boolean',
    ];

    public function itUser()
    {
        return $this->belongsTo(ItUser::class);
    }
}
