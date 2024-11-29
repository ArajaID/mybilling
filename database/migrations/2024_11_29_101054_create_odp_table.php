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
        Schema::create('tb_odp', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('odc_id');
            $table->string('odp');
            $table->string('lokasi');
            $table->string('port');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        
            $table->foreign('odc_id')->references('id')->on('tb_odc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_odp');
    }
};
