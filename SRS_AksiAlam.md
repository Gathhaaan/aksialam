# Software Requirements Specification (SRS)
**Project Name:** AksiAlam  
**Version:** 1.0  
**Date:** 01 Juni 2026  

---

## 1. Pendahuluan (Introduction)

### 1.1 Tujuan Dokumen
Dokumen Software Requirements Specification (SRS) ini bertujuan untuk mendefinisikan secara rinci spesifikasi sistem dari aplikasi web **AksiAlam**. Dokumen ini menjadi acuan utama bagi pengembang (developer), penguji (tester), dan pemangku kepentingan (stakeholder) terkait fitur, antarmuka, fungsionalitas, serta batasan-batasan dari sistem yang dibangun.

### 1.2 Ruang Lingkup Sistem
**AksiAlam** adalah sebuah platform berbasis web yang dirancang untuk mempertemukan masyarakat umum (relawan) dengan komunitas pecinta lingkungan. Sistem ini memungkinkan pengguna untuk:
1. Melaporkan kerusakan lingkungan atau titik tumpukan sampah secara *real-time*.
2. Memfasilitasi komunitas penggerak (organizer) untuk menggalang aksi peduli lingkungan (kampanye) berdasarkan laporan warga.
3. Mendorong partisipasi publik melalui sistem *gamification* (poin pengalaman/EXP, *leaderboard*, dan penukaran hadiah).
4. Menyediakan informasi terkini terkait isu ekologi di Indonesia (terintegrasi dengan NewsAPI).
5. Memberikan transparansi aksi melalui peta sebaran interaktif.

### 1.3 Definisi dan Singkatan
*   **SRS:** Software Requirements Specification.
*   **User / Relawan:** Pengguna umum yang berpartisipasi melaporkan kerusakan atau mengikuti aksi.
*   **Organizer:** Pengguna dengan akses untuk membuat dan mengelola kampanye/aksi lingkungan.
*   **Admin:** Pengelola tingkat atas yang mengatur moderasi sistem dan data pengguna.
*   **EXP:** *Experience Points*, poin yang didapatkan pengguna setiap melakukan tindakan positif.
*   **Kampanye (Campaign):** Acara fisik atau aksi nyata (seperti bersih-bersih pantai, reboisasi) yang dipelopori oleh *Organizer*.
*   **API:** Application Programming Interface.

---

## 2. Deskripsi Umum (Overall Description)

### 2.1 Perspektif Produk
AksiAlam dibangun di atas *framework* **Laravel 11** (PHP) dan menggunakan **MySQL** sebagai basis data. Aplikasi ini berjalan secara mandiri sebagai platform web responsif yang dapat diakses melalui peramban (browser) di desktop maupun perangkat seluler. Sistem ini juga menyediakan **RESTful API** untuk keperluan integrasi masa depan (misalnya dengan aplikasi *mobile*).

### 2.2 Karakteristik Pengguna (User Roles)
Sistem membagi pengguna ke dalam tiga (3) hak akses utama:
1.  **Volunteer (Relawan / `user`)**
    *   Melihat peta sebaran dan berita lingkungan.
    *   Mengirim laporan kerusakan lingkungan.
    *   Mendaftar dan mengikuti kampanye/aksi.
    *   Mengumpulkan EXP dan menukarkan hadiah (*rewards*).
2.  **Organizer (Komunitas / `organizer`)**
    *   Mengelola profil komunitas.
    *   Melihat laporan warga dan memverifikasinya.
    *   Membuat kampanye lingkungan baru.
    *   Melakukan presensi relawan (via validasi manual atau *scan QR Code*).
3.  **Administrator (`admin`)**
    *   Memiliki kontrol penuh atas sistem.
    *   Memoderasi laporan (menyetujui/menolak/menghapus laporan *spam*).
    *   Manajemen akun pengguna (mengubah *role*, menghapus akun bermasalah).
    *   Melihat analitik platform.

