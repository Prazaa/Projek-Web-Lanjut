<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary()->default(1);
            $table->string('nama_instansi', 150)->default('Universitas Riau');
            $table->string('nama_fakultas', 150)->default('Fakultas Matematika dan Ilmu Pengetahuan Alam');
            $table->string('kode_unit', 20)->default('FMIPA');
            $table->string('alamat_fakultas')->default('Kampus Bina Widya Km. 12,5 Simpang Baru, Pekanbaru 28293');
            $table->string('nama_pimpinan_default', 120)->nullable();
            $table->string('nip_pimpinan_default', 30)->nullable();
            $table->string('jabatan_pimpinan_default', 100)->default('Wakil Dekan Bidang Kemahasiswaan, Kerja Sama dan Alumni');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE pengaturan_sistem ADD CONSTRAINT chk_pengaturan_singleton CHECK (id = 1)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
    }
};