<?php
require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Detail Riwayat Jabatan';
$base_path = '../';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit();
}

// Query untuk mengambil data detail
$query = "SELECT 
            p.nama_lengkap,
            j.nama_jabatan,
            d.nama_divisi,
            dep.nama_departemen,
            g.nama_golongan,
            jp.tanggal_mulai,
            jp.tanggal_selesai,
            jp.status
          FROM jabatanpegawai jp
          LEFT JOIN pegawai p ON jp.id_pegawai = p.id_pegawai
          LEFT JOIN jabatan j ON jp.id_jabatan = j.id_jabatan
          LEFT JOIN divisi d ON jp.id_divisi = d.id_divisi
          LEFT JOIN departemen dep ON jp.id_departemen = dep.id_departemen
          LEFT JOIN golongan g ON jp.id_golongan = g.id_golongan
          WHERE jp.id_jabatan_pegawai = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>Detail Riwayat Jabatan</h1>
        <table class="table table-bordered">
            <tr><th>Nama Pegawai</th><td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
            <tr><th>Jabatan</th><td><?= htmlspecialchars($data['nama_jabatan']) ?></td></tr>
            <tr><th>Divisi</th><td><?= htmlspecialchars($data['nama_divisi']) ?></td></tr>
            <tr><th>Departemen</th><td><?= htmlspecialchars($data['nama_departemen']) ?></td></tr>
            <tr><th>Golongan</th><td><?= htmlspecialchars($data['nama_golongan']) ?></td></tr>
            <tr><th>Tanggal Mulai</th><td><?= htmlspecialchars(date('d F Y', strtotime($data['tanggal_mulai']))) ?></td></tr>
            <tr><th>Tanggal Selesai</th><td><?= $data['tanggal_selesai'] ? htmlspecialchars(date('d F Y', strtotime($data['tanggal_selesai']))) : 'Sekarang' ?></td></tr>
            <tr><th>Status</th><td><?= htmlspecialchars($data['status']) ?></td></tr>
        </table>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>