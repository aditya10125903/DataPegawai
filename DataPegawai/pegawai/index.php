<?php
/**
 * File: index.php
 * Deskripsi: Halaman utama untuk manajemen data pegawai.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

// Pastikan pengguna sudah login
if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Data Pegawai';
$base_path = '../';

require_once '../includes/header.php';

// Ambil pesan flash jika ada
$success_message = get_flash_message('success');

// Query untuk mengambil semua data pegawai
$query = "SELECT id_pegawai, nip, nama_lengkap, email, no_hp, status_pegawai FROM pegawai ORDER BY nama_lengkap ASC";
$result = $conn->query($query);

?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <div class="main-container">
        <?php require_once '../includes/navbar.php'; ?>
        <div class="content-wrapper">
            <main class="main-content">
                <div class="header-content">
                    <h1><i class="fas fa-users"></i> Manajemen Data Pegawai</h1>
                    <p>Berikut adalah daftar pegawai yang terdaftar dalam sistem.</p>
                </div>

                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($success_message) ?>
                    </div>
                <?php endif; ?>

                <div class="card-custom">
                    <div class="card-header-custom">
                        <h5 class="card-title-custom">Daftar Pegawai</h5>
                        <div>
                            <a href="tambah.php" class="btn btn-futuristic"><i class="fas fa-plus"></i> Tambah Pegawai</a>
                            <a href="export_excel.php" class="btn btn-futuristic"><i class="fas fa-file-excel"></i> Export</a>
                        </div>
                    </div>
                    <div class="card-body-custom">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>No. HP</th>
                                        <th>Status</th>
                                        <th style="width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php include 'pegawai_list.php'; ?>
                                </tbody>
                            </table>
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