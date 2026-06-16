<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use App\Services\RecipeService;
use Illuminate\Http\JsonResponse;

class RecipeController extends Controller
{
    protected RecipeService $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    /**
     * @OA\Get(
     *     path="/recipes",
     *     tags={"Recipes"},
     *     summary="Get All Recipes",
     *     description="Mengambil daftar semua resep masakan, diurutkan dari yang terbaru.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar resep berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data resep berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Recipe"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        $recipes = $this->recipeService->getAll();
        return response()->json([
            'success' => true,
            'message' => 'Data resep berhasil diambil.',
            'data' => $recipes
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/recipes/{id}",
     *     tags={"Recipes"},
     *     summary="Get Detail Recipe",
     *     description="Mengambil detail satu resep berdasarkan ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID resep", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(
     *         response=200,
     *         description="Detail resep berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail resep berhasil diambil."),
     *             @OA\Property(property="data", ref="#/components/schemas/Recipe")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Resep tidak ditemukan")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $recipe = $this->recipeService->findById($id);
        return response()->json([
            'success' => true,
            'message' => 'Detail resep berhasil diambil.',
            'data' => $recipe
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/recipes",
     *     tags={"Recipes"},
     *     summary="Create Recipe",
     *     description="Membuat resep baru. Gunakan `multipart/form-data` karena mendukung upload gambar. Field `image` opsional.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title","description","ingredients","cooking_steps","cooking_time","category"},
     *                 @OA\Property(property="title", type="string", maxLength=255, example="Rendang Daging Sapi"),
     *                 @OA\Property(property="description", type="string", example="Rendang khas Minang yang gurih dan kaya rempah."),
     *                 @OA\Property(property="ingredients", type="string", example="500gr daging sapi, 200ml santan, bumbu rendang"),
     *                 @OA\Property(property="cooking_steps", type="string", example="1. Haluskan bumbu. 2. Tumis. 3. Masukkan daging. 4. Masak hingga kering."),
     *                 @OA\Property(property="cooking_time", type="integer", minimum=1, example=180),
     *                 @OA\Property(property="category", type="string", maxLength=100, example="Makanan Utama"),
     *                 @OA\Property(property="image", type="string", format="binary", description="File gambar jpeg/png/jpg/gif maks 2MB (opsional)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Resep berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Resep berhasil dibuat."),
     *             @OA\Property(property="data", ref="#/components/schemas/Recipe")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreRecipeRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        \Log::info('DEBUG STORE:', [
            'has_file' => $request->hasFile('image'),
            'files' => $request->allFiles(),
            'all' => $request->all(),
        ]);

        // Pastikan file image ikut diproses
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image');
        }

        $recipe = $this->recipeService->create($validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Resep berhasil dibuat.',
            'data' => $recipe
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/recipes/{id}",
     *     tags={"Recipes"},
     *     summary="Update Recipe",
     *     description="Memperbarui data resep. Semua field opsional (only send what needs to change). Jika `image` diisi, gambar lama otomatis terhapus.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID resep", @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", maxLength=255, example="Nasi Goreng Spesial Pedas"),
     *                 @OA\Property(property="description", type="string", example="Versi pedas dari nasi goreng spesial."),
     *                 @OA\Property(property="ingredients", type="string", example="2 piring nasi, 2 telur, kecap, 5 cabai rawit"),
     *                 @OA\Property(property="cooking_steps", type="string", example="1. Tumis bumbu + cabai. 2. Masukkan nasi."),
     *                 @OA\Property(property="cooking_time", type="integer", minimum=1, example=20),
     *                 @OA\Property(property="category", type="string", maxLength=100, example="Makanan Utama"),
     *                 @OA\Property(property="image", type="string", format="binary", description="Gambar baru - gambar lama otomatis terhapus (opsional)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resep berhasil diupdate",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Resep berhasil diperbarui."),
     *             @OA\Property(property="data", ref="#/components/schemas/Recipe")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Resep tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateRecipeRequest $request, int $id): JsonResponse
    {
        $recipe = $this->recipeService->findById($id);
        $validatedData = $request->validated();

        // Pastikan file image ikut diproses
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image');
        }

        $updatedRecipe = $this->recipeService->update($recipe, $validatedData);
        return response()->json([
            'success' => true,
            'message' => 'Resep berhasil diperbarui.',
            'data' => $updatedRecipe
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/recipes/{id}",
     *     tags={"Recipes"},
     *     summary="Delete Recipe",
     *     description="Menghapus resep beserta file gambarnya dari server secara permanen.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID resep", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(
     *         response=200,
     *         description="Resep berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Resep berhasil dihapus."),
     *             @OA\Property(property="data", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Resep tidak ditemukan")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $recipe = $this->recipeService->findById($id);
        $this->recipeService->delete($recipe);
        return response()->json([
            'success' => true,
            'message' => 'Resep berhasil dihapus.',
            'data' => null
        ], 200);
    }
}