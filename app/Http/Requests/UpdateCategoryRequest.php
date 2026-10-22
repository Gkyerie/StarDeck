<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $categoryId = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 50 karakter.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ];
    }
}
