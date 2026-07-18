<?php
/**
 * File: index.php | Modul: Jabatan
 * Deskripsi: Halaman utama untuk menampilkan daftar jabatan.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Data Jabatan';
$base_path = '../';

// Ambil pesan status dari URL
$status = $_GET['status'] ?? '';

// Query untuk mengambil semua data jabatan
$query = "SELECT id_jabatan, nama_jabatan, gaji_pokok FROM jabatan ORDER BY nama_jabatan";
$result = $conn->query($query);

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <div class="main-container">
        <?php require_once '../includes/navbar.php'; ?>
        <div class="content-wrapper">
            <main class="main-content">
                <h1><i class="fas fa-user-tie"></i> Data Jabatan</h1>

                <?php if ($status == 'tambah_sukses'): ?>
                    <div class="alert alert-success">Data jabatan berhasil ditambahkan.</div>
                <?php elseif ($status == 'edit_sukses'): ?>
                    <div class="alert alert-success">Data jabatan berhasil diperbarui.</div>
                <?php elseif ($status == 'hapus_sukses'): ?>
                    <div class="alert alert-success">Data jabatan berhasil dihapus.</div>
                <?php endif; ?>

                <div class="button-group mb-3">
                    <a href="tambah.php" class="btn-futuristic"><i class="fas fa-plus"></i> Tambah Jabatan</a>
                    <a href="export_excel.php" class="btn-futuristic-reset"><i class="fas fa-file-excel"></i> Export</a>
                </div>

                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Jabatan</th>
                            <th>Gaji Pokok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_jabatan']) ?></td>
                                <td><?= htmlspecialchars($row['nama_jabatan']) ?></td>
                                <td>Rp <?= number_format($row['gaji_pokok'], 0, ',', '.') ?></td>
                                <td class="action-cell">
                                    <a href="detail.php?id=<?= $row['id_jabatan'] ?>" class="btn-action btn-info" title="Detail"><i class="fas fa-eye"></i></a>
                                    <a href="edit.php?id=<?= $row['id_jabatan'] ?>" class="btn-action btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="hapus.php?id=<?= $row['id_jabatan'] ?>" class="btn-action btn-danger" title="Hapus" onclick="return confirm('ANALYZING... DELETION PROTOCOL INITIATED. ARE YOU SURE?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">Tidak ada data jabatan.</td></tr>
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