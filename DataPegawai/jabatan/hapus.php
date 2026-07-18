<?php
/**
 * File: hapus.php | Modul: Jabatan
 * Deskripsi: Skrip untuk menghapus data jabatan.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

// Ambil ID dari URL dan validasi
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM jabatan WHERE id_jabatan = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?status=hapus_sukses");
    set_flash_message('success', 'Data jabatan berhasil dihapus.');
} else {
    // Anda bisa menambahkan penanganan error yang lebih baik di sini
    header("Location: index.php?status=hapus_gagal");
    set_flash_message('error', 'Gagal menghapus data jabatan. Mungkin data ini masih digunakan di tabel lain.');
}
$stmt->close();
header("Location: index.php");
exit();