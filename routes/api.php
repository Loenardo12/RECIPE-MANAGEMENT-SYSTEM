<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route publik
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route yang dilindungi (membutuhkan token Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return response()->json(['success' => true, 'message' => 'Data user berhasil diambil.', 'data' => $request->user()], 200);
    });

    // Recipe CRUD
    Route::apiResource('recipes', RecipeController::class);
});
