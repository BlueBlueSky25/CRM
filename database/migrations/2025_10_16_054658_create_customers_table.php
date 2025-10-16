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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique(); // CUST001, CUST002, etc
            $table->string('name'); // Nama lengkap / Nama perusahaan
            $table->enum('type', ['Personal', 'Company'])->default('Personal');
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->text('address');
            
            // Status & Source
            $table->enum('status', ['Lead', 'Prospect', 'Active', 'Inactive'])->default('Lead');
            $table->enum('source', ['Website', 'Referral', 'Ads', 'Walk-in', 'Social Media'])->default('Website');
            
            // PIC & Notes
            $table->string('pic'); // Assigned salesperson
            $table->text('notes')->nullable();
            
            // Contact Person (untuk Company)
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone', 20)->nullable();
            
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index('customer_id');
            $table->index('type');
            $table->index('status');
            $table->index('source');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};