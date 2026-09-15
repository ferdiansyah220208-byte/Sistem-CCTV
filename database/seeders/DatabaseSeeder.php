<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Camera;
use App\Models\Floor;
use App\Models\Layout;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@cctv.test',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // User biasa
        User::create([
            'name'      => 'Security Staff',
            'email'     => 'user@cctv.test',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'is_active' => true,
        ]);

        // Building
        $building = Building::create([
            'name'         => 'Gedung A - Utama',
            'code'         => 'GD-A',
            'address'      => 'Jl. Merdeka No. 1, Jakarta',
            'total_floors' => 3,
        ]);

        // Floors
        $floor1 = Floor::create([
            'building_id' => $building->id,
            'name'        => 'Lantai 1',
            'level'       => 1,
        ]);
        $floor2 = Floor::create([
            'building_id' => $building->id,
            'name'        => 'Lantai 2',
            'level'       => 2,
        ]);
        $floor3 = Floor::create([
            'building_id' => $building->id,
            'name'        => 'Lantai 3',
            'level'       => 3,
        ]);

        // Layout
        $layout = Layout::create([
            'floor_id'   => $floor1->id,
            'name'       => 'Denah Lantai 1',
            'image_path' => 'layouts/sample-lt1.png',
            'width'      => 1920,
            'height'     => 1080,
        ]);

        // Rooms
        $room1 = Room::create([
            'floor_id'       => $floor1->id,
            'layout_id'      => $layout->id,
            'name'           => 'Lobby Utama',
            'code'           => 'R-101',
            'polygon_points' => [
                ['x' => 100, 'y' => 100],
                ['x' => 400, 'y' => 100],
                ['x' => 400, 'y' => 300],
                ['x' => 100, 'y' => 300],
            ],
            'center_x'       => 250,
            'center_y'       => 200,
        ]);

        $room2 = Room::create([
            'floor_id'  => $floor1->id,
            'layout_id' => $layout->id,
            'name'      => 'Ruang Server',
            'code'      => 'R-102',
        ]);

        $room3 = Room::create([
            'floor_id'  => $floor2->id,
            'name'      => 'Ruang Meeting',
            'code'      => 'R-201',
        ]);

        // Cameras
        Camera::create([
            'room_id'    => $room1->id,
            'layout_id'  => $layout->id,
            'name'       => 'CAM-Lobby-01',
            'code'       => 'CAM-001',
            'ip_address' => '192.168.1.10',
            'stream_url' => 'rtsp://192.168.1.10:554/stream',
            'brand'      => 'Hikvision',
            'type'       => 'dome',
            'resolution' => '1080p',
            'status'     => 'online',
            'pos_x'      => 250,
            'pos_y'      => 150,
            'rotation'   => 45,
            'installed_at' => now()->subMonths(6),
        ]);

        Camera::create([
            'room_id'    => $room1->id,
            'layout_id'  => $layout->id,
            'name'       => 'CAM-Lobby-02',
            'code'       => 'CAM-002',
            'ip_address' => '192.168.1.11',
            'brand'      => 'Dahua',
            'type'       => 'bullet',
            'resolution' => '4K',
            'status'     => 'online',
            'pos_x'      => 350,
            'pos_y'      => 250,
        ]);

        Camera::create([
            'room_id'  => $room2->id,
            'name'     => 'CAM-Server-01',
            'code'     => 'CAM-003',
            'brand'    => 'Hikvision',
            'type'     => 'ptz',
            'status'   => 'offline',
        ]);

        Camera::create([
            'room_id' => $room3->id,
            'name'    => 'CAM-Meeting-01',
            'code'    => 'CAM-004',
            'type'    => 'dome',
            'status'  => 'maintenance',
        ]);
    }
}