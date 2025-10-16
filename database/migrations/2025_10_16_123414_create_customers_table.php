<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->unique()->nullable();
            $table->string('name');
            $table->enum('type', ['Personal', 'Company'])->default('Personal');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address')->nullable();
            $table->enum('status', ['Lead', 'Prospect', 'Active', 'Inactive'])->default('Lead');
            $table->string('source')->nullable();
            $table->string('pic'); // Person In Charge
            $table->text('notes')->nullable();
            
            // Contact Person (untuk Company)
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index('status');
            $table->index('type');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};