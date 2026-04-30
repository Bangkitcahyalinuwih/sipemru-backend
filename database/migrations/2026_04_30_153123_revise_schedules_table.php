<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_revise_schedules_table.php
public function up(): void
{
    Schema::table('schedules', function (Blueprint $table) {
        // Tambah kolom yang belum ada
        $table->string('prodi')->after('lecturer');          // Program studi / jurusan
        $table->string('kelas')->nullable()->after('prodi'); // Kelas (A, B, C, dll)
        $table->integer('sks')->nullable()->after('kelas');  // Jumlah SKS
        $table->string('semester')->change();                // Ubah jadi nullable jika belum
        $table->string('tahun_ajaran')->after('semester');   // Contoh: 2024/2025
        $table->enum('jenis_kegiatan', [
            'kuliah', 'praktikum', 'ujian', 'seminar', 'lainnya'
        ])->default('kuliah')->after('tahun_ajaran');
        $table->boolean('is_active')->default(true)->after('jenis_kegiatan');
    });
}

public function down(): void
{
    Schema::table('schedules', function (Blueprint $table) {
        $table->dropColumn(['prodi', 'kelas', 'sks', 'tahun_ajaran', 'jenis_kegiatan', 'is_active']);
    });
}
};
