<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;

class BookingService
{
    /**
     * Cek apakah slot waktu tersedia untuk peminjaman.
     *
     * @return array ['available' => bool, 'message' => string, 'reason' => string|null]
     */
    public function isAvailable(
        int $roomId,
        string $date,
        string $startTime,
        string $endTime,
        ?int $excludeBookingId = null
    ): array {
        $dayName = strtolower(Carbon::parse($date)->format('l'));

        // --- CEK 1: Bentrok dengan jadwal kuliah tetap ---
        $scheduleConflict = Schedule::query()
            ->where('room_id', $roomId)
            ->where('day_of_week', $dayName)
            ->where('is_active', true)
            ->where(function ($q) use ($startTime, $endTime) {
                // Overlap: start < endBaru AND end > startBaru
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($scheduleConflict) {
            return [
                'available' => false,
                'reason'    => 'schedule_conflict',
                'message'   => sprintf(
                    'Bentrok dengan jadwal kuliah: %s (%s - %s)',
                    $scheduleConflict->course_name,
                    substr($scheduleConflict->start_time, 0, 5),
                    substr($scheduleConflict->end_time, 0, 5)
                ),
            ];
        }

        // --- CEK 2: Bentrok dengan peminjaman lain ---
        $bookingConflict = Booking::query()
            ->where('room_id', $roomId)
            ->where('booking_date', $date)
            ->whereIn('status', ['approved', 'pending'])
            ->when($excludeBookingId, fn($q) => $q->where('id', '!=', $excludeBookingId))
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($bookingConflict) {
            return [
                'available' => false,
                'reason'    => 'booking_conflict',
                'message'   => sprintf(
                    'Bentrok dengan peminjaman yang sudah ada (%s - %s)',
                    substr($bookingConflict->start_time, 0, 5),
                    substr($bookingConflict->end_time, 0, 5)
                ),
            ];
        }

        return [
            'available' => true,
            'message'   => 'Ruangan tersedia di waktu tersebut.',
        ];
    }

    /**
     * Ambil semua slot yang terpakai di tanggal tertentu.
     * Dipakai untuk tampilan timeline di frontend.
     */
    public function getOccupiedSlots(int $roomId, string $date): array
    {
        $dayName = strtolower(Carbon::parse($date)->format('l'));

        $schedules = Schedule::query()
            ->where('room_id', $roomId)
            ->where('day_of_week', $dayName)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get(['id', 'course_name', 'lecturer', 'prodi', 'kelas',
                   'start_time', 'end_time', 'jenis_kegiatan']);

        $bookings = Booking::query()
            ->where('room_id', $roomId)
            ->where('booking_date', $date)
            ->whereIn('status', ['approved', 'pending'])
            ->with('user:id,name')
            ->orderBy('start_time')
            ->get(['id', 'purpose', 'organization', 'status',
                   'start_time', 'end_time', 'user_id']);

        return [
            'jadwal_kuliah' => $schedules,
            'peminjaman'    => $bookings,
        ];
    }

    /**
     * Hitung slot waktu yang masih tersedia.
     * Dipakai untuk saran waktu ke mahasiswa saat form peminjaman.
     */
    public function getAvailableSlots(int $roomId, string $date): array
    {
        $occupied = $this->getOccupiedSlots($roomId, $date);

        $blockedSlots = [];

        foreach ($occupied['jadwal_kuliah'] as $s) {
            $blockedSlots[] = [
                substr($s->start_time, 0, 5),
                substr($s->end_time, 0, 5),
            ];
        }

        foreach ($occupied['peminjaman'] as $b) {
            $blockedSlots[] = [
                substr($b->start_time, 0, 5),
                substr($b->end_time, 0, 5),
            ];
        }

        // Sort berdasarkan jam mulai
        usort($blockedSlots, fn($a, $b) => strcmp($a[0], $b[0]));

        // Hitung gap antara slot terpakai
        $available = [];
        $cursor = '07:00';
        $operationalEnd = '21:00';

        foreach ($blockedSlots as [$start, $end]) {
            if ($cursor < $start) {
                $available[] = ['start' => $cursor, 'end' => $start];
            }
            // Geser cursor ke akhir slot ini (jika lebih maju)
            if ($end > $cursor) {
                $cursor = $end;
            }
        }

        // Sisa waktu setelah slot terakhir
        if ($cursor < $operationalEnd) {
            $available[] = ['start' => $cursor, 'end' => $operationalEnd];
        }

        return $available;
    }
}