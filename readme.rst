# CodeIgniter 3 HMVC REST API (Support PHP 8.2+)

API backend sederhana berbasis **CodeIgniter 3** + **HMVC (Modular Extensions)**.
Cocok untuk project modern, sudah support JWT Auth, struktur modular, dan siap untuk dikembangkan.

---

## 💾 Cara Instalasi

### 1. Clone dan Install Composer

```
git clone https://github.com/silumansupra/codeigniter3-php82.git
cd codeigniter3-php82
composer install
```

### 2. Setup Database

- Buat database baru di MySQL, contoh: `ci3_api_db`
- Edit konfigurasi database di `application/config/database.php`

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'ci3_api_db',
    'dbdriver' => 'mysqli',
    // ...
);
```

### 3. Import Struktur dan Data Demo

- Jalankan file `.sql` yang sudah disediakan untuk membuat tabel dan user demo:

```
mysql -u root -p ci3_api_db < testing_api_user.sql
```

- User demo:
    - Email: **admin@example.com**
    - Password: **123456**

---

## 🚀 Cara Testing API

- Jalankan project di local server (bisa pakai `php -S localhost:8000` atau XAMPP/Laragon)
- Cek endpoint info:

```
http://localhost:8000/api/info
```

- Tes login via Postman/Insomnia/cURL:

```
POST http://localhost:8000/api/v1/auth/login
Body (JSON):
{
  "email": "admin@example.com",
  "password": "123456"
}
```

- Jika login berhasil, dapatkan JWT token.
  Gunakan token untuk akses endpoint lain, dengan menambahkan header:

```
Authorization: Bearer <access_token>
```

---

## 📚 Daftar Endpoint

- `POST /api/v1/auth/login` — Login & dapatkan token JWT
- `GET /api/v1/users` — List semua user (butuh token)
- `POST /api/v1/users` — Tambah user baru (butuh token)
- `GET /api/v1/users/{id}` — Detail user (butuh token)
- `PUT /api/v1/users/{id}` — Update user (butuh token)
- `DELETE /api/v1/users/{id}` — Hapus user (butuh token)
- `GET /api/info` — Info PHP, CodeIgniter, HMVC (no token)

---

## 🧩 Fitur

- Modular/HMVC Controller (struktur rapi, scalable)
- JWT Authentication
- RESTful API endpoint
- Support PHP 8.2+
- Mudah dikembangkan sesuai kebutuhan

---

## 🙏 Credits

- [CodeIgniter 3.x](https://github.com/bcit-ci/CodeIgniter)
- [Modular Extensions - HMVC](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/)
- [Firebase PHP-JWT](https://github.com/firebase/php-jwt)

---

## Lisensi

MIT License
