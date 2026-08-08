# AksiAlam

Aplikasi berbasis **Laravel 13** (PHP) dengan **Blade** sebagai templating dan **Vite + Tailwind CSS v4** untuk sisi frontend. Menggunakan **Laravel Sanctum** dan **JWT Auth** untuk autentikasi.

## 🧰 Tech Stack

- PHP ^8.3
- Laravel Framework ^13.7
- Laravel Sanctum ^4.0
- JWT Auth (tymon/jwt-auth) ^2.3
- Vite ^8 + Tailwind CSS ^4
- Blade

## ✅ Prasyarat

Pastikan sudah terinstal di komputer kamu:

- [PHP](https://www.php.net/) versi 8.3 atau lebih baru
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) (versi LTS terbaru) beserta npm
- Database: SQLite (bawaan/paling mudah) atau MySQL/PostgreSQL jika ingin pakai itu
- Git

## 🚀 Instalasi & Setup Lokal

1. **Clone repository**
```bash
   git clone https://github.com/Gathhaaan/aksialam.git
   cd aksialam
```

2. **Install dependency PHP**
```bash
   composer install
```

3. **Salin file environment**
```bash
   cp .env.example .env
```
   *(Windows PowerShell: `copy .env.example .env`)*

4. **Generate application key**
```bash
   php artisan key:generate
```

5. **Konfigurasi database**

   Secara default Laravel bisa langsung jalan dengan SQLite. Buat file database-nya:
```bash
   touch database/database.sqlite
```
   Lalu pastikan di `.env` sudah diatur:
```env
   DB_CONNECTION=sqlite
```

   Jika ingin memakai MySQL, sesuaikan `.env` seperti berikut, lalu buat database-nya secara manual:
```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=aksialam
   DB_USERNAME=root
   DB_PASSWORD=
```

6. **Jalankan migrasi database**
```bash
   php artisan migrate
```
   Tambahkan `--seed` jika proyek memiliki seeder dan kamu ingin mengisi data awal:
```bash
   php artisan migrate --seed
```

7. **Install dependency frontend**
```bash
   npm install
```

8. **Build asset frontend**
```bash
   npm run build
```

> 💡 **Alternatif cepat:** langkah 2–8 di atas sudah dirangkum dalam satu perintah Composer:
> ```bash
> composer run setup
> ```

## ▶️ Menjalankan Aplikasi (Development)

Jalankan semua service sekaligus (server, queue listener, log viewer, dan Vite dev server) dengan satu perintah:

```bash
composer run dev
```

Atau jalankan secara manual di terminal terpisah:

```bash
php artisan serve       # menjalankan server Laravel
npm run dev              # menjalankan Vite dev server (hot reload)
php artisan queue:listen # (opsional) menjalankan queue worker
```

Setelah berjalan, buka aplikasi di browser melalui: http://127.0.0.1:8000

## 🧪 Menjalankan Test

```bash
composer run test
```
atau
```bash
php artisan test
```

## 📄 Dokumentasi Tambahan

- Dokumentasi API tersedia di [`API_DOCUMENTATION.md`](./API_DOCUMENTATION.md)
- Koleksi Postman tersedia di [`AksiAlam.postman_collection.json`](./AksiAlam.postman_collection.json)
- Spesifikasi kebutuhan software di [`SRS_AksiAlam.md`](./SRS_AksiAlam.md)

## 📁 Struktur Folder Utama

├── app/ # Logic aplikasi (models, controllers, dll)
├── bootstrap/ # File bootstrap framework
├── config/ # File konfigurasi
├── database/ # Migration, factory, seeder
├── public/ # Entry point & asset publik
├── resources/ # View (Blade), CSS, JS
├── routes/ # Definisi route
├── storage/ # Log, cache, file upload
└── tests/ # Unit & feature test

## 🤝 Kontribusi

1. Fork repository ini
2. Buat branch baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m "Menambahkan fitur baru"`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## 📜 Lisensi

Proyek ini menggunakan lisensi [MIT](https://opensource.org/licenses/MIT).
