<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanda_tangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->unique()->constrained('pengajuan')->cascadeOnDelete();
            $table->foreignId('penanda_tangan_id')->constrained('pengguna')->restrictOnDelete();
            $table->string('nomor_surat', 60)->unique();
            $table->string('kode_verifikasi', 64)->unique();
            $table->string('nama_penanda_tangan', 120);
            $table->string('nip_penanda_tangan', 30)->nullable();
            $table->string('jabatan_penanda_tangan', 100);
            $table->timestamp('ditandatangani_pada')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanda_tangan');
    }
};