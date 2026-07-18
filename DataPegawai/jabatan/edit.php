<?php
/**
 * File: edit.php | Modul: Jabatan
 * Deskripsi: Halaman untuk mengedit data jabatan.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Edit Jabatan';
$base_path = '../';

// Ambil ID dari URL dan validasi
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit();
}

// Proses form jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_jabatan = $_POST['nama_jabatan'] ?? '';
    $gaji_pokok = $_POST['gaji_pokok'] ?? 0;

    if (!empty($nama_jabatan)) {
        // Cek duplikasi dengan nama jabatan lain (bukan dirinya sendiri)
        $stmt_check = $conn->prepare("SELECT id_jabatan FROM jabatan WHERE nama_jabatan = ? AND id_jabatan != ?");
        $stmt_check->bind_param("si", $nama_jabatan, $id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Nama jabatan sudah digunakan oleh data lain.";
        } else {
            $stmt = $conn->prepare("UPDATE jabatan SET nama_jabatan = ?, gaji_pokok = ? WHERE id_jabatan = ?");
            $stmt->bind_param("sii", $nama_jabatan, $gaji_pokok, $id);
            
            if ($stmt->execute()) {
                header("Location: index.php?status=edit_sukses");
                exit();
            } else {
                $error = "Gagal memperbarui data: " . $stmt->error;
            }
            $stmt->close();
        }
        $stmt_check->close();
    } else {
        $error = "Nama Jabatan wajib diisi.";
    }
}

// Ambil data jabatan yang akan diedit
$stmt = $conn->prepare("SELECT * FROM jabatan WHERE id_jabatan = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$jabatan = $result->fetch_assoc();
$stmt->close();

if (!$jabatan) {
    header("Location: index.php");
    exit();
}

require_once '../includes/header.php';
?>

<?php require_once '../includes/navbar.php'; ?>
<div class="container-fluid">
    <div class="main-layout">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="content-wrapper">
            <main class="main-content">
                <h1><i class="fas fa-edit"></i> Edit Jabatan</h1>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form action="edit.php?id=<?= htmlspecialchars($id) ?>" method="post">
                    <div class="form-group">
                        <label for="nama_jabatan">Nama Jabatan:</label>
                        <input type="text" class="form-control" id="nama_jabatan" name="nama_jabatan" value="<?= htmlspecialchars($jabatan['nama_jabatan']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gaji_pokok">Gaji Pokok:</label>
                        <input type="number" class="form-control" id="gaji_pokok" name="gaji_pokok" value="<?= htmlspecialchars($jabatan['gaji_pokok']) ?>">
                    </div>
                    <button type="submit" class="btn-futuristic">Simpan Perubahan</button>
                    <a href="index.php" class="btn-futuristic-reset" style="text-decoration: none; vertical-align: middle;">Batal</a>
                </form>
            </main>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>