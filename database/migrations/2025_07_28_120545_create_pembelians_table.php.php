<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('pembelians', function (Blueprint $table) {
        $table->id();
        // Pastikan baris ini ada dan benar
        $table->foreignId('pembelian_id')->constrained('pembelians')->onDelete('cascade');
        $table->foreignId('obat_id')->constrained('obats');
        $table->integer('jumlah');
        $table->decimal('harga_beli', 15, 2);
        $table->decimal('subtotal', 15, 2);
        // Kolom timestamps tidak diperlukan di sini sesuai model Anda
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        
    }
};
