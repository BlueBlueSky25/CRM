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

            // === Foreign Keys (relasi utama) ===
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();

            // === Lokasi administratif (pakai VARCHAR) ===
            $table->string('province_id')->nullable();
            $table->string('regency_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('village_id')->nullable();

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('set null');
            $table->foreign('regency_id')->references('id')->on('regencies')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('set null');


            // === Data Customer ===
            $table->string('name');
            $table->enum('type', ['Personal', 'Company'])->default('Personal');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address')->nullable();

            // === Status & Sumber ===
            $table->enum('status', ['Lead', 'Prospect', 'Active', 'Inactive'])->default('Lead');
            $table->string('source')->nullable();
            $table->string('pic'); // Person In Charge
            $table->text('notes')->nullable();

            // === Contact Person (untuk Company) ===
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();

            $table->timestamps();

            // === Index untuk performa query ===
            $table->index(['status', 'type', 'source']);

            // === Foreign Key Constraints (manual reference) ===
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('company_id')
                ->references('company_id')
                ->on('company')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
