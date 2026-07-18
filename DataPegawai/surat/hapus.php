<?php
/**
 * File: hapus.php | Modul: Surat
 * Deskripsi: Logika untuk menghapus data surat.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) { header("Location: ../index.php"); exit(); }

$id = $_GET['id'] ?? null;
if ($id) {
    // 1. Ambil nama file dari database sebelum dihapus
    $stmt_select = $conn->prepare("SELECT file_surat FROM surat WHERE id_surat = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $data = $result->fetch_assoc();
    $stmt_select->close();

    // 2. Hapus data dari database
    $stmt_delete = $conn->prepare("DELETE FROM surat WHERE id_surat = ?");
    $stmt_delete->bind_param("i", $id);
    if ($stmt_delete->execute()) {
        // 3. Jika data berhasil dihapus, hapus file fisik dari server
        if ($data && !empty($data['file_surat'])) {
            $file_path = __DIR__ . '/../assets/uploads/surat/' . $data['file_surat'];
            if (file_exists($file_path)) { unlink($file_path); }
        }
        header("Location: index.php?status=hapus_sukses");
    } else { header("Location: index.php?status=hapus_gagal"); }
    $stmt_delete->close();
} else {
    header("Location: index.php");
}
exit();