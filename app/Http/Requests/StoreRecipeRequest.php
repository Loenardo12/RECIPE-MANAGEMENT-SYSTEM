<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izinkan semua user yang terautentikasi
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ingredients' => 'required|string', // Atau 'required|array' jika menggunakan JSON
            'cooking_steps' => 'required|string',
            'cooking_time' => 'required|integer|min:1',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar (akan kita aktifkan penuh di Tahap Upload)
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul resep wajib diisi.',
            'cooking_time.integer' => 'Waktu masak harus berupa angka.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
        ];
    }
}
