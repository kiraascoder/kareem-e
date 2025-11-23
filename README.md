# Kareem Pricing 🪩  
**Dynamic Pricing System for Event Organizer (Kareem Entertainment)**

Sistem web untuk membantu **Kareem Entertainment** menetapkan **harga penawaran event** secara lebih objektif dan adaptif menggunakan:

- **Machine Learning (Random Forest)** untuk memprediksi permintaan
- **Fuzzy Logic** untuk menentukan **faktor penyesuaian harga**
- **Laravel + Vue** untuk manajemen event & tampilan antarmuka
- Dikembangkan sebagai bagian dari **penelitian skripsi** tentang _dynamic pricing_ di industri event organizer.

---

## ✨ Fitur Utama

- **Homepage, About, Contact, Booking**
  - Tampilan publik untuk calon klien: informasi layanan, form kontak, dan form booking event.
- **Manajemen Event (Internal)**
  - CRUD data event (nama event, jenis, tanggal, jumlah peserta, harga dasar, dsb).
  - Riwayat event & log rekomendasi harga.
- **Dynamic Pricing Engine**
  - Backend memanggil **ML Service (FastAPI)** yang berisi model:
    - Random Forest → prediksi permintaan
    - Fuzzy Logic → faktor koreksi harga (turun/naik)
  - Menghasilkan **harga rekomendasi** berdasarkan:
    - jenis event
    - jumlah peserta
    - lead time (jarak booking ke hari H)
    - season (high/low season)
- **Dashboard Internal**
  - Ringkasan event dan (bisa dikembangkan) grafik pendapatan & okupansi.

---

## 🧱 Arsitektur Sistem

Monorepo dengan tiga komponen utama:

```text
kareem-e/
├─ backend/        # Laravel (API + DB + integrasi ML Service)
├─ ml-services/    # FastAPI + Random Forest + Fuzzy Logic
└─ frontend/       # Vue (Vite) - public site + internal dashboard
```

Alur data:

```text
[Browser (Vue)]  →  [Laravel API]  →  [FastAPI ML Service]
      ↑                   ↓                 ↑
      └───────────────[ MySQL ]────────────┘
```

---

## 🛠️ Tech Stack

- **Frontend**
  - Vue 3 (Vite)
  - Axios

- **Backend**
  - Laravel (PHP 8.2+)
  - MySQL / MariaDB
  - HTTP Client Laravel untuk call ML service

- **ML Service**
  - Python 3.10+
  - FastAPI + Uvicorn
  - scikit-learn (Random Forest)
  - scikit-fuzzy (Fuzzy Logic)
  - Pandas, NumPy, joblib

---

## 📁 Struktur Direktori (ringkas)

```text
backend/
  app/
  bootstrap/
  config/
  routes/
    web.php
    api.php         # endpoint API (events, pricing, dll.)
  .env.example
  ...

ml-services/
  app.py            # FastAPI entry point
  fuzzy_engine.py   # Fuzzy rules & membership function
  model-training.ipynb
  rf_model.pkl      # trained Random Forest model
  venv/             # virtualenv (ignored di git)
  requirements.txt

frontend/
  src/
    main.js
    App.vue
    router/
      index.js
    services/
      api.js
    views/
      HomeView.vue
      AboutView.vue
      ContactView.vue
      BookingView.vue
      DashboardView.vue
      EventListView.vue
      # (PricingView.vue, EventFormView.vue, dst — optional)
  .env.example
```

---

## ✅ Prasyarat

Sebelum menjalankan project, pastikan:

- **Git**
- **PHP 8.2+** & **Composer**
- **MySQL** (Laragon / XAMPP / lainnya)
- **Node.js** `20.19+` (disarankan, Vite minimal 20.19)
- **Python 3.10+`

---

## ⚙️ Setup Backend (Laravel)

```bash
cd backend

# install dependency
composer install

# copy env
cp .env.example .env

# generate app key
php artisan key:generate
```

Edit `backend/.env`:

```env
APP_NAME="Kareem Pricing"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kareem_pricing
DB_USERNAME=root
DB_PASSWORD=

