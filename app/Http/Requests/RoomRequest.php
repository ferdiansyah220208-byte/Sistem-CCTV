<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'floor_id'           => 'required|exists:floors,id',
            'layout_id'          => 'nullable|exists:layouts,id',
            'name'               => 'required|string|max:100',
            'code'               => 'nullable|string|max:30',
            'description'        => 'nullable|string',
            'polygon_points'     => 'nullable|array',
            'polygon_points.*.x' => 'numeric',
            'polygon_points.*.y' => 'numeric',
            'center_x'           => 'nullable|numeric',
            'center_y'           => 'nullable|numeric',
            'color'              => 'nullable|string|max:20',
            'is_active'          => 'boolean',
        ];
    }
}