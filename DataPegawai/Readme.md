# Sistem Manajemen Data Pegawai (DataPegawai)

Selamat datang di proyek Sistem Manajemen Data Pegawai. Dokumen ini adalah panduan lengkap untuk memahami, menginstal, menggunakan, dan mengembangkan aplikasi ini.

## Gambaran Umum

Aplikasi ini dirancang sebagai Sistem Informasi Sumber Daya Manusia (HRIS) yang komprehensif untuk mengelola semua data dan aktivitas yang terkait dengan kepegawaian. Tujuannya adalah untuk menyediakan platform terpusat untuk manajemen data pegawai yang efisien dan terstruktur.

## Fitur Utama

- **Manajemen Data Master**: Mengelola data inti seperti Pegawai, Jabatan, Divisi, Departemen, dll.
- **Manajemen Transaksi**: Mencatat aktivitas harian seperti Presensi, Cuti, Lembur, dan Peringatan.
- **Manajemen Pengguna**: Sistem registrasi dan login yang aman untuk mengakses aplikasi.
- **Laporan**: Menghasilkan berbagai laporan untuk kebutuhan analisis.
- **Antarmuka Futuristik**: Desain antarmuka yang modern dan responsif untuk pengalaman pengguna yang lebih baik.

---

## Panduan Instalasi (Untuk Pengguna)

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan aplikasi ini di komputer lokal Anda.

### Kebutuhan Sistem (Prasyarat)

Pastikan perangkat Anda telah terinstal perangkat lunak berikut:

- **XAMPP**: Paket server web yang berisi Apache (Web Server), MySQL/MariaDB (Database), dan PHP. Anda bisa mengunduhnya dari situs resmi Apache Friends.
- **Web Browser**: Google Chrome, Mozilla Firefox, atau browser modern lainnya.

### Langkah-langkah Instalasi

1.  **Unduh atau Clone Proyek**
    - Letakkan semua file dan folder proyek ke dalam direktori `htdocs` di dalam folder instalasi XAMPP Anda.
    - Contoh: `C:/xampp/htdocs/DataPegawai`.

2.  **Jalankan XAMPP Control Panel**
    - Buka XAMPP Control Panel dan jalankan modul **Apache** dan **MySQL**.

3.  **Buat Database**
    - Buka web browser dan akses phpMyAdmin melalui alamat `http://localhost/phpmyadmin`.
    - Buat database baru dengan nama `db_pegawai`.

4.  **Import Struktur dan Data Database**
    - Di dalam phpMyAdmin, pilih database `db_pegawai` yang baru saja Anda buat.
    - Klik tab **"Import"**.
    - Klik **"Choose File"** dan pilih file `config/database_schema.sql` dari dalam folder proyek.
    - Gulir ke bawah dan klik tombol **"Import"** atau **"Go"**. Proses ini akan membuat semua tabel yang diperlukan oleh aplikasi.

5.  **Konfigurasi Koneksi (Opsional)**
    - Secara default, konfigurasi sudah disesuaikan untuk lingkungan XAMPP standar. Namun, jika Anda menggunakan password untuk MySQL atau konfigurasi lainnya, buka file `config/database.php`.
    - Pastikan detail koneksi (DB_HOST, DB_USER, DB_PASS, DB_NAME) sudah sesuai dengan pengaturan Anda.

6.  **Selesai!**
    - Aplikasi sekarang siap digunakan. Buka browser dan akses alamat `http://localhost/DataPegawai/`.

### Akses Pertama Kali

Setelah instalasi berhasil, Anda akan melihat halaman selamat datang atau halaman login.

- **Tidak ada akun default**: Sistem ini tidak menyediakan akun admin bawaan.
- **Registrasi Pengguna Baru**: Untuk masuk, Anda harus membuat akun terlebih dahulu. Klik tautan **"Daftar di sini"** pada halaman login untuk membuka form registrasi. Isi data yang diperlukan untuk membuat akun baru.
- **Login**: Setelah registrasi berhasil, Anda akan diarahkan kembali ke halaman login. Gunakan username dan password yang baru saja Anda daftarkan untuk masuk ke dashboard.

---

## Panduan untuk Pengembang

Bagian ini berisi informasi teknis yang berguna jika Anda ingin mengembangkan atau memodifikasi aplikasi ini.

### Struktur Folder

Berikut adalah penjelasan mengenai struktur direktori utama proyek:

```
/DataPegawai
├── /api/             # Endpoint API untuk interaksi data (misal: dengan JavaScript).
├── /assets/          # Semua file statis (CSS, JS, gambar, audio).
├── /config/          # File konfigurasi inti (koneksi DB, sesi, otentikasi).
├── /includes/        # Potongan UI yang digunakan berulang (header, footer, sidebar).
├── /vendor/          # Library pihak ketiga (misal: dompdf, phpspreadsheet).
├── /pegawai/         # Contoh folder modul. Berisi file CRUD untuk data pegawai.
├── /jabatan/         # Modul untuk manajemen jabatan.
├── ... (folder modul lainnya)
├── dashboard.php     # Halaman utama setelah user login.
├── index.php         # Halaman login (titik masuk utama).
└── README.md         # File dokumentasi ini.
```

### Penjelasan File Penting:

- **`config/koneksi.php`**: Menangani koneksi ke database MySQL.
- **`config/session.php`**: Mengelola sesi pengguna (login, logout).
- **`config/auth.php`**: Berisi logika untuk proses login dan registrasi.
- **`includes/header.php`**: Bagian atas dari setiap halaman HTML, memuat CSS.
- **`includes/footer.php`**: Bagian bawah dari setiap halaman HTML, memuat JS.
- **`setup_project.php`**: Alat bantu developer. **JANGAN** di-upload ke server produksi.

## 5. Alur Kerja Menambah Modul Baru

Proyek ini dilengkapi dengan generator modul untuk mempercepat pengembangan:

1.  Buka `http://localhost/DataPegawai/setup_project.php` di browser.
2.  Klik tombol **"Tambah Modul Baru"**.
3.  Masukkan nama modul (misal: `proyek` atau `gudang`).
4.  Klik **"Buat Modul"**.
5.  Skrip akan membuat folder dan file-file dasar (index, tambah, edit, hapus, dll.) untuk modul baru Anda di dalam folder `New`.
6.  Pindahkan folder modul baru tersebut dari `New` ke direktori utama `DataPegawai`.
7.  **PENTING**: Tambahkan definisi modul baru Anda ke dalam array `$idealStructure` di `setup_project.php` agar dikenali pada proses verifikasi selanjutnya.
8.  Buat tabel database untuk modul baru Anda (misal: `tabel_proyek`) di phpMyAdmin atau tambahkan ke `config/database_schema.sql` dan jalankan ulang setup.
9.  Mulai kembangkan logika spesifik untuk modul Anda di file-file yang telah dibuat.

---

_Dokumentasi ini dibuat secara otomatis oleh `setup_project.php` pada 2026-07-14 05:45:27_

Link Project :

Link Git :


Link Backup :


Link Phpmyadmin :


portal web untuk masuk ke halaman utama :
http://......./DataPegawai

