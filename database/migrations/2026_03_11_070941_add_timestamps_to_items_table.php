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
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('item_code')->unique(); // ID Panjang sesuai Figma
        $table->string('name');
        $table->enum('category', ['Elektronik', 'Furniture', 'Perlengkapan Ibadah', 'Sarana Umum']);
        $table->enum('status', ['Available', 'Borrowed', 'Maintenance', 'Damaged'])->default('Available');
        $table->string('condition')->nullable()->default('good');
        $table->string('location')->nullable();
        $table->string('image_url')->default('default_item.png');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};
