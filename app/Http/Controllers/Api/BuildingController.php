<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $buildings = Building::query()
            ->where('is_active', true)
            ->withCount('activeRooms')
            ->get();

        return response()->json([
            'data' => $buildings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'campus' => 'required|string|max:255',
            'floor' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $building = Building::create($data);

        return response()->json([
            'message' => 'Gedung berhasil ditambahkan.',
            'data' => $building
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buildings = Building::with('activeRooms')->findOrFail($id);

        return response()->json($buildings);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $building = Building::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'campus' => 'required|string|max:255',
            'floor' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $building->update($data);

        return response()->json([
            'message' => 'Gedung berhasil diperbarui.',
            'data' => $building
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $building = Building::findOrFail($id);

        if ($building->activeRoom()->exist()){
            return response()->json([
                'message' => 'Gedung tidak dapat dihapus karena masih memiliki ruangan aktif.'
            ], 400);
        }

        $building->update(['is_active' => false]);

        return response()->json([
            'message' => 'Gedung berhasil dinonakifkan.'
        ]);
    }
}
