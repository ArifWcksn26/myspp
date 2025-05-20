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
        Schema::create('departments', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap department
            $table->string("name"); // Nama department
            $table->integer("cost"); // Biaya
            $table->string("month"); // Bulan pembayaran (01-12)
            $table->timestamps(); // Timestamps untuk created_at dan updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
