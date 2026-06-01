# 📘 Dokumentasi API — AksiAlam v1

> Platform Gotong Royong Digital untuk Restorasi Ekologi Indonesia

## Base URL

```
http://localhost:8000/api/v1
```

## Metode Autentikasi

AksiAlam API mendukung **3 metode autentikasi**:

| Metode | Header | Digunakan Untuk |
|--------|--------|-----------------|
| **JWT (Bearer Token)** | `Authorization: Bearer <token>` | Operasi CRUD (buat, edit, hapus laporan/kampanye) |
| **API Key** | `X-API-KEY: <key>` | Endpoint publik (statistik, leaderboard) |
| **Basic Auth** | POST `email` + `password` | Login untuk mendapatkan JWT token |

### Cara Mendapatkan JWT Token

```bash
POST /api/v1/login
Content-Type: application/json

{
  "email": "gathan@user.com",
  "password": "password123"
}
```

Response:
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": { "id": 1, "name": "Gathan", "email": "gathan@user.com", "role": "user" }
}
```

---

## 📋 Daftar Endpoint

### 1. Authentication

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| `POST` | `/register` | — | Registrasi user baru |
| `POST` | `/login` | — | Login, mendapatkan JWT token |
| `POST` | `/logout` | JWT | Logout, invalidate token |

---

#### POST `/register`

**Request Body:**
```json
{
  "name": "Nama User",
  "email": "user@email.com",
  "password": "minimal6karakter"
}
```

**Response (201):**
```json
{
  "user": { "id": 1, "name": "Nama User", "email": "user@email.com", "role": "user" },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

---

### 2. Reports (Laporan Kerusakan Alam)

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| `GET` | `/reports` | — | List semua laporan (+ filter) |
| `GET` | `/reports/{id}` | — | Detail laporan |
| `POST` | `/reports` | JWT | Buat laporan baru |
| `PUT` | `/reports/{id}` | JWT | Update laporan (owner only) |
| `DELETE` | `/reports/{id}` | JWT | Hapus laporan (owner/admin) |

---

#### GET `/reports`

**Query Parameters (opsional):**
| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `q` | string | Kata kunci pencarian judul |
| `category` | string | Filter: `sampah`, `fasilitas`, `flora_fauna` |
| `status` | string | Filter: `pending`, `verified`, `resolved`, `rejected` |

**Contoh:**
```
GET /api/v1/reports?category=sampah&q=kali
```

**Response (200):**
```json
{
  "success": true,
  "message": "Daftar laporan berhasil diambil",
  "data": [
    {
      "id": 1,
      "title": "Dampak Mikroplastik Kali Tebu Surabaya",
      "description": "Sungai Kali Tebu kini penuh dengan sampah plastik...",
      "category": "sampah",
      "location_name": "Kali Tebu, Surabaya, Jawa Timur",
      "status": "pending",
      "image_url": "https://images.unsplash.com/...",
      "user_id": 1,
      "user": { "id": 1, "name": "Gathan" }
    }
  ],
  "total": 1
}
```

---

#### POST `/reports`

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "title": "Tumpukan Sampah di Pantai Surabaya",
  "description": "Sampah plastik menumpuk sepanjang 200 meter garis pantai",
  "category": "sampah",
  "location_name": "Pantai Kenjeran, Surabaya",
  "image_url": "https://example.com/foto.jpg"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Laporan berhasil dibuat",
  "data": { "id": 15, "title": "Tumpukan Sampah...", "status": "pending", ... }
}
```

---

#### PUT `/reports/{id}`

**Headers:** `Authorization: Bearer <token>`

**Request Body (field opsional, kirim yang ingin diubah saja):**
```json
{
  "title": "Judul yang Diperbarui",
  "description": "Deskripsi baru"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Laporan berhasil diperbarui",
  "data": { ... }
}
```

**Response (403) — Bukan Pemilik:**
```json
{
  "success": false,
  "message": "Anda tidak memiliki izin untuk mengubah laporan ini."
}
```

---

### 3. Campaigns (Kampanye Aksi Alam)

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| `GET` | `/campaigns` | — | List semua kampanye (+ filter status) |
| `GET` | `/campaigns/{id}` | — | Detail kampanye |
| `POST` | `/campaigns` | JWT (organizer) | Buat kampanye baru |
| `PUT` | `/campaigns/{id}` | JWT (owner) | Update kampanye |
| `DELETE` | `/campaigns/{id}` | JWT (owner/admin) | Hapus kampanye |
| `POST` | `/campaigns/{id}/join` | JWT (user) | Daftar sebagai relawan |

---

#### POST `/campaigns`

**Headers:** `Authorization: Bearer <token>` (role harus `organizer`)

**Request Body:**
```json
{
  "title": "Bersih Pantai Kenjeran 2026",
  "description": "Aksi gotong royong membersihkan sampah plastik",
  "event_date": "2026-07-15",
  "max_volunteers": 50,
  "target_metric": 500,
  "metric_unit": "kg sampah",
  "report_id": 1
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Kampanye berhasil dibuat",
  "data": { "id": 2, "title": "Bersih Pantai Kenjeran 2026", "status": "open", ... }
}
```

---

#### POST `/campaigns/{id}/join`

**Headers:** `Authorization: Bearer <token>`

**Response (200) — Berhasil:**
```json
{
  "success": true,
  "message": "Berhasil mendaftar sebagai relawan! +100 XP",
  "exp_points": 1350
}
```

**Response (422) — Kampanye Ditutup/Penuh:**
```json
{
  "success": false,
  "message": "Kuota relawan untuk kampanye ini sudah penuh."
}
```

---

### 4. User Profile

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| `GET` | `/user/profile` | JWT | Profil user login (termasuk rank XP) |
| `GET` | `/user/reports` | JWT | Semua laporan milik user login |
| `GET` | `/user/campaigns` | JWT | Kampanye yang diikuti user login |

---

#### GET `/user/profile`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Gathan",
    "email": "gathan@user.com",
    "role": "user",
    "exp_points": 1250,
    "rank": 1,
    "created_at": "2026-05-07T..."
  }
}
```

