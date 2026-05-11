<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BookingApproved;
use App\Mail\BookingRejected;
use App\Models\Booking;
use App\Models\QrTicket;
use App\Models\Room;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    // GET /api/bookings/my
    public function myBookings(Request $request)
    {
        $bookings = Booking::with(['room.building', 'qrTicket'])
            ->where('user_id', $request->user()->id)
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return response()->json($bookings);
    }

    // POST /api/bookings
    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'purpose'          => 'required|string|max:500',
            'organization'     => 'nullable|string|max:200',
            'booking_date'     => 'required|date|after_or_equal:today',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'jumlah_peserta'   => 'nullable|integer|min:1',
            'jenis_peminjaman' => 'sometimes|in:kegiatan_mahasiswa,seminar,rapat,praktikum_tambahan,lainnya',
            'pic_name'         => 'nullable|string|max:100',
            'pic_phone'        => 'nullable|string|max:20',
        ]);

        $room = Room::findOrFail($data['room_id']);

        if (!$room->is_active) {
            return response()->json(['message' => 'Ruangan tidak aktif.'], 422);
        }

        // Cek ketersediaan waktu
        $check = $this->bookingService->isAvailable(
            $data['room_id'],
            $data['booking_date'],
            $data['start_time'],
            $data['end_time']
        );

        if (!$check['available']) {
            return response()->json([
                'message' => $check['message'],
                'reason'  => $check['reason'],
            ], 422);
        }

        $status = $room->approval_type === 'auto' ? 'approved' : 'pending';

        $booking = Booking::create([
            ...$data,
            'user_id'          => $request->user()->id,
            'jenis_peminjaman' => $data['jenis_peminjaman'] ?? 'kegiatan_mahasiswa',
            'status'           => $status,
            'approved_at'      => $status === 'approved' ? now() : null,
        ]);

        if ($status === 'approved') {
            $this->generateQrAndNotify($booking);
        }

        return response()->json([
            'message' => $status === 'approved'
                ? 'Peminjaman disetujui otomatis. QR tiket dikirim ke email.'
                : 'Peminjaman diajukan. Menunggu konfirmasi admin.',
            'booking' => $booking->fresh(['room.building', 'qrTicket']),
        ], 201);
    }

    // POST /api/bookings/check-availability
    public function checkAvailability(Request $request)
    {
        $data = $request->validate([
            'room_id'      => 'required|exists:rooms,id',
            'booking_date' => 'required|date',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
        ]);

        $check          = $this->bookingService->isAvailable(...array_values($data));
        $occupiedSlots  = $this->bookingService->getOccupiedSlots($data['room_id'], $data['booking_date']);
        $availableSlots = $this->bookingService->getAvailableSlots($data['room_id'], $data['booking_date']);

        return response()->json([
            'available'       => $check['available'],
            'message'         => $check['message'],
            'reason'          => $check['reason'] ?? null,
            'occupied_slots'  => $occupiedSlots,
            'available_slots' => $availableSlots,
        ]);
    }

    // DELETE /api/bookings/{id}/cancel
    public function cancel(Request $request, int $id)
    {
        $booking = Booking::query()
            ->where('user_id', $request->user()->id)
            ->where('id', $id)->firstOrFail();

        if (!in_array($booking->status, ['pending', 'approved'])) {
            return response()->json(['message' => 'Peminjaman tidak bisa dibatalkan.'], 422);
        }

        $booking->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Peminjaman dibatalkan.']);
    }

    // GET /api/qr/verify/{token}
    public function verifyQr(string $token)
    {
        $ticket = QrTicket::with([
            'booking.user:id,name,nim,jurusan',
            'booking.room.building',
        ])->where('token', $token)->firstOrFail();

        if ($ticket->is_used) {
            return response()->json([
                'valid'   => false,
                'message' => 'Tiket sudah digunakan pada ' . $ticket->used_at?->format('d/m/Y H:i'),
            ], 422);
        }

        if (now()->isAfter($ticket->expires_at)) {
            return response()->json([
                'valid'   => false,
                'message' => 'Tiket sudah kadaluarsa.',
            ], 422);
        }

        $ticket->update(['is_used' => true, 'used_at' => now()]);

        return response()->json([
            'valid'   => true,
            'message' => 'Tiket valid. Akses diizinkan.',
            'booking' => $ticket->booking,
        ]);
    }

    // Helper: generate QR ticket + kirim email
    public function generateQrAndNotify(Booking $booking): void
    {
        try {

            // Generate token unik
            $token = Str::uuid()->toString();

            /**
             * booking_date sudah di-cast menjadi Carbon object
             * end_time biasanya format TIME dari DB
             */
            $expiresAt = Carbon::parse(
                $booking->booking_date->format('Y-m-d') . ' ' . substr($booking->end_time, 0, 5)
            );

            // Path penyimpanan QR
            $qrPath = 'qr/' . $token . '.svg';

            // Generate QR SVG
            $qrSvg = QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate(
                    config('app.url') . '/api/qr/verify/' . $token
                );

            // Simpan file QR ke storage
            Storage::disk('public')->put($qrPath, $qrSvg);

            // Simpan tiket QR ke database
            QrTicket::create([
                'booking_id'    => $booking->id,
                'token'         => $token,
                'qr_image_path' => $qrPath,
                'expires_at'    => $expiresAt,
            ]);

            // Refresh relasi terbaru
            $fresh = $booking->fresh([
                'user',
                'room.building',
                'qrTicket'
            ]);

            /**
             * Kirim email
             * Jika email gagal:
             * - booking tetap valid
             * - qr tetap ada
             * - hanya log warning
             */
            try {

                if ($fresh->user && $fresh->user->email) {

                    Mail::to($fresh->user->email)
                        ->send(new BookingApproved($fresh));
                }
            } catch (\Exception $e) {

                Log::warning('Gagal kirim email approved', [
                    'booking_id' => $booking->id,
                    'message'    => $e->getMessage(),
                    'line'       => $e->getLine(),
                    'file'       => $e->getFile(),
                ]);
            }
        } catch (\Exception $e) {

            Log::error('Generate QR gagal', [
                'booking_id' => $booking->id,
                'message'    => $e->getMessage(),
                'line'       => $e->getLine(),
                'file'       => $e->getFile(),
                'trace'      => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
