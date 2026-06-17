# PYS Manager — PickYourStyle Management App

Aplikasi manajemen UMKM konveksi/leather jacket berbasis web. Dibangun dengan Laravel 13, Tailwind CSS v4, Alpine.js, dan MySQL.

## Fitur

- **Dashboard** — Grafik penjualan 6 bulan, statistik kunci, produk terlaris, pesanan terbaru
- **Inventory** — CRUD produk & varian (SKU, ukuran, warna, stok, harga), peringatan stok menipis
- **Transaksi Keuangan** — Pencatatan pemasukan & pengeluaran, analisis laba per produk, grafik keuangan
- **Pelanggan** — Data pelanggan lengkap dengan 5 ukuran badan (dada, bahu, lengan, badan, lingkar perut)
- **Pemasok** — Data supplier dengan kontak dan tipe pasokan
- **Pesanan Produksi** — Workflow status (PENDING → POTONG → JAHIT → FINISHING → SIAP → TERKIRIM)
- **Visual Warehouse** — Peta gudang interaktif 25x15 dengan zona warna, drag-to-select, manajemen stok per container
- **Dark Mode** — Toggle light/dark dengan persistensi localStorage
- **Mobile Friendly** — Bottom navigation untuk mobile, sidebar untuk desktop

## Persyaratan Sistem

- PHP 8.3+
- Composer 2.x
- Node.js 20+ & NPM
- MySQL 8.0+ (atau MariaDB 10.4+)
- Git

## Instalasi

### Windows

1. **Install prerequisites**
   ```bash
   # Install PHP 8.3+ — download dari https://windows.php.net/download/
   # Install Composer — https://getcomposer.org/download/
   # Install Node.js — https://nodejs.org/ (v20+)
   # Install XAMPP / Laragon / MySQL Server — untuk database MySQL
   ```

2. **Clone repositori**
   ```bash
   git clone https://github.com/Sjonathan2/PYSmanager.git
   cd PYSmanager
   ```

3. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

4. **Konfigurasi environment**
   ```bash
   copy .env.example .env
   ```
   Lalu edit `.env` — sesuaikan database MySQL:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pys_manager
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate key & migrate database**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

6. **Jalankan aplikasi**
   ```bash
   php artisan serve
   npm run dev
   ```
   Buka `http://localhost:8000` di browser.

### Mac

1. **Install prerequisites**
   ```bash
   # Install Homebrew jika belum: https://brew.sh/
   brew install php@8.3 composer node
   # Install MySQL:
   brew install mysql
   brew services start mysql
   ```

2. **Clone repositori**
   ```bash
   git clone https://github.com/Sjonathan2/PYSmanager.git
   cd PYSmanager
   ```

3. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

4. **Konfigurasi environment**
   ```bash
   cp .env.example .env
   ```
   Lalu edit `.env` — sesuaikan database MySQL:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pys_manager
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate key & migrate database**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

6. **Jalankan aplikasi**
   ```bash
   php artisan serve
   npm run dev
   ```
   Buka `http://localhost:8000` di browser.

### Menggunakan Laravel Herd (Mac & Windows)

Alternatif yang lebih praktis — install [Laravel Herd](https://herd.laravel.com/) yang sudah termasuk PHP 8.3, Nginx, dan MySQL:
```bash
herd link pys-manager
herd open
```

## Akun Default

Tidak ada sistem login/auth — aplikasi langsung bisa digunakan setelah migrate & seed. Data dummy sudah tersedia:
- 10 produk dengan 4 varian masing-masing
- 80 transaksi (6 bulan)
- 8 pelanggan
- 6 pemasok
- 10 pesanan produksi

## Teknologi

- **Backend:** Laravel 13, PHP 8.3
- **Frontend:** Blade, Tailwind CSS v4, Alpine.js, Chart.js
- **Database:** MySQL
- **Build Tool:** Vite 8

## Lisensi

MIT
