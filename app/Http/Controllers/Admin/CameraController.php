<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CameraRequest;
use App\Models\ActivityLog;
use App\Models\Camera;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CameraController extends Controller
{
    public function index(Request $request)
    {
        $cameras = Camera::with(['room.floor.building'])
                         ->search($request->search)
                         ->filter($request->only(['status', 'type', 'room_id']))
                         ->latest()
                         ->paginate(15);

        $rooms = Room::with('floor.building')->get();

        return view('admin.cameras.index', compact('cameras', 'rooms'));
    }

    public function create()
    {
        $rooms = Room::with('floor.building')->get();
        return view('admin.cameras.create', compact('rooms'));
    }

    public function store(CameraRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('cameras', 'public');
        }

        $camera = Camera::create($data);
        ActivityLog::record('create', $camera, "Menambah kamera: {$camera->name}");

        return redirect()->route('admin.cameras.index')
                         ->with('success', 'Kamera berhasil ditambahkan.');
    }

    public function show(Camera $camera)
    {
        $camera->load(['room.floor.building', 'layout']);
        return view('admin.cameras.show', compact('camera'));
    }

    public function edit(Camera $camera)
    {
        $rooms = Room::with('floor.building')->get();
        return view('admin.cameras.edit', compact('camera', 'rooms'));
    }

    public function update(CameraRequest $request, Camera $camera)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($camera->photo) {
                Storage::disk('public')->delete($camera->photo);
            }
            $data['photo'] = $request->file('photo')->store('cameras', 'public');
        }

        $camera->update($data);
        ActivityLog::record('update', $camera, "Update kamera: {$camera->name}");

        return redirect()->route('admin.cameras.index')
                         ->with('success', 'Kamera berhasil diperbarui.');
    }

    public function destroy(Camera $camera)
    {
        if ($camera->photo) {
            Storage::disk('public')->delete($camera->photo);
        }
        $name = $camera->name;
        $camera->delete();
        ActivityLog::record('delete', null, "Hapus kamera: {$name}");

        return redirect()->route('admin.cameras.index')
                         ->with('success', 'Kamera berhasil dihapus.');
    }

    // Update status online/offline via AJAX
    public function updateStatus(Request $request, Camera $camera)
    {
        $data = $request->validate([
            'status' => 'required|in:online,offline,maintenance',
        ]);

        $camera->update([
            'status'          => $data['status'],
            'last_checked_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status kamera diperbarui.',
            'data'    => $camera,
        ]);
    }
}
