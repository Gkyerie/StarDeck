<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $unitId = $this->route('unit');

        return [
            'name' => ['required', 'string', 'max:150'],
            'unit_code' => [
                'required',
                'string',
                'regex:/^SD-(BG|CN)-[0-9]{3}$/',
                Rule::unique('units', 'unit_code')->ignore($unitId),
            ],
            'type' => ['required', Rule::in(['board_game', 'console'])],
            'condition' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama unit wajib diisi.',
            'name.max' => 'Nama unit maksimal 150 karakter.',
            'unit_code.required' => 'Kode unit wajib diisi.',
            'unit_code.regex' => 'Format kode unit harus SD-BG-XXX atau SD-CN-XXX.',
            'unit_code.unique' => 'Kode unit sudah digunakan.',
            'type.required' => 'Tipe unit wajib dipilih.',
            'type.in' => 'Tipe unit harus board_game atau console.',
            'condition.required' => 'Kondisi unit wajib diisi.',
            'condition.max' => 'Kondisi maksimal 100 karakter.',
            'categories.required' => 'Minimal satu kategori harus dipilih.',
            'categories.min' => 'Minimal satu kategori harus dipilih.',
            'categories.*.exists' => 'Kategori yang dipilih tidak valid.',
        ];
    }
}
