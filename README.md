# Sistem Peminjaman Buku Perpustakaan Gamifikasi SMPN 1 Prembun

Sistem informasi perpustakaan berbasis web ini dirancang untuk Sekolah Menengah Pertama (SMP) dengan mengintegrasikan elemen **gamifikasi** guna meningkatkan minat baca siswa. Dibangun menggunakan **Laravel 12** dan **Livewire 3**, aplikasi ini menawarkan pengalaman pengguna yang interaktif dan responsif (SPA-like experience).

---

## 🚀 Fitur Utama

### 1. Gamifikasi (Siswa)
Fitur ini bertujuan memotivasi siswa untuk lebih sering meminjam dan membaca buku.
* **Sistem Level & Poin:** Siswa mendapatkan XP/Poin setiap kali meminjam atau mengembalikan buku tepat waktu. Didukung oleh *library* `cjmellor/level-up`.
* **Leaderboard (Papan Peringkat):** Menampilkan peringkat siswa berdasarkan total poin yang dikumpulkan untuk memacu kompetisi sehat.
* **Misi (Missions):** Tantangan tertentu yang dapat diselesaikan siswa untuk mendapatkan poin bonus.

### 2. Sirkulasi & Transaksi
* **Peminjaman & Pengembalian:** Pencatatan transaksi peminjaman buku dengan validasi stok.
* **Denda Otomatis:** Sistem secara otomatis menghitung denda (Fine) jika buku dikembalikan melewati tenggat waktu.
* **Riwayat (History):** Siswa dapat melihat rekam jejak buku yang pernah dipinjam.

### 3. Manajemen Admin
* **Dashboard Admin:** Ringkasan statistik perpustakaan.
* **Manajemen Buku:** Tambah, edit, hapus data buku beserta stoknya.
* **Manajemen Anggota:** Kelola data siswa/anggota perpustakaan.
* **Approval:** Fitur persetujuan untuk transaksi sirkulasi tertentu.

---

## 🛠️ Teknologi

Proyek ini menggunakan tumpukan teknologi (tech stack) modern:

* **Backend:** PHP 8.2+, Laravel 12
* **Frontend:** Livewire 3.7 (Full-stack components), Tailwind CSS 4.0, Vite
* **Database:** SQLite (Default, mudah diganti ke MySQL/PostgreSQL)
* **Paket Tambahan:** `cjmellor/level-up` (Gamification system)

---

## ⚙️ Prasyarat Instalasi

Sebelum memulai, pastikan komputer Anda telah terinstal:
* PHP >= 8.2
* Composer
* Node.js & NPM

---

## 📦 Instalasi & Penggunaan

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal (Localhost):

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/username/sistem-perpustakaan-gamifikasi.git](https://github.com/username/sistem-perpustakaan-gamifikasi.git)
    cd sistem-perpustakaan-gamifikasi
    ```

2.  **Setup Otomatis**
    Jalankan perintah berikut untuk menginstal dependensi (PHP & Node), menyalin environment file, membuat key, dan migrasi database:
    ```bash
    composer setup
    ```

3.  **Jalankan Server Development**
    Gunakan perintah berikut untuk menjalankan Laravel server dan Vite secara bersamaan:
    ```bash
    composer dev
    ```
    Aplikasi dapat diakses di: `http://localhost:8000`

---

## 📂 Struktur Folder Penting

* `app/Livewire/` : Logika utama aplikasi (Controller/Component).
    * `AdminDashboard.php` : Halaman utama admin.
    * `CirculationForm.php` : Logika peminjaman/pengembalian.
    * `StudentPortal.php` & `StudentLeaderboard.php` : Fitur sisi siswa.
* `app/Models/` : Representasi data database.
    * `Book.php`, `Member.php`, `Transaction.php`, `Mission.php`.
* `database/migrations/` : Skema struktur database.
* `resources/views/livewire/` : Tampilan antarmuka (Frontend Blade).

---

## 📖 Contoh Penggunaan (User Guide)

Berikut adalah alur penggunaan dasar untuk peran Admin dan Siswa:

### 👨‍💼 Skenario 1: Admin (Pustakawan)

1.  **Login Admin:**
    Buka `http://localhost:8000/login` dan masuk dengan akun admin (sesuai *UserSeeder*).
2.  **Menambah Buku Baru:**
    * Navigasi ke menu **Buku** atau `BookForm`.
    * Klik "Tambah Buku", isi Judul, Penulis, Penerbit, dan Stok.
    * Simpan.
3.  **Melakukan Sirkulasi (Peminjaman):**
    * Masuk ke menu **Sirkulasi**.
    * Pilih nama Siswa dan Judul Buku yang akan dipinjam.
    * Klik **Submit** untuk mencatat transaksi `borrow`.
4.  **Proses Pengembalian:**
    * Saat siswa mengembalikan buku, cari transaksi aktif mereka di menu Sirkulasi.
    * Klik tombol **Kembalikan** (Return).
    * Jika terlambat, sistem akan menampilkan nominal **Denda** yang harus dibayar.

### 👨‍🎓 Skenario 2: Siswa (Member)

1.  **Cek Profil & Level:**
    * Login sebagai siswa.
    * Di **Dashboard Siswa**, lihat *Progress Bar* level saat ini dan total XP yang dimiliki.
2.  **Melihat Peringkat (Leaderboard):**
    * Buka menu **Leaderboard**.
    * Cek posisi Anda dibandingkan teman sekelas berdasarkan keaktifan membaca.
3.  **Menyelesaikan Misi:**
    * Cek notifikasi atau menu **Misi**.
    * Contoh Misi: *"Pinjam 5 Buku bulan ini"*.
    * Lakukan peminjaman buku untuk memenuhi target dan klaim poin tambahan.
4.  **Riwayat Bacaan:**
    * Akses menu **History** untuk melihat daftar buku yang pernah dibaca sebagai referensi pribadi.
