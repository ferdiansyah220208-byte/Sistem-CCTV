<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Floor;
use App\Models\Layout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayoutController extends Controller
{
    public function index()
    {
        $layouts = Layout::with('floor.building')
                         ->withCount('rooms')
                         ->latest()
                         ->paginate(12);

        return view('admin.layouts.index', compact('layouts'));
    }

    public function create()
    {
        $floors = Floor::with('building')->orderBy('building_id')->get();
        return view('admin.layouts.create', compact('floors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'name'     => 'required|string|max:100',
            'image'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'width'    => 'nullable|integer',
            'height'   => 'nullable|integer',
        ]);

        $data['image_path'] = $request->file('image')->store('layouts', 'public');
        unset($data['image']);

        $layout = Layout::create($data);
        ActivityLog::record('create', $layout, "Upload denah: {$layout->name}");

        return redirect()->route('admin.layouts.index')
                         ->with('success', 'Denah berhasil diupload.');
    }

    public function show(Layout $layout)
    {
        $layout->load(['floor.building', 'rooms.cameras']);
        return view('admin.layouts.show', compact('layout'));
    }

    public function edit(Layout $layout)
    {
        $floors = Floor::with('building')->orderBy('building_id')->get();
        return view('admin.layouts.edit', compact('layout', 'floors'));
    }

    public function update(Request $request, Layout $layout)
    {
        $data = $request->validate([
            'floor_id' => 'required|exists:floors,id',
            'name'     => 'required|string|max:100',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'width'    => 'nullable|integer',
            'height'   => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($layout->image_path);
            $data['image_path'] = $request->file('image')->store('layouts', 'public');
        }
        unset($data['image']);

        $layout->update($data);
        ActivityLog::record('update', $layout, "Update denah: {$layout->name}");

        return redirect()->route('admin.layouts.index')
                         ->with('success', 'Denah berhasil diperbarui.');
    }

    public function destroy(Layout $layout)
    {
        Storage::disk('public')->delete($layout->image_path);
        $name = $layout->name;
        $layout->delete();
        ActivityLog::record('delete', null, "Hapus denah: {$name}");

        return redirect()->route('admin.layouts.index')
                         ->with('success', 'Denah berhasil dihapus.');
    }
}