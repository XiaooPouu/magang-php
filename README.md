# 🧾 Magang App

Aplikasi sederhana berbasis PHP dengan MVC, menggunakan AdminLTE 4 dan Bootstrap 5. dengan Module: **Items**, **Customers**, dan **Suppliers**.

---

## Fitur

- CRUD untuk Items, Costumers, dan Suppliers
- Tampilan menggunakan AdminLte 4
- Struktur folder rapi
- Validasi duplikat pada `ref_no` saat insert data

---

## 🛠️ Cara Install & Menjalankan

### 1. Clone Repository
``` bash
git clone https://github.com/XiaooPouu/magang-php.git
```

### 2. Pindahkan Folder ke htdocs(XAMPP):
``` bash
cd /c/xampp/htdocs/
```

### 3. Import file SQL 
``` buka phpMyAdmin dan pilih menu Import
- Import file dari folder sql yang telah ada
```

### 4. Konfigurasi koneksi Database
Buka file `config/database.php`, lalu sesuaikan konfigurasi database

### 5. Buka di browser:
`http://localhost/magang/index.php`