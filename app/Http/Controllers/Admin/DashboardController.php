<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Building;
use App\Models\Camera;
use App\Models\Floor;
use App\Models\Room;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'buildings'   => Building::count(),
            'floors'      => Floor::count(),
            'rooms'       => Room::count(),
            'cameras'     => Camera::count(),
            'online'      => Camera::where('status', 'online')->count(),
            'offline'     => Camera::where('status', 'offline')->count(),
            'maintenance' => Camera::where('status', 'maintenance')->count(),
        ];

        $recentLogs = ActivityLog::with('user')->latest()->take(10)->get();

        $camerasByStatus = Camera::selectRaw('status, COUNT(*) as total')
                                 ->groupBy('status')
                                 ->pluck('total', 'status');

        return view('admin.dashboard', compact('stats', 'recentLogs', 'camerasByStatus'));
    }
}