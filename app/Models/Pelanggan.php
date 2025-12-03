<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelanggan extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'kode', 'alamat', 'kota', 'telepon'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}