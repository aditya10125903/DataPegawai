<?php
/**
 * File: koneksi.php
 * Deskripsi: Mengelola koneksi ke database MySQL.
 */

// Panggil file konfigurasi database
require_once __DIR__ . '/database.php';
// --- Aktifkan Mode Laporan Error mysqli untuk melempar Exceptions ---
// Ini adalah cara modern untuk menangani error, lebih baik daripada die().
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // --- Buat Koneksi ---
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // --- Atur Charset ke UTF-8 ---
    // Penting untuk mendukung berbagai macam karakter
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    // Tangani error koneksi dengan cara yang lebih aman.
    error_log("Koneksi Database Gagal: " . $e->getMessage());

    // Tampilkan pesan error yang ramah kepada pengguna dan hentikan eksekusi.
    http_response_code(500);
    die("<h1>Koneksi Gagal</h1><p>Terjadi masalah saat menghubungkan ke database. Silakan coba lagi nanti.</p>");
    
    $conn = null;
}

// Kembalikan objek koneksi agar bisa ditangkap oleh skrip yang memanggilnya (seperti setup_project.php)
return $conn;

?>