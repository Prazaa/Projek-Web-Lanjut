<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('isi_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->unique()->constrained('pengajuan')->cascadeOnDelete();
            $table->foreignId('disusun_oleh')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->string('perihal', 200);
            $table->string('tujuan_surat', 200)->nullable();
            $table->text('paragraf_pembuka');
            $table->text('paragraf_isi');
            $table->text('paragraf_penutup');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('isi_surat');
    }
};