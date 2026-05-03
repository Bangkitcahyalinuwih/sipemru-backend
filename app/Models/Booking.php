<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'room_id',
        'purpose',          // Keperluan
        'organization',     // Organisasi/himpunan
        'jumlah_peserta',
        'jenis_peminjaman',
        'pic_name',         // Nama PIC
        'pic_phone',        // HP PIC
        'booking_date',
        'start_time',
        'end_time',
        'status',           // pending | approved | rejected | cancelled
        'admin_notes',      // Catatan dari admin
        'approved_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'approved_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function qrTicket()
    {
        return $this->hasOne(QrTicket::class);
    }
}
