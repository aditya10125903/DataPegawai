<?php
/**
 * File: detail.php
 * Deskripsi: Halaman untuk menampilkan detail data pegawai.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Detail Pegawai';
$base_path = '../';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash_message('error', 'ID Pegawai tidak valid.');
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT p.*, a.nama_agama, pend.tingkat_pendidikan
    FROM pegawai p
    LEFT JOIN agama a ON p.id_agama = a.id_agama
    LEFT JOIN pendidikan pend ON p.id_pendidikan = pend.id_pendidikan
    WHERE p.id_pegawai = ?
");
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pegawai = $result->fetch_assoc();
$stmt->close();

if (!$pegawai) {
    set_flash_message('error', 'Data pegawai tidak ditemukan.');
    header("Location: index.php");
    exit();
}

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/navbar.php'; ?>
    <div class="row">
        <div class="col-md-2">
            <?php require_once '../includes/sidebar.php'; ?>
        </div>
        <div class="col-md-10">
            <main class="main-content">
                <div class="header-content">
                    <h1><i class="fas fa-user-tie"></i> Detail Pegawai</h1>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Informasi Lengkap: <?= htmlspecialchars($pegawai['nama_lengkap']) ?></h5>
                        <div>
                            <a href="edit.php?id=<?= $id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                            <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="<?= $base_path ?>assets/img/<?= $pegawai['foto_pegawai'] ?? 'default-avatar.png' ?>" class="img-fluid rounded-circle mb-3" alt="Foto Pegawai" style="width: 150px; height: 150px; object-fit: cover;">
                                <h4><?= htmlspecialchars($pegawai['nama_lengkap']) ?></h4>
                                <p class="text-muted"><?= htmlspecialchars($pegawai['nip'] ?? 'NIP tidak tersedia') ?></p>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-bordered">
                                    <tr><th style="width: 30%;">Email</th><td><?= htmlspecialchars($pegawai['email'] ?? '-') ?></td></tr>
                                    <tr><th>No. HP</th><td><?= htmlspecialchars($pegawai['no_hp'] ?? '-') ?></td></tr>
                                    <tr><th>Tempat, Tanggal Lahir</th><td><?= htmlspecialchars($pegawai['tempat_lahir'] ?? '-') ?>, <?= !empty($pegawai['tanggal_lahir']) ? date('d F Y', strtotime($pegawai['tanggal_lahir'])) : '-' ?></td></tr>
                                    <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($pegawai['jenis_kelamin'] ?? '-') ?></td></tr>
                                    <tr><th>Agama</th><td><?= htmlspecialchars($pegawai['nama_agama'] ?? '-') ?></td></tr>
                                    <tr><th>Status Pernikahan</th><td><?= htmlspecialchars($pegawai['status_pernikahan'] ?? '-') ?></td></tr>
                                    <tr><th>Alamat</th><td><?= nl2br(htmlspecialchars($pegawai['alamat_lengkap'] ?? '-')) ?></td></tr>
                                    <tr><th>Pendidikan Terakhir</th><td><?= htmlspecialchars($pegawai['tingkat_pendidikan'] ?? '-') ?></td></tr>
                                    <tr><th>Tanggal Masuk</th><td><?= !empty($pegawai['tanggal_masuk']) ? date('d F Y', strtotime($pegawai['tanggal_masuk'])) : '-' ?></td></tr>
                                    <tr><th>Status Pegawai</th><td>
                                        <span class="badge badge-<?= $pegawai['status_pegawai'] == 'Aktif' ? 'success' : 'danger' ?>">
                                            <?= htmlspecialchars($pegawai['status_pegawai']) ?>
                                        </span>
                                    </td></tr>
                                    <tr><th>NPWP</th><td><?= htmlspecialchars($pegawai['npwp'] ?? '-') ?></td></tr>
                                    <tr><th>Rekening Bank</th><td><?= htmlspecialchars($pegawai['rekening_bank'] ?? '-') ?></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>