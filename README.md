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
- Ekstrak ke folder `htdocs (XAMPP)`

---

### 2. Buat dan Import Database

1. Buka **phpMyAdmin**
2. Buat database, misalnya: `web_magang`
3. Import file `schema.sql` dari folder `sql/` untuk struktur tabel databasenya
4. Import file `sample-data.sql` dari folder `sql/` untuk data dummy-nya

---

### 3. Konfigurasi Database

Ubah nama file `env.php.example` pada folder `config` menjadi `env.php` dan sesuaikan dengan konfigurasi database lokal kamu:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', null);
define('DB_NAME', ''); // isi dengan nama database

define('BASE_URL', 'http://localhost/nama_projek_kalian/'); //rubah sesuai dengan localhost projek kalian atau vhost kalian
```

---

### 4. Jalankan Aplikasi

Buka browser dan akses:

```
http://localhost/nama-folder-kamu/
```

> ✅  Tidak perlu ubah path apapun! Semua URL akan otomatis menyesuaikan folder kamu. Karena Mengambil document rootnya

---

## 📌 Catatan Tambahan
- Pastikan folder src/, assets/, atau css/ diletakkan dengan benar agar CSS ter-load.