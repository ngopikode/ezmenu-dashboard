# EzMenu - SaaS Digital Menu Dashboard

Platform untuk membangun dan mengelola menu digital multi-tenant. Proyek ini adalah backend, database, dan dashboard
admin untuk aplikasi EzMenu, yang memungkinkan pemilik restoran mendaftar, membuat profil, dan mengelola menu mereka
secara mandiri.

## 🚀 Konteks & Tujuan Proyek (The Big Picture)

Tujuan utama proyek ini adalah membuat sebuah sistem **Software as a Service (SaaS)** di mana setiap pengguna bisa:

1. **Mendaftar** sebagai pemilik restoran.
2. **Membuat dan Mengonfigurasi** outlet/restoran mereka sendiri dengan subdomain unik (misal:
   `samarotikukus.ezmenu.com`).
3. **Mengelola Menu** secara dinamis melalui dashboard yang intuitif (dibuat dengan Laravel Livewire).
4. Secara otomatis mendapatkan **Halaman Menu Publik** yang akan di-consume oleh frontend (React/Vue/dll).

Proyek ini FOKUS pada pembuatan **sistem di balik layar**: dashboard admin, logika bisnis, dan API.

## ✅ Daftar Fitur & Tugas (Development Prompts)

Ini adalah checklist fitur yang harus dibangun menggunakan Laravel Livewire.

### 1. Autentikasi & Tenant

- [ ] **Registrasi Pengguna:** Form untuk mendaftarkan akun pemilik (`users` table).
- [ ] **Login Pengguna:** Form login standar.
- [ ] **Setup Restoran:** Setelah registrasi, paksa pengguna untuk membuat profil restoran (`restaurants` table). Form
  ini harus berisi:
    - Nama Restoran
    - **Subdomain** (harus unik se-sistem, validasi ketat di sini!)
    - Nomor WhatsApp
    - Alamat
- [ ] **Middleware Multi-Tenancy:** Pastikan setiap pengguna yang login hanya bisa melihat dan mengelola data
  restorannya sendiri.

### 2. Dashboard Utama

- [ ] **Halaman Dashboard:** Tampilkan ringkasan data, seperti:
    - Jumlah Kategori Menu
    - Jumlah Produk
    - (Future) Grafik item yang paling sering dilihat.

### 3. Manajemen Menu (CRUD Lengkap)

- [ ] **Manajemen Kategori:**
    - Buat, Baca, Update, Hapus (CRUD) untuk kategori menu (`categories` table).
    - Fitur untuk **mengurutkan** kategori (drag-and-drop akan jadi nilai plus).
- [ ] **Manajemen Produk:**
    - CRUD untuk produk (`products` table).
    - Form harus mencakup: Nama, Deskripsi, Harga, Ketersediaan (toggle on/off).
    - **Upload Gambar** untuk setiap produk.
    - Pilih **Kategori** dari daftar yang sudah dibuat.
    - Pilih **Tipe Produk** (`single`, `multi`, `drink`) untuk menentukan logika di frontend.
- [ ] **Manajemen Opsi/Varian:**
    - Di halaman edit produk, jika `type` adalah `single` atau `multi`, tampilkan interface untuk CRUD **opsi rasa** (
      `options` table).
    - Pengguna harus bisa menambah atau menghapus varian seperti "Cokelat", "Keju", "Oreo", dll. untuk produk tersebut.

### 4. Pengaturan Restoran

- [ ] **Halaman Pengaturan:** Form untuk mengedit semua data di `restaurants` table yang sudah dibuat saat setup awal.
    - Ganti Nama, Logo, Alamat.
    - Ganti **Warna Tema** (gunakan input color picker).
    - Ganti Nomor WhatsApp.

### 5. API untuk Frontend

- [ ] **Endpoint Publik:** Buat satu API endpoint yang tidak memerlukan autentikasi:
    - **URL:** `GET /api/client/{subdomain}`
    - **Fungsi:** Mengambil semua data yang dibutuhkan oleh halaman menu publik (frontend React) berdasarkan subdomain.
    - **Output JSON:** Harus sesuai dengan format yang sudah ditentukan (lihat di bawah).

## 🛠️ Teknologi yang Digunakan

- **Backend:** Laravel 11
- **Frontend Dashboard:** Livewire 3 & Alpine.js
- **Database:** MySQL
- **Styling:** Tailwind CSS

## 🗄️ Struktur Database (MySQL)

Berikut adalah skema database yang dirancang untuk mendukung arsitektur multi-tenant.

- **`users`**: Menyimpan data login pemilik akun.
- **`restaurants`**: Tabel tenant utama. Setiap baris adalah satu pelanggan SaaS.
- **`categories`**: Kategori menu yang dimiliki oleh setiap restoran.
- **`products`**: Semua produk dari semua restoran.
- **`options`**: Varian rasa untuk setiap produk.
