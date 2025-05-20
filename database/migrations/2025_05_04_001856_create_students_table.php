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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string("nisn", 10)->unique();
            $table->char("nis",8)->unique();
            $table->string("nama",35);
            $table->string('kelas', 10);
            $table->enum('jenis_kelamin',['L','P']);
            $table->text("alamat")->nullable();
            $table->string("phone")->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
