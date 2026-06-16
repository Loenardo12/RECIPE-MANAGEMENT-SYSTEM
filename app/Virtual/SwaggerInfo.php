<?php

namespace App\Virtual;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Sistem Manajemen Resep Masakan API',
    version: '1.0.0',
    description: 'REST API untuk mengelola resep masakan menggunakan Laravel 11 dan Laravel Sanctum.'
)]
#[OA\Server(
    url: 'http://localhost:8000/api',
    description: 'Local Development Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'BearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Format: Bearer {token}'
)]
class SwaggerInfo {}