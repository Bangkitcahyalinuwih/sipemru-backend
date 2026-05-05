<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;

class RoomStatusService
{
    /**
     * Tentukan status ruangan saat ini.
     *
     * @return string 'available' | 'occupied' | 'pending'
     */
    public function getStatus(Room $room, string $date, string $time): string
    {
        $dayName = strtolower(Carbon::parse($date)->format('l'));

        // Cek 1: Ada jadwal kuliah yang sedang berlangsung?
        $inSchedule = Schedule::query()
            ->where('room_id', $room->id)
            ->where('day_of_week', $dayName)
            ->where('is_active', true)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->exists();

        if ($inSchedule) return 'occupied';

        // Cek 2: Ada peminjaman approved yang sedang berlangsung?
        $inBooking = Booking::query()
            ->where('room_id', $room->id)
            ->where('booking_date', $date)
            ->where('status', 'approved')
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->exists();

        if ($inBooking) return 'occupied';

        // Cek 3: Ada peminjaman pending hari ini? (menunggu konfirmasi)
        $hasPending = Booking::query()
            ->where('room_id', $room->id)
            ->where('booking_date', $date)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) return 'pending';

        return 'available';
    }

    /**
     * Ambil detail aktivitas yang sedang berjalan di ruangan.
     * Dipakai untuk menampilkan info di card dashboard.
     *
     * @return array|null
     */
    public function getCurrentActivity(Room $room, string $date, string $time): ?array
    {
        $dayName = strtolower(Carbon::parse($date)->format('l'));

        // Cek jadwal kuliah
        $schedule = Schedule::query()
            ->where('room_id', $room->id)
            ->where('day_of_week', $dayName)
            ->where('is_active', true)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->first();

        if ($schedule) {
            return [
                'type'       => 'jadwal_kuliah',
                'title'      => $schedule->course_name,
                'lecturer'   => $schedule->lecturer,
                'prodi'      => $schedule->prodi,
                'kelas'      => $schedule->kelas,
                'start_time' => substr($schedule->start_time, 0, 5),
                'end_time'   => substr($schedule->end_time, 0, 5),
            ];
        }

        // Cek peminjaman aktif
        $booking = Booking::with('user:id,name')
            ->where('room_id', $room->id)
            ->where('booking_date', $date)
            ->where('status', 'approved')
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->first();

        if ($booking) {
            return [
                'type'         => 'peminjaman',
                'title'        => $booking->purpose,
                'peminjam'     => $booking->user->name,
                'organization' => $booking->organization,
                'start_time'   => substr($booking->start_time, 0, 5),
                'end_time'     => substr($booking->end_time, 0, 5),
            ];
        }

        return null;
    }
}