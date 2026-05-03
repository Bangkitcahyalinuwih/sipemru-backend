<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'room_id',
        'course_name',    // Nama mata kuliah
        'lecturer',       // Nama dosen
        'prodi',          // Program studi (TI, TRPL, SI, dll)
        'kelas',          // Kelas (A, B, C, TRPL, dll)
        'sks',            // Jumlah SKS
        'day_of_week',    // monday, tuesday, dst
        'start_time',     // 07:00
        'end_time',       // 10:00
        'semester',       // Ganjil / Genap
        'tahun_ajaran',   // 2024/2025
        'jenis_kegiatan', // kuliah, praktikum, ujian, dll
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sks'       => 'integer',
    ];

    // Tabel referensi nama hari
    public static array $dayLabels = [
        'monday'    => 'Senin',
        'tuesday'   => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday'  => 'Kamis',
        'friday'    => 'Jumat',
        'saturday'  => 'Sabtu',
    ];

    // Accessor: panggil $schedule->day_label untuk dapat "Senin", dll
    public function getDayLabelAttribute(): string
    {
        return self::$dayLabels[$this->day_of_week] ?? $this->day_of_week;
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}