### 2.3 Lingkungan Operasi
*   **Sistem Operasi Server:** Linux/Unix (Ubuntu dsb) atau lingkungan lokal (Mac/Windows via XAMPP/Valet/Sail).
*   **Web Server:** Nginx atau Apache.
*   **Database Server:** MySQL 8.0+ atau MariaDB.
*   **Bahasa Pemrograman Utama:** PHP 8.2+, JavaScript (ES6), HTML5, CSS3 (Tailwind CSS).

---

## 3. Kebutuhan Fungsional (Functional Requirements)

### 3.1 Modul Otentikasi & Otorisasi
*   **FR-1.1:** Sistem harus memungkinkan pengunjung untuk mendaftar akun baru sebagai *Volunteer*.
*   **FR-1.2:** Sistem harus memvalidasi format email dan memverifikasi kata sandi (minimal 8 karakter, *confirmed*).
*   **FR-1.3:** Sistem harus dapat melakukan *login* dengan kredensial yang valid.
*   **FR-1.4:** Sistem harus mengarahkan (*redirect*) pengguna secara otomatis ke halaman yang sesuai dengan *role* mereka setelah berhasil login (User ke `/`, Organizer ke `/organizer/dashboard`, Admin ke `/admin/dashboard`).
*   **FR-1.5:** Sistem harus memiliki fitur *logout* yang aman.

### 3.2 Modul Halaman Publik (Beranda Utama)
*   **FR-2.1:** Sistem harus menampilkan **Peta Interaktif** (menggunakan Leaflet.js) yang memuat titik-titik koordinat kampanye dan laporan.
*   **FR-2.2:** Sistem harus menampilkan *carousel* maksimal 8 **Laporan Warga** terbaru.
*   **FR-2.3:** Sistem harus menampilkan daftar **Kampanye Aktif** dengan *progress bar* jumlah relawan.
*   **FR-2.4:** Sistem harus menampilkan daftar **Kampanye Selesai/Ditutup** sebagai riwayat aksi.
*   **FR-2.5:** Sistem harus menarik dan menampilkan **Berita Ekologi Indonesia** secara dinamis menggunakan integrasi *NewsAPI* dengan *cache* selama 6 jam untuk efisiensi limit API.
*   **FR-2.6:** Sistem harus menampilkan **Leaderboard** berisikan 5 Relawan dengan jumlah EXP tertinggi.

### 3.3 Modul Laporan Warga (Reports)
*   **FR-3.1:** *Volunteer* harus bisa mengunggah laporan kerusakan lingkungan yang berisi: Judul, Kategori (Sampah, Fasilitas, Flora/Fauna), Deskripsi, Titik Lokasi (Nama tempat), dan Foto Bukti.
*   **FR-3.2:** *Organizer* dan *Admin* harus bisa melihat daftar semua laporan yang masuk.
*   **FR-3.3:** Laporan harus melalui proses moderasi atau verifikasi (status: *pending, verified, resolved, rejected*).

### 3.4 Modul Kampanye & Aksi (Campaigns)
*   **FR-4.1:** *Organizer* harus bisa membuat kampanye baru dengan mengisi: Nama Kampanye, Deskripsi Singkat, Tanggal Pelaksanaan, Lokasi, Titik Koordinat (Latitude & Longitude), dan Batas Maksimal Relawan.
*   **FR-4.2:** *Volunteer* harus bisa melihat detail kampanye dan menekan tombol "Daftar Sekarang" untuk berpartisipasi.
*   **FR-4.3:** Sistem harus menampilkan QR Code tiket pendaftaran kepada *Volunteer* yang berhasil mendaftar (berisi Payload URL `/campaign/{id}/checkin?user={user_id}`).
*   **FR-4.4:** *Organizer* harus bisa memindai QR Code relawan atau menekan tombol manual untuk mengonfirmasi kehadiran relawan (Check-In).
*   **FR-4.5:** Sistem harus secara otomatis menambahkan sejumlah EXP kepada relawan jika status kehadirannya diubah menjadi "Hadir".

