<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_revise_bookings_table.php
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('jumlah_peserta')->nullable()->after('organization');
            $table->enum('jenis_peminjaman', [
                'kegiatan_mahasiswa',
                'seminar',
                'rapat',
                'praktikum_tambahan',
                'lainnya'
            ])->default('kegiatan_mahasiswa')->after('jumlah_peserta');
            $table->string('pic_name')->nullable()->after('jenis_peminjaman');    // Nama PIC kegiatan
            $table->string('pic_phone')->nullable()->after('pic_name');            // HP PIC
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
