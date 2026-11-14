<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syarat_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->cascadeOnDelete();
            $table->string('nama_dokumen', 120);
            $table->string('keterangan')->nullable();
            $table->boolean('wajib')->default(true);
            $table->string('format_diizinkan', 50)->default('pdf,jpg,jpeg,png');
            $table->unsignedSmallInteger('maks_ukuran_kb')->default(2048);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syarat_surat');
    }
};