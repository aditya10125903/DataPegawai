<?php
/**
 * File: edit.php | Modul: Surat
 * Deskripsi: Halaman untuk mengedit data surat.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$page_title = 'Edit Surat';
$base_path = '../';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_surat = $_POST['nomor_surat'];
    $perihal = $_POST['perihal'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $jenis_surat = $_POST['jenis_surat'];
    $file_surat_lama = $_POST['file_surat_lama'];
    $nama_file_surat = $file_surat_lama;
    $upload_dir = __DIR__ . '/../assets/uploads/surat/';

    // --- Logika Hapus File jika dicentang ---
    if (isset($_POST['hapus_file_checkbox']) && !empty($file_surat_lama)) {
        $file_path_lama = $upload_dir . $file_surat_lama;
        if (file_exists($file_path_lama)) {
            unlink($file_path_lama);
        }
        $nama_file_surat = null; // Set nama file menjadi null di database
        // Jika ada file baru yang diupload bersamaan dengan hapus, file baru akan menimpa nilai null ini.
    }

    
    // --- Logika Upload File Baru ---
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nama_file_surat = 'surat_' . time() . '_' . uniqid() . '.' . $file_extension;
        $lokasi_file_upload_baru = $upload_dir . $nama_file_surat;

        if (move_uploaded_file($file['tmp_name'], $lokasi_file_upload_baru)) {
            // Hapus file lama jika ada dan file baru berhasil diupload
            $file_path_lama = $upload_dir . $file_surat_lama;
            if (!empty($file_surat_lama) && file_exists($file_path_lama)) {
                unlink($file_path_lama);
            }
        } else {
            $error = "Gagal mengupload file surat baru.";
            $nama_file_surat = $file_surat_lama; // Kembalikan ke nama file lama jika gagal
        }
    }

    if (!isset($error)) {
        $stmt = $conn->prepare("UPDATE surat SET nomor_surat = ?, perihal = ?, tanggal_surat = ?, jenis_surat = ?, file_surat = ? WHERE id_surat = ?");
        $stmt->bind_param("sssssi", $nomor_surat, $perihal, $tanggal_surat, $jenis_surat, $nama_file_surat, $id);
        if ($stmt->execute()) { // Eksekusi setelah bind
            header("Location: index.php?status=edit_sukses");
            exit();
        } else {
            $error = "Gagal memperbarui data: " . $stmt->error;
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT * FROM surat WHERE id_surat = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
if (!$data) { echo "Data tidak ditemukan."; exit(); }
$stmt->close();

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1><i class="fas fa-edit"></i> Edit Surat</h1>
        <?php if (isset($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form action="edit.php?id=<?= htmlspecialchars($id) ?>" method="post" enctype="multipart/form-data">
            <div class="form-group"><label for="nomor_surat">Nomor Surat:</label><input type="text" class="form-control" id="nomor_surat" name="nomor_surat" value="<?= htmlspecialchars($data['nomor_surat']) ?>" required></div>
            <div class="form-group"><label for="perihal">Perihal:</label><input type="text" class="form-control" id="perihal" name="perihal" value="<?= htmlspecialchars($data['perihal']) ?>" required></div>
            <div class="form-group"><label for="tanggal_surat">Tanggal Surat:</label><input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="<?= htmlspecialchars($data['tanggal_surat']) ?>" required></div>
            <div class="form-group"><label for="jenis_surat">Jenis Surat:</label><input type="text" class="form-control" id="jenis_surat" name="jenis_surat" value="<?= htmlspecialchars($data['jenis_surat']) ?>" required></div>
            <div class="form-group">
                <label for="file_surat">File Surat :</label>
                <input type="file" class="form-control" id="file_surat" name="file_surat">
                <input type="hidden" name="file_surat_lama" value="<?= htmlspecialchars($data['file_surat']) ?>">
                <?php if (!empty($data['file_surat'])): ?>
                    <small class="form-text text-muted mt-2">
                        File saat ini: <a href="../assets/uploads/surat/<?= htmlspecialchars($data['file_surat']) ?>" target="_blank"><?= htmlspecialchars($data['file_surat']) ?></a><br>
                        <input type="checkbox" name="hapus_file_checkbox" id="hapus_file_checkbox" value="1">
                        <label for="hapus_file_checkbox" class="text-danger">Centang untuk menghapus file saat ini</label>
                    </small>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>