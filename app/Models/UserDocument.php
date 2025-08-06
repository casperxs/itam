<?php // app/Models/UserDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'it_user_id',
        'original_name',
        'filename',
        'file_path',
        'file_size',
        'mime_type',
        'document_type',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function itUser()
    {
        return $this->belongsTo(ItUser::class);
    }
}
