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
            $table->id();  // ← AUTO INCREMENT primary key 'id'
            
            // Foreign keys
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('pic_id')->nullable();
            
            // Customer information
            $table->string('customer_name', 100);
            $table->string('company_name', 100)->nullable();
            
            // Address information - PENTING: varchar bukan bigint!
            $table->string('province_id', 50);
            $table->string('regency_id', 50)->nullable();
            $table->string('district_id', 50)->nullable();
            $table->string('village_id', 50)->nullable();
            $table->string('address', 255)->nullable();
            
            // Visit details
            $table->date('visit_date');
            $table->string('visit_purpose', 150);
            $table->text('notes')->nullable();
            $table->boolean('is_follow_up')->default(false);
            
            // Location coordinates (optional)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('sales_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('company_id')->on('company')->onDelete('set null');

            $table->foreign('pic_id')->references('pic_id')->on('company_pics')->onDelete('set null');

            
            // Indexes for performance
            $table->index('sales_id');
            $table->index('visit_date');
            $table->index('is_follow_up');
            $table->index('province_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_visits');
    }
};