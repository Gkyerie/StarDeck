<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isMember();
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'regex:/^\d{9,15}$/'],
            'rank_or_position' => ['required', 'string', 'max:100'],
            'station_module' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Nomor telepon harus berupa 9-15 digit angka.',
            'rank_or_position.required' => 'Pangkat/jabatan wajib diisi.',
            'rank_or_position.max' => 'Pangkat/jabatan maksimal 100 karakter.',
            'station_module.required' => 'Modul stasiun wajib diisi.',
            'station_module.max' => 'Modul stasiun maksimal 100 karakter.',
            'address.max' => 'Alamat maksimal 255 karakter.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}
