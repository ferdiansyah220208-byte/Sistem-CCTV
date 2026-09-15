<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FloorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('floor')?->id;

        return [
            'building_id' => 'required|exists:buildings,id',
            'name'        => 'required|string|max:50',
            'level'       => [
                'required',
                'integer',
                Rule::unique('floors')
                    ->where('building_id', $this->building_id)
                    ->ignore($id),
            ],
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }
}