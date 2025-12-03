<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\DetailPembelian; // <-- TAMBAHKAN BARIS INI
use App\Models\Obat; // <-- TAMBAHKAN BARIS INI

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_nota',
        'tanggal_nota',
        'supplier_id',
        'kode_obat',
        
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPembelian::class, 'nomor_nota', 'nomor_nota');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}