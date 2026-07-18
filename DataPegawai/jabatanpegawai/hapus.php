<?php
/**
 * File: hapus.php | Modul: Riwayat Jabatan
 * Deskripsi: Logika untuk menghapus data riwayat jabatan.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $conn->prepare("DELETE FROM jabatanpegawai WHERE id_jabatan_pegawai = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php?status=hapus_sukses");
exit();