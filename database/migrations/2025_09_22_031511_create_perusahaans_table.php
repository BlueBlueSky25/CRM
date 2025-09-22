<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('perusahaans', function (Blueprint $table) {
        $table->id('id_perusahaan');
        $table->string('tipe');            // dulu TIPE RS
        $table->string('nama');            // dulu Nama RS
        $table->string('alamat');          // Alamat RS
        $table->string('kota');
        $table->string('email')->nullable();
        $table->string('telepon')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaans');
    }
};
