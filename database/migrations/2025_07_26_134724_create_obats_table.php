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
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_obat')->unique(); // <-- TAMBAHKAN KOLOM INI
            $table->string('nama');
            $table->string('jenis');
            $table->string('satuan');
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('harga_beli', 15, 2);
            $table->integer('stok');
            $table->string('supplier');
            $table->string('gambar')->nullable();
             $table->date('tgl_kadaluarsa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
