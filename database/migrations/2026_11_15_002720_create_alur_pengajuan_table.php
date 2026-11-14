<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alur_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->cascadeOnDelete();
            $table->foreignId('oleh_pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->string('status_dari', 20)->nullable();
            $table->string('status_ke', 20);
            $table->text('keterangan')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alur_pengajuan');
    }
};