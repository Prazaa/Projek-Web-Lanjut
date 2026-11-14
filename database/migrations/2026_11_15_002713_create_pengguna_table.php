<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 120);
            $table->string('email', 150)->unique();
            $table->string('password_hash')->nullable();
            $table->enum('role', ['mahasiswa', 'staf', 'pimpinan'])->default('mahasiswa');
            $table->string('nip', 30)->nullable()->unique();
            $table->string('jabatan', 100)->nullable();
            $table->string('google_id', 100)->nullable()->unique();
            $table->string('avatar_url')->nullable();
            $table->boolean('aktif')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};