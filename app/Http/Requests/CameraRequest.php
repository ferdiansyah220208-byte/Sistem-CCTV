<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CameraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('camera')?->id;

        return [
            'room_id'      => 'required|exists:rooms,id',
            'layout_id'    => 'nullable|exists:layouts,id',
            'name'         => 'required|string|max:100',
            'code'         => 'required|string|max:50|unique:cameras,code,' . $id,
            'ip_address'   => 'nullable|ip',
            'stream_url'   => 'nullable|string|max:255',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'brand'        => 'nullable|string|max:50',
            'type'         => 'required|in:dome,bullet,ptz,fisheye',
            'resolution'   => 'nullable|string|max:20',
            'status'       => 'required|in:online,offline,maintenance',
            'pos_x'        => 'nullable|numeric',
            'pos_y'        => 'nullable|numeric',
            'rotation'     => 'nullable|integer|between:0,360',
            'installed_at' => 'nullable|date',
            'notes'        => 'nullable|string',
            'is_active'    => 'boolean',
        ];
    }
}