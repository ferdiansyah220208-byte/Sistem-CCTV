<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'description', 'ip_address', 'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, ?Model $model = null, ?string $desc = null): void
    {
        if (!auth()->check()) {
            return;
        }

        static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model?->id,
            'description' => $desc,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}