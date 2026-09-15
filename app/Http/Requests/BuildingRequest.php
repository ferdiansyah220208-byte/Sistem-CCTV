<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('building')?->id;

        return [
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|max:20|unique:buildings,code,' . $id,
            'address'     => 'nullable|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ];
    }
}