<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Sistem Manajemen Resep Masakan API",
 *     version="1.0.0",
 *     description="REST API untuk mengelola resep masakan. Dibangun dengan Laravel 11 dan diamankan menggunakan Laravel Sanctum (Bearer Token).\n\n## Cara Penggunaan\n1. Register akun baru via `/api/register` ATAU Login via `/api/login`\n2. Copy nilai `token` dari response\n3. Klik tombol **Authorize**, masukkan: `Bearer {token}`\n4. Semua endpoint terkunci kini bisa diakses",
 *     @OA\Contact(name="Kelompok Web Service")
 * )
 *
 * @OA\Server(url="http://localhost:8000/api", description="Local Development Server")
 *
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Masukkan token dari response Login/Register. Format: Bearer {token}"
 * )
 *
 * @OA\Tag(name="Authentication", description="Register, Login, dan Logout")
 * @OA\Tag(name="Recipes", description="CRUD Resep Masakan")
 *
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Chef Budi"),
 *     @OA\Property(property="email", type="string", format="email", example="chefbudi@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Recipe",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Nasi Goreng Spesial"),
 *     @OA\Property(property="description", type="string", example="Nasi goreng dengan bumbu rahasia."),
 *     @OA\Property(property="ingredients", type="string", example="2 piring nasi, 2 telur, kecap manis"),
 *     @OA\Property(property="cooking_steps", type="string", example="1. Tumis bumbu. 2. Masukkan nasi."),
 *     @OA\Property(property="cooking_time", type="integer", example=15, description="Dalam menit"),
 *     @OA\Property(property="category", type="string", example="Makanan Utama"),
 *     @OA\Property(property="image_url", type="string", nullable=true, example="http://localhost:8000/storage/recipes/nasi.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
abstract class Controller
{
    //
}
