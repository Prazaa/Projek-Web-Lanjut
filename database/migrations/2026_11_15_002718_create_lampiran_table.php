<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lampiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->foreignId('syarat_surat_id')->nullable()->constrained('syarat_surat')->nullOnDelete();
            $table->string('nama_asli', 255);
            $table->string('path_berkas', 255);
            $table->string('mime_type', 80);
            $table->unsignedInteger('ukuran_byte');
            $table->boolean('terverifikasi')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran');
    }
};