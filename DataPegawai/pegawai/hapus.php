<?php
/**
 * File: hapus.php
 * Deskripsi: Logika untuk menghapus data pegawai.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $stmt = $conn->prepare("DELETE FROM pegawai WHERE id_pegawai = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        set_flash_message('success', 'Data pegawai berhasil dihapus.');
    } else {
        set_flash_message('error', 'Gagal menghapus data pegawai: ' . $stmt->error);
    }
    $stmt->close();
} else {
    set_flash_message('error', 'ID pegawai tidak valid.');
}

header("Location: index.php");
exit();