ML_SERVICE_URL=http://127.0.0.1:8001
```

Buat database `kareem_pricing` di MySQL, lalu:

```bash
php artisan migrate
# php artisan db:seed   # kalau ada seeder
```

Jalankan backend:

```bash
php artisan serve
# http://127.0.0.1:8000
```

---

## 🤖 Setup ML Services (FastAPI + ML)

```bash
cd ml-services

# buat virtualenv (pertama kali)
python -m venv venv

# aktifkan venv (PowerShell)
.env\Scripts\Activate.ps1

# atau (Git Bash)
# source venv/Scripts/activate

# install dependency
pip install -r requirements.txt
# atau kalau kosong:
# pip install fastapi "uvicorn[standard]" pandas numpy scikit-learn scikit-fuzzy joblib
# pip freeze > requirements.txt
```

Struktur dasar:

- `app.py` → definisi FastAPI routes (`/` dan `/predict-price`)
- `fuzzy_engine.py` → fungsi `compute_factor(...)`
- `rf_model.pkl` → model Random Forest yang sudah di-train

Jalankan ML service:

```bash
uvicorn app:app --reload --port 8001
# http://127.0.0.1:8001
```

---

## 🖥️ Setup Frontend (Vue 3 + Vite)

```bash
cd frontend

npm install
npm install axios
```

Buat `frontend/.env`:

```env
VITE_API_BASE_URL=http://localhost:8000/api
```

Pastikan `src/services/api.js`:

```js
import axios from "axios";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || "http://localhost:8000/api",
});

export default api;
```

Jalankan frontend:

```bash
npm run dev
# http://localhost:5173
```

---

## 🚀 Cara Menjalankan (Urutan)

Setiap kali mau develop / demo:

1. **Nyalakan MySQL** (Laragon/XAMPP).
2. **ML Service**:

   ```bash
   cd ml-services
   # aktifkan venv
   .env\Scripts\Activate.ps1
   uvicorn app:app --reload --port 8001
   ```

3. **Backend Laravel**:

   ```bash
   cd backend
   php artisan serve
   ```

4. **Frontend Vue**:

   ```bash
   cd frontend
   npm run dev
   ```

5. Buka:
   - `http://localhost:5173` → aplikasi frontend (Home, About, Contact, Booking, Dashboard, Events)
   - `http://127.0.0.1:8000/api/ping` → test backend
   - `http://127.0.0.1:8001/` → test ML service

---

## 🔌 API Ringkas

Contoh rute (bisa disesuaikan dengan implementasi):

### Backend (Laravel, `routes/api.php`)

- `GET /api/ping`  
  → Cek status backend ( `{ message: "Backend OK" }` )

- `GET /api/events`  
  → List event

- `POST /api/events`  
  → Tambah event / booking baru

- `GET /api/events/{id}`  
  → Detail event

- `POST /api/events/{id}/recommend-price`  
  → Minta rekomendasi harga ke ML Service, simpan ke `price_recommendations`, dan kembalikan ke frontend.

### ML Service (FastAPI)

- `GET /`  
  → Cek status ML Service (`{ "message": "ML Service running" }`)

- `POST /predict-price`  
  Body (contoh):

  ```json
  {
    "jenis_event": "corporate",
    "tanggal_event": "2025-12-01",
    "tanggal_booking": "2025-11-20",
    "jumlah_peserta": 100,
    "harga_dasar": 25000000,
    "season": "high"
  }
  ```

  Response (contoh):

  ```json
  {
    "lead_time": 10,
    "permintaan_prediksi": 0.82,
    "faktor_harga": 1.2,
    "harga_rekomendasi": 30000000
  }
  ```

---

## 📚 Catatan Skripsi

Repo ini dapat digunakan sebagai:

- **Artefak implementasi** untuk BAB III–IV:
  - Metode: DSR + CRISP-DM + RAD
  - Implementasi: Laravel + FastAPI + ML
- **Bahan screenshot**:
  - UI: homepage, booking, dashboard, rekomendasi harga
  - API: respons JSON dari Laravel dan ML Service
- **Lampiran kode** (dipilih bagian penting: arsitektur, integrasi ML, fuzzy rules).

---

## 📝 License

Project ini digunakan untuk keperluan penelitian akademik.  
Lisensi bisa disesuaikan (misalnya MIT) sesuai kebutuhan pemilik repo.
