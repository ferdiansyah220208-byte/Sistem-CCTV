<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use Illuminate\Http\Request;

class CameraApiController extends Controller
{
    public function index(Request $request)
    {
        $cameras = Camera::with(['room.floor.building'])
                         ->search($request->search)
                         ->filter($request->only(['status', 'type', 'room_id']))
                         ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $cameras,
        ]);
    }

    public function show(Camera $camera)
    {
        $camera->load(['room.floor.building', 'layout']);

        return response()->json([
            'success' => true,
            'data'    => $camera,
        ]);
    }

    public function search(string $keyword)
    {
        $cameras = Camera::with(['room.floor.building'])
                         ->search($keyword)
                         ->take(20)
                         ->get();

        return response()->json([
            'success' => true,
            'data'    => $cameras,
        ]);
    }
}