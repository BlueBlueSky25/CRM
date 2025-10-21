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
        Schema::create('sales_visits', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('visit_id');

            // Relasi ke user (sales)
            $table->unsignedBigInteger('sales_id')->nullable();
            $table->foreign('sales_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            // Relasi ke company & PIC
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')
                ->references('company_id')
                ->on('company')
                ->onDelete('set null');

            $table->unsignedBigInteger('pic_id')->nullable();
            $table->foreign('pic_id')
                ->references('pic_id')
                ->on('company_pics')
                ->onDelete('set null');

            // Data customer & lokasi (bisa dihapus kalau udah ada di tabel company)
            $table->string('customer_name', 100)->nullable();
            $table->string('company_name', 100)->nullable();
            $table->string('province_id', 50)->nullable();
            $table->string('regency_id', 50)->nullable();
            $table->string('district_id', 50)->nullable();
            $table->string('village_id', 50)->nullable();
            $table->string('address', 255)->nullable();

            // Data kunjungan
            $table->date('visit_date')->nullable();
            $table->string('visit_purpose', 150)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_is_follow_up')->default(false);

            // Tracking & metadata
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('status', 50)->nullable();

            // Relasi ke user yang input
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_visits', function (Blueprint $table) {
            // drop foreign dulu baru drop tabel, biar aman
            $table->dropForeign(['sales_id']);
            $table->dropForeign(['company_id']);
            $table->dropForeign(['pic_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('sales_visits');
    }
};
