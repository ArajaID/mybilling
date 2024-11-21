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
        Schema::create('tb_tagihan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tagihan')->unique();
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->datetime('tanggal_tagihan');
            $table->datetime('tanggal_jatuh_tempo')->nullable();
            $table->decimal('jumlah_tagihan', 10, 2);
            $table->enum('status_pembayaran', ['BELUM-LUNAS', 'LUNAS']);
            $table->text('deskripsi')->nullable();

            $table->foreign('id_pelanggan')
            ->references('id')->on('tb_pelanggan')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_tagihan');
    }
};
