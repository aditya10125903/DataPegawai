<?php
/**
 * File: tambah.php | Modul: Surat
 * Deskripsi: Halaman untuk menambah data surat baru.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Tambah Surat Baru';
$base_path = '../';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_surat = $_POST['nomor_surat'];
    $perihal = $_POST['perihal'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $jenis_surat = $_POST['jenis_surat'];
    $nama_file_surat = null;

    // --- Logika Upload File ---
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file_surat'];
        $allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $max_file_size = 5 * 1024 * 1024; // 5 MB

        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $error = "Tipe file tidak diizinkan. Hanya " . implode(', ', $allowed_extensions) . " yang diperbolehkan.";
        } elseif ($file['size'] > $max_file_size) {
            $error = "Ukuran file terlalu besar. Maksimal 5 MB.";
        }

        $upload_dir = __DIR__ . '/../assets/uploads/surat/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (!isset($error)) {
            $nama_file_surat = 'surat_' . time() . '_' . uniqid() . '.' . $file_extension;
            $lokasi_file_upload = $upload_dir . $nama_file_surat;

            if (!move_uploaded_file($file['tmp_name'], $lokasi_file_upload)) {
                $error = "Gagal mengupload file surat.";
            }
        }
    }

    if (!isset($error)) {
        $stmt = $conn->prepare("INSERT INTO surat (nomor_surat, perihal, tanggal_surat, jenis_surat, file_surat) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nomor_surat, $perihal, $tanggal_surat, $jenis_surat, $nama_file_surat);
        if ($stmt->execute()) { // Eksekusi setelah bind
            header("Location: index.php?status=tambah_sukses");
            exit();
        } else {
            $error = "Gagal menyimpan data: " . $stmt->error;
        }
        $stmt->close();
    }
}

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1><i class="fas fa-plus-circle"></i> Tambah Surat Baru</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="tambah.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nomor_surat">Nomor Surat:</label>
                <input type="text" class="form-control" id="nomor_surat" name="nomor_surat" required>
            </div>
            <div class="form-group">
                <label for="perihal">Perihal:</label>
                <input type="text" class="form-control" id="perihal" name="perihal" required>
            </div>
            <div class="form-group">
                <label for="tanggal_surat">Tanggal Surat:</label>
                <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
            </div>
            <div class="form-group">
                <label for="jenis_surat">Jenis Surat:</label>
                <input type="text" class="form-control" id="jenis_surat" name="jenis_surat" required>
            </div>
            <div class="form-group">
                <label for="file_surat">File Surat :</label>
                <input type="file" class="form-control" id="file_surat" name="file_surat">
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>