<?php
/**
 * File: index.php | Modul: Surat
 * Deskripsi: Halaman utama untuk manajemen data surat.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Daftar Surat';
$base_path = '../';

require_once '../includes/header.php';

$stmt = $conn->prepare("SELECT id_surat, nomor_surat, perihal, tanggal_surat, jenis_surat, file_surat FROM surat ORDER BY tanggal_surat DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1><i class="fas fa-envelope"></i> Manajemen Data Surat</h1>
        <a href="tambah.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah Surat Baru</a>
        
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nomor Surat</th>
                    <th>Perihal</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nomor_surat']) ?></td>
                        <td><?= htmlspecialchars($row['perihal']) ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal_surat']))) ?></td>
                        <td><?= htmlspecialchars($row['jenis_surat']) ?></td>
                        <td>
                            <?php if (!empty($row['file_surat'])): ?>
                                <a href="../assets/uploads/surat/<?= htmlspecialchars($row['file_surat']) ?>" class="btn btn-sm btn-info" target="_blank">Lihat File</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="detail.php?id=<?= $row['id_surat'] ?>" class="btn btn-sm btn-primary">Detail</a>
                            <a href="edit.php?id=<?= $row['id_surat'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="hapus.php?id=<?= $row['id_surat'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="6" class="text-center">Tidak ada data surat.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php $stmt->close(); ?>
    </main>
</div>

<?php
require_once '../includes/footer.php';
?>