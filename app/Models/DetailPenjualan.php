<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualans';
    public $timestamps = false; // Tidak ada kolom created_at/updated_at

    // Kolom yang bisa diisi
    protected $fillable = ['nota', 'kode_obat', 'jumlah'];

    /**
     * Relasi ke Obat: Satu detail penjualan merujuk ke satu obat
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'kode_obat', 'kode_obat');
    }
}