<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'name', 'campus', 'floors',
        'address', 'description', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    // Semua ruangan di gedung ini
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // Hanya ruangan yang aktif
    public function activeRooms()
    {
        return $this->hasMany(Room::class)->where('is_active', true);
    }
}