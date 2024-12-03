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
        Schema::create('tb_promopelanggan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('berlaku_bulan')->nullable();
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->unsignedBigInteger('id_promo')->nullable();
            $table->date('tanggal_klaim')->nullable();
            $table->date('tanggal_berakhir_promo')->nullable();

            $table->foreign('id_pelanggan')
            ->references('id')->on('tb_pelanggan')
            ->onDelete('cascade');
            $table->foreign('id_promo')
                ->references('id')->on('tb_promo')
                ->onDelete('cascade');

            $table->boolean('is_active')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_promopelanggan');
    }
};
