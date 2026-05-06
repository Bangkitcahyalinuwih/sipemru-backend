<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\RoomStatusService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function __construct(private RoomStatusService $statusService) {}

    /**
     * GET /api/rooms
     * Daftar ruangan + status real-time + filter lengkap
     * Query params: campus, building_id, floor, type, status, date, time
     */
    public function index(Request $request)
    {
        $query = Room::with('building')->active();

        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        if ($request->filled('campus')) {
            $query->whereHas('building', fn($q) =>
                $q->where('campus', $request->campus)
            );
        }

        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $rooms = $query->get();
        $date = $request->input('date', today()->toDateString());
        $time = $request->input('time', now()->format('H:i'));
        $date = $request->input('date', today()->toDateString());
        $time = $request->input('time', now()->format('H:i'));

        // Inject status real-time ke tiap ruangan
        $rooms->each(function ($room) use ($date, $time) {
            $room->current_status   = $this->statusService->getStatus($room, $date, $time);
            $room->current_activity = $this->statusService->getCurrentActivity($room, $date, $time);
        });

        // Filter by status (setelah inject)
        if ($request->filled('status')) {
            $rooms = $rooms->filter(
                fn($r) => $r->current_status === $request->status
            )->values();
        }

        return response()->json([
            'data'    => $rooms,
            'summary' => [
                'total'     => $rooms->count(),
                'available' => $rooms->where('current_status', 'available')->count(),
                'occupied'  => $rooms->where('current_status', 'occupied')->count(),
                'pending'   => $rooms->where('current_status', 'pending')->count(),
            ],
        ]);
    }

    /**
     * GET /api/rooms/{id}
     * Detail ruangan + timeline hari ini
     */
    public function show(Request $request, int $id)
    {
        $room = Room::with([
            'building',
            'schedules' => fn($q) => $q->where('is_active', true)
                                        ->orderBy('day_of_week')
                                        ->orderBy('start_time'),
        ])->findOrFail($id);

        $date    = $request->input('date', today()->toDateString());
        $time    = $request->input('time', now()->format('H:i'));
        $dayName = strtolower(Carbon::parse($date)->format('l'));

        // Jadwal kuliah di hari yang dipilih
        $todaySchedules = $room->schedules
            ->filter(fn($s) => $s->day_of_week === $dayName)
            ->values();

        // Peminjaman di tanggal yang dipilih
        $todayBookings = $room->bookings()
            ->with('user:id,name')
            ->where('booking_date', $date)
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('start_time')
            ->get();

        // Gabungkan jadi timeline
        $timeline = $this->buildTimeline($todaySchedules, $todayBookings);

        $room->current_status   = $this->statusService->getStatus($room, $date, $time);
        $room->current_activity = $this->statusService->getCurrentActivity($room, $date, $time);

        return response()->json([
            'room'     => $room,
            'timeline' => $timeline,
            'date'     => $date,
        ]);
    }

    // POST /api/admin/rooms
    public function store(Request $request)
    {
        $data = $request->validate([
            'building_id'   => 'required|exists:buildings,id',
            'code'          => 'required|string|max:20|unique:rooms,code',
            'name'          => 'required|string|max:100',
            'type'          => 'required|in:kelas,lab,auditorium,rapat',
            'capacity'      => 'required|integer|min:1',
            'floor'         => 'required|integer|min:1',
            'approval_type' => 'required|in:auto,manual',
            'description'   => 'nullable|string|max:500',
            'facilities'    => 'nullable|array',
            'facilities.*'  => 'string|max:50',
            'is_active'     => 'sometimes|boolean',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        if ($request->hasFile('photo')) {
            $data['foto'] = $request->file('photo')->store('rooms_photos', 'public');
        }

        unset($data['photo']);

        $room = Room::create($data);
        $room->load('building');

        return response()->json([
            'message' => 'Ruangan berhasil ditambahkan.',
            'room'    => $room,
        ], 201);
    }

    // PUT /api/admin/rooms/{id}
    public function update(Request $request, int $id)
    {
        $room = Room::findOrFail($id);

        $data = $request->validate([
            'building_id'   => 'sometimes|exists:buildings,id',
            'code'          => 'sometimes|string|max:20|unique:rooms,code,' . $id,
            'name'          => 'sometimes|string|max:100',
            'type'          => 'sometimes|in:kelas,lab,auditorium,rapat',
            'capacity'      => 'sometimes|integer|min:1',
            'floor'         => 'sometimes|integer|min:1',
            'approval_type' => 'sometimes|in:auto,manual',
            'description'   => 'nullable|string|max:500',
            'facilities'    => 'nullable|array',
            'is_active'     => 'sometimes|boolean',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('photo')) {
            if($room->foto) {
                Storage::disk('public')->delete($room->foto);
            }
            $data['foto'] = $request->file('photo')->store('rooms_photos', 'public');
        }

        $room->update($data);

        return response()->json([
            'message' => 'Ruangan berhasil diperbarui.',
            'room'    => $room->fresh('building'),
        ]);
    }

    public function deletePhoto(int $id)
    {
        $room = Room::findOrFail($id);

        if (!$room->foto) {
            return response()->json([
                'message' => 'Ruangan tidak memiliki foto.',
            ], 400);
        };

        Storage::disk('public')->delete($room->foto);
        $room->update([
            'foto' => null
        ]);

        return response()->json([
            'message' => 'Foto berhasil dihapus.',
        ]);
        
    }

    // DELETE /api/admin/rooms/{id}
    public function destroy(int $id)
    {
        $room = Room::findOrFail($id);

        $hasActive = $room->bookings()
            ->whereIn('status', ['pending', 'approved'])
            ->where('booking_date', '>=', today())
            ->exists();

        if ($hasActive) {
            return response()->json([
                'message' => 'Tidak bisa menonaktifkan ruangan yang masih punya peminjaman aktif.',
            ], 422);
        }

        $room->update(['is_active' => false]);
        return response()->json(['message' => 'Ruangan berhasil dinonaktifkan.']);
    }

    // Helper: bangun timeline gabungan jadwal + peminjaman
    private function buildTimeline($schedules, $bookings): array
    {
        $items = [];

        foreach ($schedules as $s) {
            $items[] = [
                'type'       => 'jadwal_kuliah',
                'start_time' => substr($s->start_time, 0, 5),
                'end_time'   => substr($s->end_time, 0, 5),
                'title'      => $s->course_name,
                'subtitle'   => $s->lecturer . ' · ' . $s->prodi,
                'prodi'      => $s->prodi,
                'kelas'      => $s->kelas,
                'status_label' => 'Berlangsung',
            ];
        }

        foreach ($bookings as $b) {
            $items[] = [
                'type'         => 'peminjaman',
                'start_time'   => substr($b->start_time, 0, 5),
                'end_time'     => substr($b->end_time, 0, 5),
                'title'        => $b->purpose,
                'subtitle'     => $b->user->name . ($b->organization ? ' · ' . $b->organization : ''),
                'prodi'        => null,
                'kelas'        => null,
                'status_label' => $b->status === 'pending' ? 'Menunggu Konfirmasi' : 'Disetujui',
            ];
        }

        usort($items, fn($a, $b) => strcmp($a['start_time'], $b['start_time']));

        return $items;
    }
}