<?php
require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

echo "<h1>Export ke Excel</h1>";
echo "<p>Halaman ini akan digunakan untuk mengekspor data ke format Excel. Logika menggunakan library seperti PhpSpreadsheet perlu ditambahkan di sini.</p>";