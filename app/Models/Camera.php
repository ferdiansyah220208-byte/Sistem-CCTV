<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id', 'layout_id', 'name', 'code', 'ip_address', 'stream_url',
        'photo', 'brand', 'type', 'resolution', 'status',
        'pos_x', 'pos_y', 'rotation', 'installed_at',
        'last_checked_at', 'notes', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'installed_at'    => 'date',
            'last_checked_at' => 'datetime',
            'is_active'       => 'boolean',
            'rotation'        => 'integer',
        ];
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function layout()
    {
        return $this->belongsTo(Layout::class);
    }

    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('code', 'like', "%{$keyword}%")
              ->orWhere('ip_address', 'like', "%{$keyword}%");
        });
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
            ->when($filters['type'] ?? null, fn($q, $v) => $q->where('type', $v))
            ->when($filters['room_id'] ?? null, fn($q, $v) => $q->where('room_id', $v));
    }
}