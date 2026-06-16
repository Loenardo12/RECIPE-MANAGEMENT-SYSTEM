<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RecipeService
{
    public function getAll()
    {
        return Recipe::latest()->get();
    }

    public function findById(int $id)
    {
        return Recipe::findOrFail($id);
    }

    public function create(array $data)
    {
        // Cek jika ada file gambar yang diunggah
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return Recipe::create($data);
    }

    public function update(Recipe $recipe, array $data)
    {
        // Jika ada gambar baru, hapus gambar lama dan upload yang baru
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->deleteImage($recipe->image);
            $data['image'] = $this->uploadImage($data['image']);
        }

        $recipe->update($data);
        return $recipe->fresh();
    }

    public function delete(Recipe $recipe)
    {
        // Hapus file gambar dari storage sebelum menghapus record database
        $this->deleteImage($recipe->image);
        return $recipe->delete();
    }

    /**
     * Helper: Upload gambar ke storage
     */
    private function uploadImage(UploadedFile $file): string
    {
        // Generate nama file unik: timestamp_nama_asli
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Simpan ke folder 'public/recipes'
        return $file->storeAs('recipes', $fileName, 'public');
    }

    /**
     * Helper: Hapus gambar dari storage
     */
    private function deleteImage(?string $imagePath): void
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
