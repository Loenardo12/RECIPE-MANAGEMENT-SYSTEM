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
    Schema::create('recipes', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->text('ingredients'); // Bisa disimpan sebagai teks biasa atau JSON string
        $table->text('cooking_steps');
        $table->integer('cooking_time')->comment('Dalam menit');
        $table->string('category');
        $table->string('image')->nullable(); // Kolom untuk nama file gambar
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
