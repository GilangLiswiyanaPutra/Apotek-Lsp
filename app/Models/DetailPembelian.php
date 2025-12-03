<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPembelian extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_obat',
        'nomor_nota',
        'jumlah',
    ];

    /**
     * Menonaktifkan timestamps (created_at, updated_at) untuk tabel ini.
     * Hapus baris ini jika tabel Anda memiliki timestamps.
     */
    public $timestamps = false;

    /**
     * Mendefinisikan relasi ke model Obat.
     */
    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }

    /**
     * Mendefinisikan relasi ke model Pembelian.
     */
    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }
}
