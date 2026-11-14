# E-ROOM – Sistem Peminjaman Ruangan
### Fakultas Teknik | Universitas Mulawarman

E-ROOM (Electronic Room Reservation) merupakan aplikasi berbasis web yang dirancang untuk mempermudah proses peminjaman dan pengelolaan ruangan di lingkungan Fakultas Teknik Universitas Mulawarman.

Aplikasi ini menyediakan informasi ketersediaan ruangan, jadwal peminjaman, serta sistem pengelolaan reservasi yang membantu mahasiswa dan administrator dalam mengatur penggunaan fasilitas kampus secara lebih terstruktur.

---

## ✨ Fitur Utama

### 👨‍🎓 Mahasiswa

- **Ketersediaan Ruangan** – Melihat daftar ruangan beserta kapasitas, fasilitas, dan jadwal penggunaannya.
- **Pencarian Ruangan** – Memfilter ruangan berdasarkan jenis, kapasitas, dan ketersediaan.
- **Kalender Peminjaman** – Melihat jadwal peminjaman dalam tampilan kalender.
- **Peminjaman Ruangan** – Mengajukan peminjaman dengan mengisi identitas, tanggal, waktu, jumlah peserta, dan alasan peminjaman.
- **Peminjaman Saya** – Melihat daftar peminjaman pribadi.
- **Pembatalan Peminjaman** – Membatalkan peminjaman yang telah dibuat.
- **Ekspor Kalender** – Mengunduh jadwal peminjaman dalam format `.ics`.

### 🛡️ Administrator

- **Dashboard** – Melihat ringkasan informasi ruangan, mahasiswa, dan peminjaman.
- **Manajemen Ruangan** – Menambahkan, mengubah, mengaktifkan, menonaktifkan, dan menghapus data ruangan.
- **Manajemen Mahasiswa** – Mengelola data dan akun mahasiswa.
- **Manajemen Peminjaman** – Melihat dan mengelola seluruh data peminjaman.
- **Persetujuan Peminjaman** – Menyetujui, menolak, atau membatalkan pengajuan peminjaman.
- **Peminjaman Manual** – Menambahkan peminjaman atas nama mahasiswa melalui panel administrator.
- **Filter Data** – Menyaring peminjaman berdasarkan tanggal, ruangan, status, nama, atau NIM.

---

## 🧰 Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP 8.3 | Bahasa pemrograman backend |
| Laravel 13 | Framework aplikasi web |
| MySQL / SQLite | Database |
| Blade | Template engine |
| Tailwind CSS | Styling antarmuka |
| JavaScript | Interaksi dan pengelolaan tampilan |
| Vite | Frontend build tool |
| HTML5 | Struktur halaman |
| CSS3 | Tampilan antarmuka |

---

## 🗂️ Struktur Database

Aplikasi menggunakan beberapa tabel utama:

| Tabel | Keterangan |
|---|---|
| `users` | Menyimpan data akun mahasiswa dan administrator |
| `rooms` | Menyimpan informasi ruangan, kapasitas, fasilitas, dan status |
| `bookings` | Menyimpan data peminjaman ruangan |

### Relasi Database

- Satu mahasiswa dapat memiliki banyak data peminjaman.
- Satu ruangan dapat memiliki banyak data peminjaman.
- Setiap peminjaman terhubung dengan satu mahasiswa dan satu ruangan.

### Status Peminjaman

- `menunggu` – Menunggu persetujuan administrator.
- `disetujui` – Peminjaman telah disetujui.
- `ditolak` – Peminjaman ditolak.
- `dibatalkan` – Peminjaman dibatalkan.

---

## ⚙️ Instalasi dan Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan E-ROOM di lingkungan lokal.

### 1. Persyaratan Sistem

Pastikan perangkat telah memiliki:

- PHP 8.3 atau lebih baru.
- Composer.
- Node.js dan NPM.
- Git.
- Database SQLite atau MySQL.

### 2. Clone Repository

```bash
git clone https://github.com/nashwanfz/E-ROOM.git
```

Masuk ke direktori proyek:

```bash
cd E-ROOM
```

### 3. Install Dependencies

Install dependency Laravel:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

### 4. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
copy .env.example .env
```

Buat application key:

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Secara default, proyek menggunakan SQLite.

Pastikan file database tersedia di:

```text
database/database.sqlite
```

Jika belum tersedia, buat file tersebut terlebih dahulu.

Pastikan konfigurasi berikut terdapat pada `.env`:

```env
DB_CONNECTION=sqlite
```

Jika ingin menggunakan MySQL, sesuaikan konfigurasi database pada file `.env`.

### 6. Migrasi Database

Jalankan migrasi untuk membuat seluruh tabel:

```bash
php artisan migrate
```

### 7. Mengisi Data Awal

Jalankan seeder administrator untuk membuat akun admin, data ruangan, akun mahasiswa contoh, dan data peminjaman demonstrasi:

```bash
php artisan db:seed --class=AdminSeeder
```

### 8. Build Frontend

```bash
npm run build
```

### 9. Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://127.0.0.1:8000
```

---

## 🔐 Akun Demonstrasi

Akun berikut tersedia melalui `AdminSeeder` untuk keperluan pengujian lokal.

### Administrator

| Informasi | Akun |
|---|---|
| NIM | `1000000001` |
| Password | `admin12345` |

### Mahasiswa

| Informasi | Akun |
|---|---|
| NIM | `2509106001` |
| Password | `password123` |

**Catatan:** Akun di atas merupakan akun demonstrasi. Ganti password sebelum digunakan dalam lingkungan produksi.

---

## 📝 Catatan Pengembangan

E-ROOM dikembangkan sebagai aplikasi pengelolaan peminjaman ruangan untuk lingkungan Fakultas Teknik Universitas Mulawarman.

Pada versi repository ini, antarmuka mahasiswa masih menggunakan penyimpanan data berbasis browser (`localStorage`) untuk demonstrasi fitur peminjaman. Sementara itu, pengelolaan data melalui panel administrator telah menggunakan Laravel dan database.

Pengembangan selanjutnya dapat difokuskan pada integrasi penuh antara sistem peminjaman mahasiswa dan database agar seluruh data dapat dikelola secara terpusat.

---

## 👨‍💻 Developer

**Nashwan Faiz Nandana Astaman**

Program Studi Informatika  
Fakultas Teknik  
Universitas Mulawarman

---

## 📄 License

Proyek ini dikembangkan untuk keperluan pembelajaran dan pengembangan sistem informasi di lingkungan akademik.