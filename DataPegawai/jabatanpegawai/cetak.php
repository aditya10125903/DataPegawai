<?php
require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

echo "<h1>Cetak Data Riwayat Jabatan</h1>";
echo "<p>Halaman ini akan digunakan untuk mencetak data. Logika untuk membuat PDF (misalnya dengan FPDF atau Dompdf) perlu ditambahkan di sini.</p>";