<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BookingController;
use App\Mail\BookingRejected;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct(private BookingController $bookingController) {}

    // GET /api/admin/stats
    public function stats()
    {
        $now  = now()->format('H:i');
        $date = today()->toDateString();
        $day  = strtolower(now()->format('l'));

        $total = Room::active()->count('id');

        $occupiedNow = Room::active()
            ->where(function ($q) use ($date, $now, $day) {
                $q->whereHas('schedules', fn($s) =>
                    $s->where('day_of_week', $day)->where('is_active', true)
                      ->where('start_time', '<=', $now)->where('end_time', '>', $now)
                )->orWhereHas('bookings', fn($b) =>
                    $b->where('booking_date', $date)->where('status', 'approved')
                      ->where('start_time', '<=', $now)->where('end_time', '>', $now)
                );
            })->count();

        return response()->json([
            'total_rooms'     => $total,
            'available_rooms' => $total - $occupiedNow,
            'occupied_rooms'  => $occupiedNow,
            'pending_count'   => Booking::query()
                ->where('status', 'pending')->count(),
            'today_bookings'  => Booking::query()
                ->where('booking_date', $date)->count(),
        ]);
    }

    // GET /api/admin/bookings
    public function bookings(Request $request)
    {
        $query = Booking::with(['user:id,name,nim,jurusan', 'room.building'])->latest();

        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('date'))    $query->where('booking_date', $request->date);
        if ($request->filled('room_id')) $query->where('room_id', $request->room_id);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('purpose', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($u) =>
                      $u->where('name', 'like', '%' . $request->search . '%')
                  );
            });
        }

        return response()->json($query->paginate(15));
    }

    // POST /api/admin/bookings/{id}/approve
    public function approve(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending') {
            return response()->json([
                'message' => 'Hanya peminjaman pending yang bisa disetujui.'
            ], 422);
        }

        $booking->update([
            'status'      => 'approved',
            'admin_notes' => $request->input('notes'),
            'approved_at' => now(),
        ]);

        $this->bookingController->generateQrAndNotify($booking->fresh());

        return response()->json(['message' => 'Peminjaman disetujui. QR tiket dikirim ke peminjam.']);
    }

    // POST /api/admin/bookings/{id}/reject
    public function reject(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'pending') {
            return response()->json([
                'message' => 'Hanya peminjaman pending yang bisa ditolak.'
            ], 422);
        }

        $data = $request->validate(['notes' => 'required|string|max:500']);

        $booking->update([
            'status'      => 'rejected',
            'admin_notes' => $data['notes'],
        ]);

        try {
            $booking->load(['user', 'room.building']);
            Mail::to($booking->user->email)->send(new BookingRejected($booking));
        } catch (\Exception $e) {
            Log::warning('Gagal kirim email rejected: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Peminjaman ditolak. Notifikasi dikirim.']);
    }
}