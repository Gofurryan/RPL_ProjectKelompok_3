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
        Schema::table('users', function (Blueprint $table) {
            //
            // Dibuat nullable() agar tidak error karena di database sudah ada 2 data user yang belum punya email.
            $table->string('email')->unique()->nullable()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');

            // Menambahkan kolom password setelah kolom phone
            $table->string('password')->after('phone');
            
            // Kolom token untuk fitur "Remember Me" saat login
            $table->rememberToken()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            // Menghapus kolom jika kita melakukan rollback
            $table->dropColumn(['email', 'email_verified_at', 'password', 'remember_token']);
        });
    }
};
