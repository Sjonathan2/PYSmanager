# Project Context & AI Guidelines: PYS Manager

Dokumen ini berfungsi sebagai panduan dan konteks untuk AI (LLM / Agent) mana pun yang akan membantu mengembangkan proyek ini. Bacalah dokumen ini terlebih dahulu sebelum melakukan perubahan pada basis kode.

## 1. Tech Stack Overview
- **Backend Framework:** Laravel 13 (v13.12.0)
- **PHP Version:** >= 8.3
- **Frontend Stack:** Blade, Tailwind CSS v4.0.0, Vite v8.0.0
- **Testing Framework:** Pest v4.7
- **Database:** SQLite (Default koneksi lokal, file: `database/database.sqlite`)
- **Local Development Environment:** Laravel Herd (`http://pys_manager.test`)
- **Node/NPM:** Terintegrasi menggunakan Vite untuk asset bundling.

## 2. Status & Struktur Proyek Saat Ini
- **Status:** Proyek ini masih berupa **Fresh Installation (Blank Slate)**.
- **Model:** Baru terdapat default model `User`.
- **Controller:** Baru terdapat default `Controller`.
- **View:** Baru terdapat default `welcome.blade.php`.
- **Route:** `routes/web.php` masih standar bawaan Laravel.
- **Konfigurasi Tambahan:**
  - Session, Cache, dan Queue diatur menggunakan driver `database`.
  - Terdapat script bawaan `composer dev` yang menjalankan server, antrean (queue), dan Vite secara serentak (menggunakan library `concurrently`).

## 3. Aturan & Konvensi Pengembangan (AI Instructions)
Setiap AI yang mengembangkan proyek ini **WAJIB** mengikuti aturan berikut:

### A. Code Style & PHP Standards
- Gunakan fitur modern **PHP 8.3+** (seperti constructor property promotion, typed properties, readonly classes, nullsafe operator, match expressions).
- Sebisa mungkin gunakan strict typing.
- Ikuti standar penamaan Laravel (misal: nama tabel jamak/snake_case, nama model tunggal/PascalCase, metode controller camelCase).
- **Fat Models, Skinny Controllers:** Pindahkan logika bisnis yang kompleks ke Service Classes atau Action Classes, dan gunakan Form Request Classes untuk validasi form. Jangan menumpuk logika di Controller.

### B. Frontend & UI (Tailwind CSS 4)
- Proyek ini menggunakan **Tailwind CSS v4**. Gunakan utilitas class bawaan Tailwind untuk styling.
- Terapkan pendekatan *Mobile-First Design* untuk UI yang responsif dan modern.
- Jangan sembarangan menambahkan framework JS yang berat. Jika butuh interaktivitas ringan, sarankan penggunaan **Alpine.js** atau tetap gunakan Javascript vanilla modular via Vite. Jika butuh UI dinamis yang kompleks, tanyakan dulu pada user (apakah ingin menggunakan Livewire, Vue, React, atau Inertia).
- Komponen UI harus modular menggunakan fitur Blade Components.

### C. Database & Migrasi
- Selalu buat migrasi (Migration) saat menambah atau mengubah struktur tabel.
- Buat Factory dan Seeder untuk setiap entitas agar data pengujian lokal (dummy data) mudah di-generate ulang (`php artisan migrate:fresh --seed`).

### D. Testing (Pest)
- Proyek ini telah dipasangkan **Pest** sebagai framework pengujian.
- Selalu buat test menggunakan format sintaks Pest yang rapi (`it('does something', function () { ... });`).
- Jalankan test menggunakan perintah `php artisan test`.

## 4. Cara Menggunakan Proyek
- **Backend & Frontend (Dev Mode):** Gunakan `composer run dev` untuk menjalankan Laravel Server, Queue Listener, dan Vite secara serentak.
- **Menjalankan Migrasi:** `php artisan migrate`
- **Testing:** `php artisan test`

---
*Catatan untuk AI: Jadikan dokumen ini sebagai pedoman utama referensi memori Anda. Jika ragu terkait keputusan arsitektur (seperti penambahan framework baru), selalu tanyakan terlebih dahulu kepada pengguna.*