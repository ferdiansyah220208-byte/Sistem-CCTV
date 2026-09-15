<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'address', 'description',
        'total_floors', 'image', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function floors()
    {
        return $this->hasMany(Floor::class)->orderBy('level');
    }

    public function cameras()
    {
        return $this->hasManyThrough(Camera::class, Floor::class);
    }

    public function updateTotalFloors(): void
    {
        $this->update(['total_floors' => $this->floors()->count()]);
    }
}