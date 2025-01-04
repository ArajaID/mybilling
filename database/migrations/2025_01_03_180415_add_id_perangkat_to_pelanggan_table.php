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
        Schema::table('tb_pelanggan', function (Blueprint $table) {
            // Menambahkan kolom id_perangkat
            $table->unsignedBigInteger('id_perangkat')->nullable()->after('odp_id');
            // Menambahkan foreign key ke tb_perangkat
            $table->foreign('id_perangkat')
                ->references('id')->on('tb_perangkat')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pelanggan', function (Blueprint $table) {
            // Menghapus foreign key
            $table->dropForeign(['id_perangkat']);
            // Menghapus kolom id_perangkat
            $table->dropColumn('id_perangkat');
        });
    }
};
