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
        // database/migrations/xxxx_xx_xx_xxxxxx_create_pelanggans_table.php
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            // [TAMBAH] Kolom untuk menghubungkan ke tabel 'users'
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('alamat');
            $table->string('kota');
            $table->string('telepon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};
