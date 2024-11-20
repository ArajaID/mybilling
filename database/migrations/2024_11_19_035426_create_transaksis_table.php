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
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_transaksi', ['Pemasukan', 'Pengeluaran']);
            $table->string('sumber')->nullable();
            $table->decimal('debit', 10, 2)->nullable();
            $table->decimal('kredit', 10, 2)->nullable();
            $table->string('kategori')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi');
    }
};
