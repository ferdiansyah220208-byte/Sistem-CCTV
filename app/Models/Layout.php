<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor_id', 'name', 'image_path', 'width', 'height', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}