### 3.5 Modul Gamifikasi & Rewards
*   **FR-5.1:** Sistem harus menambahkan +10 EXP setiap kali pengguna membuat laporan kerusakan.
*   **FR-5.2:** Sistem harus menambahkan +50 EXP setiap kali pengguna hadir di sebuah kampanye.
*   **FR-5.3:** Pengguna dengan level EXP tertentu bisa menukarkan (*redeem*) poin tersebut dengan hadiah fisik atau digital di menu Rewards.

### 3.6 Modul REST API (Backend Khusus Mobile/Pihak Ketiga)
*   **FR-6.1:** Sistem harus menyediakan endpoint API menggunakan *Laravel Sanctum* atau JWT (JSON Web Tokens).
*   **FR-6.2:** API harus mencakup fungsi Login/Register, Get Profile, Get Reports, Create Report, Get Campaigns, dan Get Leaderboard.

---

## 4. Kebutuhan Non-Fungsional (Non-Functional Requirements)

### 4.1 Performa & Kecepatan (Performance)
*   **NFR-1.1:** Waktu muat (load time) halaman Beranda publik tidak boleh lebih dari 3 detik pada koneksi standar 4G.
*   **NFR-1.2:** Pengambilan data berita dari pihak ketiga (NewsAPI) harus menggunakan *caching* (Redis/File Cache) untuk mencegah jeda tunggu koneksi jaringan.

### 4.2 Keamanan (Security)
*   **NFR-2.1:** Semua kata sandi pengguna harus dienkripsi menggunakan algoritma `Bcrypt` bawaan Laravel.
*   **NFR-2.2:** Semua form yang memodifikasi data (POST, PUT, DELETE) harus dilindungi oleh token CSRF.
*   **NFR-2.3:** *Endpoint* khusus Organizer dan Admin tidak boleh bisa diakses oleh *Volunteer*. Sistem akan memblokir menggunakan *Middleware Role*.
*   **NFR-2.4:** Fitur "Hadir / Check-In" kampanye hanya bisa dieksekusi jika pengguna yang login (yang memindai QR) memiliki peran sebagai *Organizer*.

### 4.3 Ketersediaan & Keandalan (Reliability)
*   **NFR-3.1:** Aplikasi harus bisa menangani kondisi bila API eksternal (NewsAPI) sedang *down* tanpa menyebabkan aplikasi *error* (*graceful fallback*, misalnya menampilkan pesan "Berita tidak tersedia").

### 4.4 Antarmuka Pengguna & Pengalaman (UX/UI)
*   **NFR-4.1:** Seluruh antarmuka web harus bersifat *Mobile-Responsive*, dapat beradaptasi dengan layar ponsel pintar, tablet, dan desktop.
*   **NFR-4.2:** Menggunakan *styling* modern (Tailwind CSS) dengan estetika UI hijau (mewakili alam), mode terang yang bersih, kartu dengan bayangan lembut, dan animasi mikro transisi.
*   **NFR-4.3:** Peta sebaran interaktif harus memiliki `scrollWheelZoom: false` agar tidak mengganggu proses gulir (scrolling) halaman pengguna.

---

## 5. Diagram & Alur Proses Singkat (Workflow)

### 5.1 Alur Relawan (Volunteer Journey)
`Mendaftar -> Login -> Melihat Peta/Beranda -> Melapor Kerusakan -> Menemukan Kampanye Reboisasi -> Daftar Kampanye -> Hadir di Lokasi -> Scan QR oleh Panitia -> Dapat EXP -> Tukar Hadiah.`

### 5.2 Alur Komunitas (Organizer Journey)
`Login -> Dashboard Komunitas -> Cek Laporan Warga Baru -> Buat Kampanye "Bersih Sungai" di lokasi laporan -> Verifikasi Pendaftar -> Acara Selesai -> Tutup Kampanye.`

---
**-- Akhir dari Dokumen SRS AksiAlam --**
