<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FloorRequest;
use App\Models\ActivityLog;
use App\Models\Building;
use App\Models\Floor;

class FloorController extends Controller
{
    public function index()
    {
        $floors = Floor::with('building')
                       ->withCount('rooms', 'cameras')
                       ->when(request('building_id'), fn($q, $v) => $q->where('building_id', $v))
                       ->orderBy('building_id')
                       ->orderBy('level')
                       ->paginate(15);

        $buildings = Building::orderBy('name')->get();

        return view('admin.floors.index', compact('floors', 'buildings'));
    }

    public function create()
    {
        $buildings = Building::orderBy('name')->get();
        return view('admin.floors.create', compact('buildings'));
    }

    public function store(FloorRequest $request)
    {
        $floor = Floor::create($request->validated());
        $floor->building->updateTotalFloors();

        ActivityLog::record('create', $floor, "Menambah lantai: {$floor->name}");

        return redirect()->route('admin.floors.index')
                         ->with('success', 'Lantai berhasil ditambahkan.');
    }

    public function edit(Floor $floor)
    {
        $buildings = Building::orderBy('name')->get();
        return view('admin.floors.edit', compact('floor', 'buildings'));
    }

    public function update(FloorRequest $request, Floor $floor)
    {
        $oldBuilding = $floor->building_id;
        $floor->update($request->validated());

        if ($oldBuilding != $floor->building_id) {
            Building::find($oldBuilding)?->updateTotalFloors();
        }
        $floor->building->updateTotalFloors();

        ActivityLog::record('update', $floor, "Update lantai: {$floor->name}");

        return redirect()->route('admin.floors.index')
                         ->with('success', 'Lantai berhasil diperbarui.');
    }

    public function destroy(Floor $floor)
    {
        $building = $floor->building;
        $name = $floor->name;
        $floor->delete();
        $building->updateTotalFloors();

        ActivityLog::record('delete', null, "Hapus lantai: {$name}");

        return redirect()->route('admin.floors.index')
                         ->with('success', 'Lantai berhasil dihapus.');
    }
}