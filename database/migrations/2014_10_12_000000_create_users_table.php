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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); // Wajib untuk Login Breeze
            $table->string('phone', 15)->nullable()->unique(); // Tambahan dari temanmu
            // Menyesuaikan dengan ENUM Role baru dari temanmu
            $table->enum('role', ['ketua_takmir', 'petugas', 'warga'])->default('warga'); 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Wajib untuk Login Breeze
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
