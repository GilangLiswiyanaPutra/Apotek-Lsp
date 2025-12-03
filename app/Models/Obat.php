<?php

// app/Models/Obat.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Obat extends Model
{
    use HasFactory;

    // app/Models/Obat.php
    protected $fillable = [
        'kode_obat', // <-- TAMBAHKAN INI
        'nama', 
        'jenis', 
        'satuan', 
        'harga_jual', 
        'harga_beli', 
        'stok', 
        'supplier', 
        'gambar',
        'tgl_kadaluarsa'
    ];

    protected $casts = [
        'tgl_kadaluarsa' => 'date',
    ];

    /**
     * Check if medicine is near expiration (within 30 days)
     */
    public function isNearExpiration()
    {
        if (!$this->tanggal_kadaluarsa) {
            return false;
        }
        
        $daysDiff = Carbon::now()->diffInDays($this->tanggal_kadaluarsa, false);
        return $daysDiff <= 30 && $daysDiff >= 0;
    }

    /**
     * Check if medicine is expired
     */
    public function isExpired()
    {
        if (!$this->tanggal_kadaluarsa) {
            return false;
        }
        
        return Carbon::now()->isAfter($this->tanggal_kadaluarsa);
    }

    /**
     * Get expiration status with color indicator
     */
    public function getExpirationStatus()
    {
        if (!$this->tanggal_kadaluarsa) {
            return ['status' => 'Tidak ada data', 'class' => 'badge-secondary'];
        }

        if ($this->isExpired()) {
            return ['status' => 'Kadaluarsa', 'class' => 'badge-danger'];
        }

        if ($this->isNearExpiration()) {
            return ['status' => 'Hampir kadaluarsa', 'class' => 'badge-warning'];
        }

        return ['status' => 'Aman', 'class' => 'badge-success'];
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpiration()
    {
        if (!$this->tanggal_kadaluarsa) {
            return null;
        }
        
        return Carbon::now()->diffInDays($this->tanggal_kadaluarsa, false);
    }
}