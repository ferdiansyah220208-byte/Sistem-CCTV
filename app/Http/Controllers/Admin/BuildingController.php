<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuildingRequest;
use App\Models\ActivityLog;
use App\Models\Building;
use Illuminate\Support\Facades\Storage;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::withCount('floors')
                             ->orderBy('name')
                             ->paginate(10);

        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
        return view('admin.buildings.create');
    }

    public function store(BuildingRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('buildings', 'public');
        }

        $building = Building::create($data);
        ActivityLog::record('create', $building, "Menambah gedung: {$building->name}");

        return redirect()->route('admin.buildings.index')
                         ->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function show(Building $building)
    {
        $building->load(['floors.rooms', 'floors.cameras']);
        return view('admin.buildings.show', compact('building'));
    }

    public function edit(Building $building)
    {
        return view('admin.buildings.edit', compact('building'));
    }

    public function update(BuildingRequest $request, Building $building)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($building->image) {
                Storage::disk('public')->delete($building->image);
            }
            $data['image'] = $request->file('image')->store('buildings', 'public');
        }

        $building->update($data);
        ActivityLog::record('update', $building, "Update gedung: {$building->name}");

        return redirect()->route('admin.buildings.index')
                         ->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy(Building $building)
    {
        if ($building->image) {
            Storage::disk('public')->delete($building->image);
        }
        $name = $building->name;
        $building->delete();
        ActivityLog::record('delete', null, "Hapus gedung: {$name}");

        return redirect()->route('admin.buildings.index')
                         ->with('success', 'Gedung berhasil dihapus.');
    }
}