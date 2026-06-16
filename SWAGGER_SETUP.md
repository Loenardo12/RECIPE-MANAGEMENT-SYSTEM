# 📘 Cara Setup Swagger (L5-Swagger)

## 1. Install Library
```bash
composer require darkaonline/l5-swagger
```

## 2. Publish Config
```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

## 3. Tambahkan di .env
```
L5_SWAGGER_GENERATE_ALWAYS=true
```

## 4. Generate Dokumentasi
```bash
php artisan l5-swagger:generate
```

## 5. Akses Swagger UI
Buka di browser:
```
http://localhost:8000/api/documentation
```

## Cara Pakai Swagger UI
1. Klik endpoint `/api/register` atau `/api/login`
2. Klik **Try it out** → isi data → **Execute**
3. Copy nilai `token` dari response
4. Klik tombol **Authorize** (kunci 🔒) di atas
5. Masukkan: `Bearer <token_kamu>`
6. Klik **Authorize** → **Close**
7. Sekarang semua endpoint bisa diakses langsung dari browser!
