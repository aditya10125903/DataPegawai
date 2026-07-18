<?php
/**
 * File: detail.php | Modul: Jabatan
 * Deskripsi: Halaman untuk menampilkan detail data jabatan.
 */
require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Detail Jabatan';
$base_path = '../';

// Ambil ID dari URL dan validasi
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit();
}

// Ambil data jabatan dari database
$stmt = $conn->prepare("SELECT id_jabatan, nama_jabatan, gaji_pokok FROM jabatan WHERE id_jabatan = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$jabatan = $result->fetch_assoc();
$stmt->close();

if (!$jabatan) {
    echo "Data tidak ditemukan.";
    exit();
}

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>Detail Jabatan</h1>
        <p><strong>ID Jabatan:</strong> <?= htmlspecialchars($jabatan['id_jabatan']) ?></p>
        <p><strong>Nama Jabatan:</strong> <?= htmlspecialchars($jabatan['nama_jabatan']) ?></p>
        <p><strong>Gaji Pokok:</strong> Rp <?= number_format($jabatan['gaji_pokok'], 0, ',', '.') ?></p>
        <a href="index.php" class="btn btn-primary">Kembali ke Daftar</a>
    </main>
</div>

<?php
require_once '../includes/footer.php';
?>