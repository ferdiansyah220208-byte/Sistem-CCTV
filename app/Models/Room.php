<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor_id', 'layout_id', 'name', 'code', 'description',
        'polygon_points', 'center_x', 'center_y', 'color', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'polygon_points' => 'array',
            'is_active'      => 'boolean',
        ];
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function layout()
    {
        return $this->belongsTo(Layout::class);
    }

    public function cameras()
    {
        return $this->hasMany(Camera::class);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('code', 'like', "%{$keyword}%");
        });
    }
}