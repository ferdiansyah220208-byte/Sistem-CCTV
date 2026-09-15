<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\ActivityLog;
use App\Models\Floor;
use App\Models\Layout;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['floor.building', 'layout'])
                     ->withCount('cameras')
                     ->search(request('search'))
                     ->when(request('floor_id'), fn($q, $v) => $q->where('floor_id', $v))
                     ->latest()
                     ->paginate(15);

        $floors = Floor::with('building')->orderBy('building_id')->get();

        return view('admin.rooms.index', compact('rooms', 'floors'));
    }

    public function create()
    {
        $floors  = Floor::with('building')->get();
        $layouts = Layout::with('floor')->get();
        return view('admin.rooms.create', compact('floors', 'layouts'));
    }

    public function store(RoomRequest $request)
    {
        $room = Room::create($request->validated());
        ActivityLog::record('create', $room, "Menambah ruangan: {$room->name}");

        return redirect()->route('admin.rooms.index')
                         ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Room $room)
    {
        $room->load(['floor.building', 'layout', 'cameras']);
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $floors  = Floor::with('building')->get();
        $layouts = Layout::with('floor')->get();
        return view('admin.rooms.edit', compact('room', 'floors', 'layouts'));
    }

    public function update(RoomRequest $request, Room $room)
    {
        $room->update($request->validated());
        ActivityLog::record('update', $room, "Update ruangan: {$room->name}");

        return redirect()->route('admin.rooms.index')
                         ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        $name = $room->name;
        $room->delete();
        ActivityLog::record('delete', null, "Hapus ruangan: {$name}");

        return redirect()->route('admin.rooms.index')
                         ->with('success', 'Ruangan berhasil dihapus.');
    }

    public function updatePolygon(Room $room, Request $request)
    {
        $data = $request->validate([
            'polygon_points'     => 'required|array|min:3',
            'polygon_points.*.x' => 'required|numeric',
            'polygon_points.*.y' => 'required|numeric',
            'center_x'           => 'nullable|numeric',
            'center_y'           => 'nullable|numeric',
        ]);

        $room->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Polygon ruangan berhasil disimpan.',
            'data'    => $room,
        ]);
    }
}