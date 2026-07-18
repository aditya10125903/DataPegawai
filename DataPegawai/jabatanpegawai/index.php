<?php
/**
 * File: index.php | Modul: Riwayat Jabatan
 * Deskripsi: Halaman utama untuk manajemen riwayat jabatan pegawai.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

// Pastikan pengguna sudah login
if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Riwayat Jabatan Pegawai';
$base_path = '../';

require_once '../includes/header.php';

// Query untuk mengambil data riwayat jabatan dengan join ke tabel pegawai, jabatan, dan divisi
$query = "SELECT 
            jp.id_jabatan_pegawai,
            p.nama_lengkap,
            j.nama_jabatan,
            d.nama_divisi,
            jp.tanggal_mulai,
            jp.tanggal_selesai,
            jp.status
          FROM jabatanpegawai jp
          JOIN pegawai p ON jp.id_pegawai = p.id_pegawai
          JOIN jabatan j ON jp.id_jabatan = j.id_jabatan
          LEFT JOIN divisi d ON jp.id_divisi = d.id_divisi
          ORDER BY p.nama_lengkap, jp.tanggal_mulai DESC";

$result = $conn->query($query);
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <div class="main-container">
        <?php require_once '../includes/navbar.php'; ?>
        <div class="content-wrapper">
            <main class="main-content">
                <h1><i class="fas fa-history"></i> Manajemen Riwayat Jabatan</h1>
                <div class="button-group my-3">
                    <a href="tambah.php" class="btn-futuristic">Tambah Riwayat Jabatan</a>
                    <a href="cetak.php" class="btn-futuristic-secondary" target="_blank">Cetak PDF</a>
                    <a href="export_excel.php" class="btn-futuristic-secondary">Export ke Excel</a>
                </div>
                
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Jabatan</th>
                            <th>Divisi</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($row['nama_jabatan']) ?></td>
                                <td><?= htmlspecialchars($row['nama_divisi'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal_mulai']))) ?></td>
                                <td><?= $row['tanggal_selesai'] ? htmlspecialchars(date('d-m-Y', strtotime($row['tanggal_selesai']))) : 'Sekarang' ?></td>
                                <td><span class="badge <?= $row['status'] == 'Aktif' ? 'badge-success' : 'badge-secondary' ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                                <td>
                                    <a href="detail.php?id=<?= $row['id_jabatan_pegawai'] ?>" class="btn btn-sm btn-info">Detail</a>
                                    <a href="edit.php?id=<?= $row['id_jabatan_pegawai'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id_jabatan_pegawai'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center">Tidak ada data riwayat jabatan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>