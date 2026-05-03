<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrTicket extends Model
{
    protected $fillable = [
        'booking_id', 'token',
        'qr_image_path',
        'is_used', 'used_at', 'expires_at',
    ];

    protected $casts = [
        'is_used'    => 'boolean',
        'used_at'    => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}