---

### 5. Statistik & Leaderboard (API Key)

| Method | Endpoint | Auth | Deskripsi |
|--------|----------|------|-----------|
| `GET` | `/stats` | API Key | Statistik global platform |
| `GET` | `/leaderboard` | API Key | Top 10 relawan berdasarkan XP |

---

#### GET `/stats`

**Headers:** `X-API-KEY: aksialam-api-key-2026-secret`

**Response (200):**
```json
{
  "success": true,
  "message": "Statistik platform AksiAlam",
  "data": {
    "reports": {
      "total": 13,
      "pending": 6,
      "verified": 4,
      "resolved": 2,
      "rejected": 1
    },
    "validated_reports": 6,
    "total_volunteers": 48,
    "total_impact": 280,
    "total_campaigns": 1
  }
}
```

---

#### GET `/leaderboard`

**Headers:** `X-API-KEY: aksialam-api-key-2026-secret`

**Response (200):**
```json
{
  "success": true,
  "message": "Leaderboard relawan AksiAlam",
  "data": [
    { "rank": 1, "name": "Gathan", "exp_points": 1250 },
    { "rank": 2, "name": "Rudi Eco", "exp_points": 900 }
  ]
}
```

---

## Error Codes

| HTTP Code | Deskripsi |
|-----------|-----------|
| `200` | Request berhasil |
| `201` | Resource berhasil dibuat |
| `401` | Unauthorized — token tidak valid atau API key salah |
| `403` | Forbidden — tidak punya izin untuk aksi ini |
| `404` | Resource tidak ditemukan |
| `409` | Conflict — sudah terdaftar di kampanye ini |
| `422` | Validasi gagal atau business rule violation |

---

## Testing dengan Postman

1. Import file `AksiAlam.postman_collection.json` ke Postman
2. Buat environment dengan variabel:
   - `base_url`: `http://localhost:8000/api/v1`
   - `token`: (isi setelah login)
   - `api_key`: `aksialam-api-key-2026-secret`
3. Jalankan request Login terlebih dahulu → token akan tersimpan otomatis
4. Gunakan token tersebut untuk request-request yang memerlukan JWT

---

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| User | `gathan@user.com` | `password123` |
| Organizer | `org@himafortic.com` | `password123` |
| Admin | `admin@aksialam.com` | `password123` |
