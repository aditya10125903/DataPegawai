<?php
/**
 * File: index.php | Modul: Users
 * Deskripsi: Halaman utama untuk manajemen pengguna sistem.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

// Pastikan pengguna sudah login
if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Manajemen Pengguna';
$base_path = '../';

require_once '../includes/header.php';

$query = "SELECT id_pengguna, username, created_at, last_login FROM pengguna ORDER BY username ASC";
$result = $conn->query($query);
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>Manajemen Pengguna</h1>
        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Pengguna Baru</a>
        
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Username</th>
                    <th>Tanggal Dibuat</th>
                    <th>Login Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars(date('d-m-Y H:i', strtotime($row['created_at']))) ?></td>
                        <td><?= htmlspecialchars($row['last_login'] ? date('d-m-Y H:i', strtotime($row['last_login'])) : 'Belum pernah login') ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="hapus.php?id=<?= $row['id_pengguna'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="4" class="text-center">Tidak ada data pengguna.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

<?php
require_once '../includes/footer.php';
?>