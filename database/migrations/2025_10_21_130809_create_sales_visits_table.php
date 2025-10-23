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
            $table->id('visit_id');
            
            // Foreign keys
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('pic_id')->nullable();
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('regency_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
            $table->unsignedBigInteger('user_id');
            
            // Customer information
            $table->string('customer_name');
            $table->string('company_name')->nullable();
            
            // Address information
            $table->text('address')->nullable();
            
            // Visit details
            $table->date('visit_date');
            $table->text('visit_purpose');
            $table->text('notes')->nullable();
            $table->boolean('is_follow_up')->default(false);
            
            // Location coordinates (optional)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            // Timestamps
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('sales_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('pic_id')->references('id')->on('pics')->onDelete('set null');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('set null');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            
            // Indexes for performance
            $table->index('sales_id');
            $table->index('visit_date');
            $table->index('is_follow_up');
            $table->index('status');
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