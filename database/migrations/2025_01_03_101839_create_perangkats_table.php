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
        Schema::create('tb_perangkat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perangkat');
            $table->string('merek')->nullable();
            $table->string('tipe')->nullable();
            $table->string('sn')->nullable()->unique();
            $table->text('catatan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_perangkat');
    }
};
