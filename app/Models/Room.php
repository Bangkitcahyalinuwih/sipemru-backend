<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Room extends Model
{
    protected $fillable = [
        'building_id', 'code', 'name', 'type',
        'capacity', 'floor', 'approval_type',
        'description', 'facilities', 'foto', 'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'facilities' => 'array', // Otomatis encode/decode JSON
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->foto) return null;

        return asset('storage/' . $this->foto);
    }
    // Scope: hanya ruangan aktif
    // Penggunaan: Room::active()->get()
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}