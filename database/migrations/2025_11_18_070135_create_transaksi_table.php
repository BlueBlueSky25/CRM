<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_visit_id')->nullable();
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('company_id');
            $table->string('nama_sales');
            $table->string('nama_perusahaan');
            $table->decimal('nilai_proyek', 15, 2);
            $table->enum('status', ['Deals', 'Fails'])->default('Deals');
            $table->string('bukti_spk')->nullable();
            $table->string('bukti_dp')->nullable();
            $table->date('tanggal_mulai_kerja')->nullable();
            $table->date('tanggal_selesai_kerja')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys - FIXED: gunakan sales_visits (dengan 's')
            $table->foreign('sales_visit_id')->references('id')->on('sales_visits')->onDelete('set null');
            $table->foreign('sales_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('company_id')->on('company')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};