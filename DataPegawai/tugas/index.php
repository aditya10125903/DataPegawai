<?php
/**
 * File: index.php | Modul: Tugas
 * Deskripsi: Halaman utama untuk manajemen data tugas.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Daftar Tugas';
$base_path = '../';

require_once '../includes/header.php';

$query = "SELECT t.id_tugas, p.nama_lengkap, t.nama_tugas, t.status 
          FROM tugas t
          JOIN pegawai p ON t.id_pegawai = p.id_pegawai
          ORDER BY t.id_tugas DESC";
$result = $conn->query($query);
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>Manajemen Data Tugas</h1>
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Tugas Baru</a>
        
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nama Tugas</th>
                    <th>Pegawai Bertugas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_tugas']) ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id_tugas'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="detail.php?id=<?= $row['id_tugas'] ?>" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="4" class="text-center">Tidak ada data tugas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

<?php
require_once '../includes/footer.php';
?>