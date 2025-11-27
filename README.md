<div align="center">

[![Typing SVG](https://readme-typing-svg.herokuapp.com?font=Montserrat&weight=600&size=32&duration=3000&pause=2000&color=FFFFFF&center=true&vCenter=true&width=435&lines=Polban+DataView)](https://git.io/typing-svg)

<img src="laravel-vue/public/images/polban-logo.png" alt="Polban DataView Logo" width="120" />

**Aplikasi Visualisasi Data Interaktif**

Dashboard analitik modern untuk memantau data umum, akademik, kemahasiswaan prodi secara real-time dengan arsitektur Cloud-Native.

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D)](https://vuejs.org/)
[![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev/)

[![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)](https://www.chartjs.org/)
[![Leaflet](https://img.shields.io/badge/Leaflet-199900?style=for-the-badge&logo=leaflet&logoColor=white)](https://leafletjs.com/)
 
</div>

<div align="center">

---

### 📈 Project Stats

![GitHub repo size](https://img.shields.io/github/repo-size/ihsan-ramadhan/polban-dataview?style=flat-square&color=FF6384)
![GitHub commit activity](https://img.shields.io/github/commit-activity/m/ihsan-ramadhan/polban-dataview?style=flat-square&color=2196F3)
![GitHub last commit](https://img.shields.io/github/last-commit/ihsan-ramadhan/polban-dataview?style=flat-square&color=646CFF)
![Status](https://img.shields.io/badge/Status-Active-success?style=flat-square)

</div>

---

## 📑 Table of Contents

- [📖 Tentang Proyek](#-tentang-proyek)
- [✨ Fitur Utama](#-fitur-utama)
- [🛠️ Tech Stack](#️-tech-stack)
- [👥 Tim Pengembang](#-tim-pengembang-kelompok-c33)
- [💻 Panduan Instalasi](#-panduan-instalasi)
- [🎯 Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [📂 Struktur Folder](#-struktur-folder)
- [🚨 Troubleshooting](#-troubleshooting)
- [📊 API Endpoints](#-api-endpoints-datacore)
- [🔧 Development Tips](#-development-tips)
- [⭐ Acknowledgments](#-acknowledgments)
- [📚 Resources & Documentation](#-resources--documentation)
- [📝 Lisensi](#-lisensi)
- [📸 Preview](#-preview)

---

## 📖 Tentang Proyek

**Polban DataView** adalah komponen visualisasi *front-end* dari ekosistem **Polban Dataverse**. Aplikasi ini bertugas menyajikan data dalam bentuk grafik interaktif dan peta sebaran untuk memudahkan pemahaman informasi mahasiswa dan akademik Politeknik Negeri Bandung.

### 🔗 Ekosistem Polban Dataverse

Proyek ini merupakan bagian dari kolaborasi tiga tim pengembang:

1. **[DataHub](https://github.com/ErsyaHasby/polban-datahub)** - Mengelola *database*, struktur tabel, halaman admin, dan data partisipan
2. **[DataCore](https://github.com/muhamadSyahid/polban-datacore)** - Mengagregasi data dari DataHub dan menyediakannya melalui REST API ([Lihat Dokumentasi API](#-api-endpoints-datacore))
3. **[DataView](https://github.com/ihsan-ramadhan/polban-dataview)** (Repositori ini) - Mengambil data dari API DataCore dan memvisualisasikannya kepada pengguna

### 🔄 Alur Data

```mermaid
flowchart LR
    subgraph DataSource [💾 Data Layer]
        direction TB
        A[("🗄️ DataHub<br/>(Supabase/PostgreSQL)")]
    end

    subgraph Backend [⚙️ API Layer]
        B["🔌 DataCore<br/>(Bun Runtime)"]
    end

    subgraph Frontend [💻 Client Layer]
        C["🖥️ DataView<br/>(Vue.js + Laravel)"]
    end

    subgraph User [👤 End User]
        D["👀 Guest / Internal"]
    end

    %% Flow
    A ==>|Raw Data Query| B
    B ==>|JSON REST API| C
    C ==>|Interactive UI| D
    D -.->|Filter & Interaction| C
    
    %% Styling
    style A fill:#e1f5fe,stroke:#01579b,stroke-width:2px,color:#000
    style B fill:#fff3e0,stroke:#ff6f00,stroke-width:2px,color:#000
    style C fill:#f3e5f5,stroke:#7b1fa2,stroke-width:2px,color:#000
    style D fill:#e8f5e9,stroke:#2e7d32,stroke-width:2px,color:#000
```

---

## ✨ Fitur Utama

### 📊 Visualisasi Interaktif
- **Grafik Dinamis**: Pie Chart dan Bar Chart menggunakan Chart.js
- **Peta Sebaran**: Visualisasi domisili mahasiswa dengan Leaflet.js
- **Integrasi API**: Mengonsumsi data *real-time* dan teragregasi yang disediakan oleh tim DataCore.

### 🔐 Sistem Akses Bertingkat

| Mode | Akses | Fitur |
|------|-------|-------|
| **Guest** | Publik | Data statistik umum |
| **Internal** | Login Required | Akses penuh data Akademik & Kemahasiswaan |

### ⚡ Teknologi Modern
- **Single Page Application (SPA)**: Pengalaman pengguna mulus tanpa reload halaman
- **Cloud Architecture**: Frontend terpisah yang terhubung ke Backend API
- **Responsive Design**: Optimal di desktop dan mobile

---

## 🛠️ Tech Stack

### Frontend
- **Framework**: [Vue.js](https://vuejs.org/)
- **Build Tool**: [Vite](https://vitejs.dev/)
- **UI Components**: 
  - [Chart.js](https://www.chartjs.org/) & [Vue-chartjs](https://vue-chartjs.org/) - Visualisasi data
  - [Leaflet.js](https://leafletjs.com/) - Peta interaktif
- **Styling**: Custom CSS dengan CSS Variables

### Backend Integration
- **Framework**: [Laravel](https://laravel.com/)
- **API Server**: [Bun Runtime](https://bun.sh/)
- **Database**: [Supabase PostgreSQL](https://supabase.com/)
- **Caching**: [Upstash Redis](https://upstash.com/)

## 👥 Tim Pengembang (Kelompok C3.3)

<div align="center">

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/ihsan-ramadhan">
        <img src="https://github.com/ihsan-ramadhan.png" width="100px;" alt="Ihsan"/><br />
        <sub><b>Muhammad Ihsan Ramadhan</b></sub>
      </a><br />
      <sub>Ketua - UI Lead</sub>
      <sub><br>241511083</br></sub>
    </td>
    <td align="center">
      <a href="https://github.com/MuhammadBrataH">
        <img src="https://github.com/MuhammadBrataH.png" width="100px;" alt="Brata"/><br />
        <sub><b>Muhammad Brata Hadinata</b></sub>
      </a><br />
      <sub>Backend - Authentication</sub>
      <sub><br>241511082</br></sub>
    </td>
    <td align="center">
      <a href="https://github.com/VarianAbi">
        <img src="https://github.com/VarianAbi.png" width="100px;" alt="Varian"/><br />
        <sub><b>Varian Abidarma Syuhada</b></sub>
      </a><br />
      <sub>Frontend - Home</sub>
      <sub><br>241511091</br></sub>
    </td>
  </tr>
  <tr>
    <td align="center">
      <a href="https://github.com/Hanif13579">
        <img src="https://github.com/Hanif13579.png" width="100px;" alt="Hanif"/><br />
        <sub><b>Hanifidin Ibrahim</b></sub>
      </a><br />
      <sub>Frontend - Akademik</sub>
      <sub><br>241511076</br></sub>
    </td>
    <td align="center">
      <a href="https://github.com/ftmhhwwa">
        <img src="https://github.com/ftmhhwwa.png" width="100px;" alt="Fatimah"/><br />
        <sub><b>Fatimah Hawwa Alkhansa</b></sub>
      </a><br />
      <sub>Frontend - Kemahasiswaan</sub>
      <sub><br>241511074</br></sub>
    </td>
    <td align="center">
      <a href="https://github.com/ihsan-ramadhan/polban-dataview/graphs/contributors">
        <img src="https://contrib.rocks/image?repo=ihsan-ramadhan/polban-dataview" width="100px;" alt="All Contributors"/><br />
        <sub><b>All Contributors</b></sub>
      </a><br />
      <sub>View All</sub>
    </td>
  </tr>
</table>

</div>

---

## 💻 Panduan Instalasi

### 📋 Prasyarat


| Software | Minimum Version | Link Download |
|----------|----------------|---------------|
| PHP | 8.1+ | [php.net](https://www.php.net/downloads) |
| Composer | 2.x | [getcomposer.org](https://getcomposer.org/) |
| Node.js | 18.x+ | [nodejs.org](https://nodejs.org/) |
| NPM | 9.x+ | (included with Node.js) |
| Git | 2.x | [git-scm.com](https://git-scm.com/) |

> **💡 Tips Windows**: Gunakan [Laragon](https://laragon.org/) atau [XAMPP](https://www.apachefriends.org/) untuk kemudahan setup PHP & Composer.

### 🚀 Langkah Instalasi

#### 1️⃣ Clone Repository

```bash
# Clone repository
git clone https://github.com/ihsan-ramadhan/polban-dataview

# Masuk ke direktori project
cd polban-dataview

# Masuk ke direktori Laravel + Vue
cd laravel-vue
```

#### 2️⃣ Install Dependencies

```bash
# Install PHP dependencies (Laravel & packages)
composer install

# Install JavaScript dependencies (Vue, Chart.js, Leaflet)
npm install
```

#### 3️⃣ Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key (wajib untuk keamanan)
php artisan key:generate

# Jalankan migrasi database
php artisan migrate
```

#### 4️⃣ Konfigurasi Environment (Opsional)

Buka file `.env` dan sesuaikan jika diperlukan:

```env
APP_NAME="Polban DataView"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Backend API Configuration
API_BASE_URL=http://localhost:3000
API_TIMEOUT=30

# Session & Security
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

> **⚠️ Catatan Penting**: 
> - Database dikelola oleh tim **DataHub**
> - API endpoint dikelola oleh tim **DataCore**
> - Anda **TIDAK PERLU** setup database lokal

---

## 🎯 Menjalankan Aplikasi

### Metode 1: Development Mode (Recommended)

Karena menggunakan **Vite**, Anda perlu menjalankan **2 terminal** secara bersamaan:

#### Terminal 1 - Laravel Server
```bash
php artisan serve
```

#### Terminal 2 - Vite Development Server
```bash
npm run dev
```

### Metode 2: Production Build

Untuk production atau testing build:

```bash
# Build assets untuk production
npm run build

# Jalankan Laravel server
php artisan serve
```

### 🌐 Akses Aplikasi

Buka browser dan akses: **http://127.0.0.1:8000**

---

## 📂 Struktur Folder

```
polban-dataview/
├── 📁 laravel-vue/                  # Main Laravel + Vue application
│   ├── 📁 app/                      # Laravel application logic
│   │   ├── 📁 Http/                 # HTTP controllers
│   │   ├── 📁 Models/               # Database models
│   │   ├── 📁 Providers/            # Service providers
│   │   └── 📁 Traits/               # Reusable traits
│   ├── 📁 config/                   # Configuration files
│   ├── 📁 database/                 # Database files
│   ├── 📁 public/                   # Public assets
│   │   └── 📁 images/               # Images & logos
│   ├── 📁 resources/
│   │   ├── 📁 css/
│   │   │   └── main.css             # Global styles & CSS variables
│   │   ├── 📁 js/
│   │   │   ├── 📁 components/
│   │   │   │   ├── 📁 Charts/      # Reusable chart components
│   │   │   │   │   ├── BasePieChart.vue
│   │   │   │   │   ├── BaseBarChart.vue
│   │   │   │   │   └── BaseMap.vue
│   │   │   │   └── 📁 Layout/      # Layout components
│   │   │   │       ├── AppHeader.vue
│   │   │   │       ├── AppSidebar.vue
│   │   │   │       └── AppFooter.vue
│   │   │   ├── 📁 pages/            # Main pages
│   │   │   │   ├── Home.vue         # Public data & maps
│   │   │   │   ├── Akademik.vue     # Academic data (Internal)
│   │   │   │   └── Kemahasiswaan.vue # Student affairs (Internal)
│   │   │   ├── 📁 router/           # Vue Router configuration
│   │   │   │   └── index.js
│   │   │   ├── App.vue              # Root component
│   │   │   └── app.js               # Entry point
│   │   └── 📁 views/                # Laravel Blade templates
│   ├── 📁 routes/                   # Laravel routes
│   │   └── web.php
│   ├── 📁 storage/                  # Laravel storage
│   ├── 📁 tests/                   # Laravel tests
│   ├── .env.example                 # Environment template
│   ├── .gitignore                   # Git ignore
│   ├── composer.json                # PHP dependencies
│   ├── package.json                 # Node dependencies
│   ├── vite.config.js               # Vite configuration
│   └── README.md                    # Laravel + Vue Documentation
└── README.md                        # Project root documentation
```

---

## 🚨 Troubleshooting

### ❌ Error "Failed to download" saat Composer Install

**Penyebab**: PHP zip extension tidak aktif

**Error yang muncul**:
```
Failed to download doctrine/inflector from dist: The zip extension and unzip/7z commands are both missing, skipping.
The php.ini used by your command-line PHP is: C:\xampp\php\php.ini
```

**Solusi**:

1. **Buka file `php.ini`**:
   - Lokasi: `C:\xampp\php\php.ini` (XAMPP)
   - Atau: `C:\laragon\bin\php\php8.x\php.ini` (Laragon)

2. **Cari baris berikut** (gunakan Ctrl+F):
   ```ini
   ;extension=zip
   ```

3. **Hapus tanda titik koma** (`;`) di depannya:
   ```ini
   extension=zip
   ```

4. **Restart terminal/command prompt** dan jalankan ulang:
   ```bash
   composer install
   ```

---

### ❌ Error 404 pada Salah Satu Chart

**Penyebab**: Endpoint API belum dibuat

**Solusi**: Laporkan ke tim Backend (DataCore) untuk menambahkan endpoint

### ❌ Layar Putih / "Vite manifest not found"

**Penyebab**: Vite dev server tidak berjalan

**Solusi**:
```bash
# Pastikan terminal kedua menjalankan:
npm run dev
```

### ❌ Error "Class 'Composer\...' not found"

**Penyebab**: Dependencies belum terinstall

**Solusi**:
```bash
composer install
```

### ❌ Port 8000 Already in Use

**Solusi**: Gunakan port lain
```bash
php artisan serve --port=8001
```

---

---

## 📊 API Endpoints (DataCore)

Aplikasi ini menggunakan REST API yang disediakan oleh layanan **DataCore**. Untuk melihat daftar lengkap endpoint, struktur request, dan contoh response, silakan kunjungi dokumentasi interaktif kami:

[![API Docs](https://img.shields.io/badge/📖_Buka_Dokumentasi_API-DataCore-0078D4?style=for-the-badge&logo=swagger&logoColor=white)](https://ikhsan3adi.is-a.dev/polban-datacore-api-docs/)

**URL Dokumentasi:** 🔗 [https://ikhsan3adi.is-a.dev/polban-datacore-api-docs/](https://ikhsan3adi.is-a.dev/polban-datacore-api-docs/)

---

## 🔧 Development Tips

### Hot Reload Issues?

Jika hot reload tidak berfungsi:
```bash
# Clear cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Restart Vite
npm run dev
```

### Code Style
 
Gunakan Prettier dan ESLint untuk konsistensi:
```bash
# Install (jika belum)
npm install -D prettier eslint

# Format code
npm run format
```

### Debugging Vue Components

Install Vue DevTools:
- [Chrome Extension](https://chrome.google.com/webstore/detail/vuejs-devtools/nhdogjmejiglipccpnnnanhbledajbpd)
- [Firefox Extension](https://addons.mozilla.org/en-US/firefox/addon/vue-js-devtools/)

---

## ⭐ Acknowledgments

Terima kasih kepada:

- **Politeknik Negeri Bandung** - Institusi pendidikan
- **Dosen Pembimbing** - Bimbingan dan arahan proyek
- **Tim DataHub & DataCore** - Kolaborasi backend yang solid
- **Open Source Community** - Libraries dan tools yang digunakan

---

## 📚 Resources & Documentation

### Official Docs
- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Guide](https://vuejs.org/guide/)
- [Vite Documentation](https://vitejs.dev/guide/)
- [Chart.js Documentation](https://www.chartjs.org/docs/)
- [Leaflet Documentation](https://leafletjs.com/reference.html)

### Related Repositories
- [DataHub Repository](https://github.com/ErsyaHasby/polban-datahub)
- [DataCore Repository](https://github.com/muhamadSyahid/polban-datacore)

### Tutorials & References
- [Laravel + Vue.js Setup Guide](https://laravel.com/docs/vite#vue)
- [Chart.js with Vue 3](https://vue-chartjs.org/guide/)
- [Leaflet Quick Start](https://leafletjs.com/examples/quick-start/)

---

## 📝 Lisensi

© 2025 Kelompok C3.3 - D3 Teknik Informatika Politeknik Negeri Bandung.  
Proyek ini dibuat untuk keperluan akademik.


## 📸 Preview

<div align="center">

### 🏠 Halaman Home (Public Access)
*Dashboard utama dengan visualisasi data publik dan peta sebaran mahasiswa*

### 📚 Halaman Akademik (Internal)
*Visualisasi data akademik dengan berbagai metrik performa*

### 👨‍🎓 Halaman Kemahasiswaan (Internal)
*Dashboard kemahasiswaan dengan statistik lengkap*

</div>

---

<div align="center">

**Dibuat dengan ❤️ dan ☕ oleh Tim Polban DataView**

[![GitHub stars](https://img.shields.io/github/stars/ihsan-ramadhan/polban-dataview?style=social)](https://github.com/ihsan-ramadhan/polban-dataview)
[![GitHub forks](https://img.shields.io/github/forks/ihsan-ramadhan/polban-dataview?style=social)](https://github.com/ihsan-ramadhan/polban-dataview/fork)
[![GitHub issues](https://img.shields.io/github/issues/ihsan-ramadhan/polban-dataview?style=social)](https://github.com/ihsan-ramadhan/polban-dataview/issues)

<img src="https://capsule-render.vercel.app/api?type=waving&color=gradient&height=120&section=footer"/>

</div>