<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id', 'name', 'level', 'description', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function layouts()
    {
        return $this->hasMany(Layout::class);
    }

    public function cameras()
    {
        return $this->hasManyThrough(Camera::class, Room::class);
    }
}