<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan', 25)->unique();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->restrictOnDelete();
            $table->string('keperluan', 255);
            $table->json('data_tambahan')->nullable();
            $table->string('status', 20)->default('diajukan')->index();

            // Snapshot data pemohon saat pengajuan dibuat
            $table->string('nama_snapshot', 120);
            $table->string('nim_snapshot', 20);
            $table->string('prodi_snapshot', 100);
            $table->unsignedTinyInteger('semester_snapshot');

            $table->text('catatan_revisi')->nullable();
            $table->text('alasan_ditolak')->nullable();
            $table->timestamp('diajukan_pada')->useCurrent();
            $table->timestamp('selesai_pada')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE pengajuan ADD CONSTRAINT chk_pengajuan_status CHECK (status IN ('diajukan', 'diproses', 'revisi', 'siap_ttd', 'selesai', 'ditolak'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};