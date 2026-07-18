<?php
/**
 * File: index.php | Modul: Proyek
 * Deskripsi: Halaman utama untuk manajemen data proyek.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Daftar Proyek';
$base_path = '../';

require_once '../includes/header.php';

$query = "SELECT id_proyek, nama_proyek, tanggal_mulai, tanggal_selesai, status FROM proyek ORDER BY tanggal_mulai DESC";
$result = $conn->query($query);
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>Manajemen Data Proyek</h1>
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Proyek Baru</a>
        
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nama Proyek</th>
                    <th>Tanggal Mulai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_proyek']) ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal_mulai']))) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <a href="detail.php?id=<?= $row['id_proyek'] ?>" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="4" class="text-center">Tidak ada data proyek.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

<?php
require_once '../includes/footer.php';
?>