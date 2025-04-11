# 🧾 Magang App

Aplikasi sederhana berbasis PHP dengan MVC, menggunakan AdminLTE 4 dan Bootstrap 5. dengan Module: **Items**, **Customers**, dan **Suppliers**.

---

## 📁 Struktur dan Fleksibilitas

🟢 **Tidak mengunci nama folder!**  
Kamu bebas menyimpan folder project ini di mana saja, seperti:

```
C:/xampp/htdocs/magang_php/
C:/xampp/htdocs/projekku/
C:/xampp/htdocs/folder_bebas/
```

URL dan path akan otomatis menyesuaikan.

---

## 🔧 Fitur Aplikasi
- CRUD untuk:
  - 📦 Items (kode, nama, harga)
  - 👤 Customers (kode, nama)
  - 🏭 Suppliers (kode, nama)
- Struktur MVC:
  - `models/` untuk query
  - `views/` untuk tampilan
  - `controllers/` untuk logika
- Tampilan menggunakan AdminLte 4
- Struktur folder yang rapi
- Validasi duplikat pada ref_no saat insert data
- Koneksi database menggunakan class Database

---

## 🚀 Langkah Install

### 1. Clone atau Download Project
``` bash
git clone https://github.com/XiaooPouu/magang-php.git
```

Atau:

- Download ZIP dari GitHub
- Ekstrak ke folder `htdocs (XAMPP) atau www (Laragon)`

---

### 2. Buat dan Import Database

1. Buka **phpMyAdmin**
2. Buat database, misalnya: `web_magang`
3. Import file `magang_php.sql` dari folder `sql/`

---

### 3. Konfigurasi Database

Buka file `config/config.php` dan ubah bagian berikut:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_NAME', 'web_magang'); // Ganti sesuai nama database kamu
```

---

### 4. Jalankan Aplikasi

Buka browser dan akses:

```
http://localhost/nama-folder-kamu/public/
```

> ✅ Tidak perlu ganti folder khusus, URL akan menyesuaikan otomatis berkat konfigurasi `BASE_URL`.

---