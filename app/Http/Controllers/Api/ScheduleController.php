<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Room;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with('room.building')->where('is_active', true);

        if ($request->filled('room_id'))      $query->where('room_id', $request->room_id);
        if ($request->filled('day_of_week'))  $query->where('day_of_week', $request->day_of_week);
        if ($request->filled('semester'))     $query->where('semester', $request->semester);
        if ($request->filled('tahun_ajaran')) $query->where('tahun_ajaran', $request->tahun_ajaran);
        if ($request->filled('prodi')) {
            $query->where('prodi', 'like', '%' . $request->prodi . '%');
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();
        $schedules->each(fn($s) => $s->append('day_label'));

        return response()->json(['data' => $schedules]);
    }

    // GET /api/schedules/by-room/{roomId} — jadwal per ruangan, dikelompok per hari
    public function byRoom(Request $request, int $roomId)
    {
        $room = Room::findOrFail($roomId);

        $schedules = Schedule::query()
            ->where('room_id', $roomId)
            ->where('is_active', true)
            ->when($request->filled('semester'), fn($q) => $q->where('semester', $request->semester))
            ->when($request->filled('tahun_ajaran'), fn($q) => $q->where('tahun_ajaran', $request->tahun_ajaran))
            ->orderBy('day_of_week')->orderBy('start_time')
            ->get();

        // Kelompokkan per hari
        $grouped = $schedules
            ->groupBy('day_of_week')
            ->map(function ($items, $day) {
                return [
                    'day'       => $day,
                    'day_label' => Schedule::$dayLabels[$day] ?? $day,
                    'schedules' => $items->map->append('day_label')->values(),
                ];
            })->values();

        return response()->json([
            'room'     => $room->load('building'),
            'schedule' => $grouped,
        ]);
    }

    // GET /api/schedules/{id}
    public function show(int $id)
    {
        $schedule = Schedule::with('room.building')->findOrFail($id);
        $schedule->append('day_label');
        return response()->json($schedule);
    }

    // POST /api/admin/schedules — tambah jadwal (admin only)
    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'course_name'    => 'required|string|max:200',
            'lecturer'       => 'required|string|max:200',
            'prodi'          => 'required|string|max:100',
            'kelas'          => 'nullable|string|max:50',
            'sks'            => 'nullable|integer|min:1|max:6',
            'day_of_week'    => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i|after:start_time',
            'semester'       => 'required|in:Ganjil,Genap',
            'tahun_ajaran'   => 'required|string|max:20',
            'jenis_kegiatan' => 'sometimes|in:kuliah,praktikum,ujian,seminar,lainnya',
        ]);

        // Cek konflik jadwal
        $conflict = $this->checkConflict(
            $data['room_id'], $data['day_of_week'],
            $data['start_time'], $data['end_time']
        );

        if ($conflict) {
            return response()->json([
                'message'  => sprintf(
                    'Jadwal bentrok dengan: %s (%s - %s)',
                    $conflict->course_name,
                    substr($conflict->start_time, 0, 5),
                    substr($conflict->end_time, 0, 5)
                ),
                'conflict' => $conflict,
            ], 422);
        }

        $data['jenis_kegiatan'] = $data['jenis_kegiatan'] ?? 'kuliah';
        $schedule = Schedule::create($data);
        $schedule->load('room.building');
        $schedule->append('day_label');

        return response()->json([
            'message'  => 'Jadwal berhasil ditambahkan.',
            'schedule' => $schedule,
        ], 201);
    }

    // PUT /api/admin/schedules/{id}
    public function update(Request $request, int $id)
    {
        $schedule = Schedule::findOrFail($id);

        $data = $request->validate([
            'room_id'        => 'sometimes|exists:rooms,id',
            'course_name'    => 'sometimes|string|max:200',
            'lecturer'       => 'sometimes|string|max:200',
            'prodi'          => 'sometimes|string|max:100',
            'kelas'          => 'nullable|string|max:50',
            'sks'            => 'nullable|integer|min:1|max:6',
            'day_of_week'    => 'sometimes|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time'     => 'sometimes|date_format:H:i',
            'end_time'       => 'sometimes|date_format:H:i',
            'semester'       => 'sometimes|in:Ganjil,Genap',
            'tahun_ajaran'   => 'sometimes|string|max:20',
            'jenis_kegiatan' => 'sometimes|in:kuliah,praktikum,ujian,seminar,lainnya',
            'is_active'      => 'sometimes|boolean',
        ]);

        // Cek konflik jika waktu/ruangan diubah
        if (isset($data['day_of_week']) || isset($data['start_time']) || isset($data['end_time'])) {
            $conflict = $this->checkConflict(
                $data['room_id']     ?? $schedule->room_id,
                $data['day_of_week'] ?? $schedule->day_of_week,
                $data['start_time']  ?? $schedule->start_time,
                $data['end_time']    ?? $schedule->end_time,
                $id
            );

            if ($conflict) {
                return response()->json([
                    'message' => sprintf(
                        'Jadwal bentrok dengan: %s (%s - %s)',
                        $conflict->course_name,
                        substr($conflict->start_time, 0, 5),
                        substr($conflict->end_time, 0, 5)
                    ),
                ], 422);
            }
        }

        $schedule->update($data);
        $schedule->load('room.building');
        $schedule->append('day_label');

        return response()->json([
            'message'  => 'Jadwal berhasil diperbarui.',
            'schedule' => $schedule,
        ]);
    }

    // DELETE /api/admin/schedules/{id}
    public function destroy(int $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['is_active' => false]);
        return response()->json(['message' => 'Jadwal berhasil dihapus.']);
    }

    // Helper: cek konflik antar jadwal kuliah
    private function checkConflict(
        int $roomId, string $day,
        string $start, string $end,
        ?int $excludeId = null
    ): ?Schedule {
        return Schedule::query()
            ->where('room_id', $roomId)
            ->where('day_of_week', $day)
            ->where('is_active', true)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                  ->where('end_time', '>', $start);
            })
            ->first();
    }
}