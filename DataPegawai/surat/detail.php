<?php
/**
 * File: detail.php | Modul: Surat
 * Deskripsi: Halaman untuk menampilkan detail data surat.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$page_title = 'Detail Surat';
$base_path = '../';

$stmt = $conn->prepare("SELECT * FROM surat WHERE id_surat = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) { echo "Data surat tidak ditemukan."; exit(); }

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1><i class="fas fa-info-circle"></i> Detail Surat</h1>
        <table class="table table-bordered">
            <tr><th>Nomor Surat</th><td><?= htmlspecialchars($data['nomor_surat']) ?></td></tr>
            <tr><th>Perihal</th><td><?= htmlspecialchars($data['perihal']) ?></td></tr>
            <tr><th>Tanggal Surat</th><td><?= htmlspecialchars(date('d F Y', strtotime($data['tanggal_surat']))) ?></td></tr>
            <tr><th>Jenis Surat</th><td><?= htmlspecialchars($data['jenis_surat']) ?></td></tr>
            <tr><th>File Surat</th><td><?php if (!empty($data['file_surat'])): ?><a href="../assets/uploads/surat/<?= htmlspecialchars($data['file_surat']) ?>" target="_blank">Lihat/Unduh File</a><?php else: ?>Tidak ada file<?php endif; ?></td></tr>
        </table>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>