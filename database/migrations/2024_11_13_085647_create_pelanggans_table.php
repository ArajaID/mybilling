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
        Schema::create('tb_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelanggan');
            $table->string('email')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('blok');
            $table->string('rt');
            $table->string('area')->nullable();
            $table->string('odc_odp')->nullable();
            $table->unsignedBigInteger('id_paket')->nullable();

            $table->foreign('id_paket')
                ->references('id')->on('tb_paket')
                ->onDelete('cascade');

            $table->string('user_pppoe');
            $table->string('password_pppoe');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pelanggan');
    }
};
