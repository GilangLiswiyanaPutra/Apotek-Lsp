<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans';
    protected $primaryKey = 'nota';
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang bisa diisi
    protected $fillable = ['nota', 'tanggal_nota', 'id_user'];

    /**
     * Relasi ke DetailPenjualan: Satu penjualan punya banyak detail
     */
    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'nota', 'nota');
    }

    /**
     * Relasi ke User: Satu penjualan dibuat oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}