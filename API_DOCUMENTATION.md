# Dokumentasi API & Pengujian: Sistem Manajemen Resep Makanan

## 1. Pendahuluan

Dokumen ini berisi spesifikasi lengkap dan panduan pengujian untuk API Sistem Manajemen Resep Makanan. API ini dibangun menggunakan **Laravel 11** dan diamankan menggunakan **Laravel Sanctum** (Bearer Token).

### Arsitektur Sistem

* **Framework**: Laravel 11
* **Database**: MySQL
* **Autentikasi**: Laravel Sanctum (Stateless API Token)
* **Arsitektur**: RESTful API dengan pemisahan lapisan (Service Layer, Form Request Validation, Controller).

---

## 2. Informasi Dasar

* **Base URL**: `http://localhost:8000/api`
* **Format Data**: `application/json` (Kecuali endpoint upload file menggunakan `multipart/form-data`)
* **Charset**: `UTF-8`

---

## 3. Standarisasi Response

Semua response API mengikuti format JSON yang konsisten untuk memudahkan parsing di sisi klien (Frontend/Mobile).

### Success Response (HTTP 200 / 201)

```json
{
  "success": true,
  "message": "Pesan deskriptif tentang keberhasilan operasi",
  "data": {}
}
```

*(Catatan: `data` bisa berupa Object atau Array tergantung endpoint)*

### Error Response (HTTP 400 / 401 / 404 / 422 / 500)

```json
{
  "success": false,
  "message": "Pesan deskriptif tentang kesalahan",
  "errors": {
    "field_name": ["Pesan error spesifik untuk field ini"]
  }
}
```

---

## 4. Daftar Endpoint & Spesifikasi

### A. Autentikasi (Authentication)

#### 1. Register User

Mendaftarkan pengguna baru dan menghasilkan token API.

* **Method**: `POST`
* **Endpoint**: `/register`
* **Auth**: Tidak diperlukan
* **Content-Type**: `application/json`

**Request Body:**

