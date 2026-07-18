<?php
/**
 * File: logout.php
 * Deskripsi: Menangani proses logout pengguna.
 */

require_once 'config/session.php';

// Panggil fungsi untuk menghancurkan sesi
logout_user();

// Arahkan pengguna kembali ke halaman login
header('Location: index.php?status=logout_sukses');
exit;