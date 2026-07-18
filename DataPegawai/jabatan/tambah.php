<?php
/**
 * File: tambah.php | Modul: Jabatan
 * Deskripsi: Halaman untuk menambah data jabatan baru.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Tambah Jabatan';
$base_path = '../';

// Proses form jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_jabatan = $_POST['nama_jabatan'] ?? '';
    $gaji_pokok = $_POST['gaji_pokok'] ?? 0;

    // Validasi sederhana
    if (!empty($nama_jabatan)) {
        // Cek apakah jabatan sudah ada
        $stmt_check = $conn->prepare("SELECT id_jabatan FROM jabatan WHERE nama_jabatan = ?");
        $stmt_check->bind_param("s", $nama_jabatan);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Nama jabatan sudah ada di database.";
        } else {
            $stmt = $conn->prepare("INSERT INTO jabatan (nama_jabatan, gaji_pokok) VALUES (?, ?)");
            // 'si' -> s untuk string (nama_jabatan), i untuk integer (gaji_pokok)
            $stmt->bind_param("si", $nama_jabatan, $gaji_pokok);
            
            if ($stmt->execute()) {
                // Anda bisa menggunakan flash message jika ada, atau redirect dengan status
                header("Location: index.php?status=tambah_sukses");
                exit();
            } else {
                $error = "Gagal menyimpan data: " . $stmt->error;
            }
            $stmt->close();
        }
        $stmt_check->close();
    } else {
        $error = "Nama Jabatan wajib diisi.";
    }
}

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <div class="main-container">
        <?php require_once '../includes/navbar.php'; ?>
        <div class="content-wrapper">
            <main class="main-content">
                <h1><i class="fas fa-plus-circle"></i> Tambah Jabatan Baru</h1>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form action="tambah.php" method="post">
                    <div class="form-group">
                        <label for="nama_jabatan">Nama Jabatan:</label>
                        <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" required>
                    </div>
                    <div class="form-group">
                        <label for="gaji_pokok">Gaji Pokok:</label>
                        <input type="number" class="form-control" id="gaji_pokok" name="gaji_pokok" value="0">
                    </div>
                    <button type="submit" class="btn-futuristic">Simpan Data</button>
                    <a href="index.php" class="btn-futuristic-reset" style="text-decoration: none; vertical-align: middle;">Batal</a>
                </form>
            </main>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>