```json
{
  "name": "Chef Budi",
  "email": "chefbudi@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response Success (201 Created):**

```json
{
  "success": true,
  "message": "Registrasi berhasil. Silakan simpan token Anda.",
  "data": {
    "user": {
      "name": "Chef Budi",
      "email": "chefbudi@example.com",
      "updated_at": "2023-10-27T10:00:00.000000Z",
      "created_at": "2023-10-27T10:00:00.000000Z",
      "id": 1
    },
    "token": "1|abc123xyz...",
    "token_type": "Bearer"
  }
}
```

#### 2. Login User

Masuk ke sistem dan mendapatkan token API.

* **Method**: `POST`
* **Endpoint**: `/login`
* **Auth**: Tidak diperlukan
* **Content-Type**: `application/json`

**Request Body:**

```json
{
  "email": "chefbudi@example.com",
  "password": "password123"
}
```

#### 3. Logout User

Mencabut token API yang sedang digunakan (Revoke Token).

* **Method**: `POST`
* **Endpoint**: `/logout`
* **Auth**: **Wajib** (Bearer Token)

---

### B. Manajemen Resep (Recipes)

#### 1. Get All Recipes

Mengambil daftar semua resep.

* **Method**: `GET`
* **Endpoint**: `/recipes`
* **Auth**: **Wajib** (Bearer Token)

**Response Success (200 OK):**

```json
{
  "success": true,
  "message": "Data resep berhasil diambil.",
  "data": [
    {
      "id": 1,
      "title": "Nasi Goreng Spesial",
      "description": "Nasi goreng dengan bumbu rahasia.",
      "ingredients": "Nasi putih, telur, kecap manis, bawang",
      "cooking_steps": "1. Tumis bumbu. 2. Masukkan nasi.",
      "cooking_time": 15,
      "category": "Makanan Utama",
      "image_url": "http://localhost:8000/storage/recipes/1698400000_nasi.jpg",
      "created_at": "2023-10-27T10:00:00.000000Z",
      "updated_at": "2023-10-27T10:00:00.000000Z"
    }
  ]
}
```

#### 2. Get Detail Recipe

Mengambil detail satu resep berdasarkan ID.

* **Method**: `GET`
* **Endpoint**: `/recipes/{id}`
* **Auth**: **Wajib** (Bearer Token)

#### 3. Create Recipe

Membuat resep baru.

* **Method**: `POST`
* **Endpoint**: `/recipes`
* **Auth**: **Wajib** (Bearer Token)
* **Content-Type**: `multipart/form-data`

**Request Body (Form-Data):**

| Key           | Type   | Required | Validasi                                   |
| ------------- | ------ | -------- | ------------------------------------------ |
| title         | Text   | Yes      | string, max 255                            |
| description   | Text   | Yes      | string                                     |
| ingredients   | Text   | Yes      | string                                     |
| cooking_steps | Text   | Yes      | string                                     |
| cooking_time  | Number | Yes      | integer, min 1                             |
| category      | Text   | Yes      | string, max 100                            |
| image         | File   | No       | image, mimes: jpeg,png,jpg,gif, max 2048KB |

#### 4. Update Recipe

Memperbarui data resep yang sudah ada.

* **Method**: `PUT` atau `PATCH`
* **Endpoint**: `/recipes/{id}`
* **Auth**: **Wajib** (Bearer Token)
* **Content-Type**: `multipart/form-data`

**Request Body (Form-Data):**

Sama seperti Create Recipe, namun semua field bersifat opsional (`sometimes`). Jika field `image` diisi, gambar lama akan otomatis dihapus dari server dan diganti dengan yang baru.

#### 5. Delete Recipe

Menghapus resep beserta file gambarnya dari server.

* **Method**: `DELETE`
* **Endpoint**: `/recipes/{id}`
* **Auth**: **Wajib** (Bearer Token)

---

## 5. Error Handling Umum

* **401 Unauthorized**: Token tidak disertakan, kadaluarsa, atau tidak valid.
* **404 Not Found**: ID resep yang dicari tidak ditemukan di database.
* **422 Unprocessable Entity**: Validasi gagal (misal: format email salah, file terlalu besar, field wajib kosong). Cek objek `errors` pada response untuk detail field mana yang salah.
* **500 Internal Server Error**: Terjadi kesalahan fatal pada sisi server.

---

## 6. Dokumentasi & Skenario Pengujian (Testing)

### Tujuan Pengujian

Memastikan seluruh endpoint API berfungsi sesuai spesifikasi, validasi berjalan dengan benar, dan manajemen file (upload/delete) tidak meninggalkan file sampah (orphaned files).

### Alat Pengujian

* **Postman** atau **Insomnia**
* Server Laravel lokal (`php artisan serve` di port 8000)

### Skenario & Langkah Pengujian

#### Skenario 1: Alur Autentikasi Penuh

1. **Register**: Kirim POST ke `/api/register` dengan data valid.

   * Hasil: Status 201, mendapatkan `token`.
2. **Login**: Kirim POST ke `/api/login` dengan kredensial yang sama.

   * Hasil: Status 200, mendapatkan `token` baru.
3. **Akses Protected Route**: Kirim GET ke `/api/user` dengan Header `Authorization: Bearer <token>`.

   * Hasil: Status 200, data user muncul.
4. **Logout**: Kirim POST ke `/api/logout` dengan Header `Authorization: Bearer <token>`.

   * Hasil: Status 200, token dicabut.
5. **Akses Protected Route (Setelah Logout)**: Kirim GET ke `/api/user` dengan token yang sama.

   * Hasil: Status 401 Unauthorized (Berhasil ditolak).

#### Skenario 2: CRUD Resep dengan Upload Gambar

1. **Create**: Kirim POST ke `/api/recipes` menggunakan `form-data`. Isi semua field teks dan unggah file gambar `.jpg` (< 2MB).

   * Hasil: Status 201, response berisi `image_url` yang valid.
2. **Read All**: Kirim GET ke `/api/recipes`.

   * Hasil: Status 200, array data berisi resep baru dengan `image_url`.
3. **Read Detail**: Kirim GET ke `/api/recipes/{id}`.

   * Hasil: Status 200, detail objek resep.
4. **Update**: Kirim PUT ke `/api/recipes/{id}` menggunakan `form-data`. Ubah `title` dan unggah gambar `.png` baru.

   * Hasil: Status 200, data diperbarui, `image_url` berubah, file gambar lama terhapus dari folder `storage/app/public/recipes/`.
5. **Delete**: Kirim DELETE ke `/api/recipes/{id}`.

   * Hasil: Status 200, data hilang dari database, file gambar fisik terhapus dari storage.

#### Skenario 3: Validasi Error (Negative Testing)

1. Kirim POST ke `/api/recipes` tanpa field `title`.

   * Hasil: Status 422, response berisi:

```json
{
  "errors": {
    "title": ["Judul resep wajib diisi."]
  }
}
```

2. Kirim POST ke `/api/recipes` dengan file `.pdf` (bukan gambar).

   * Hasil: Status 422, response berisi:

```json
{
  "errors": {
    "image": ["File yang diunggah harus berupa gambar."]
  }
}
```

