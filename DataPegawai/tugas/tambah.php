<?php
// File ini dibuat secara otomatis oleh setup_project.php
// Placeholder: Konten untuk menambah data tugas baru.
// Anda perlu membuat tabel `tugas` di database terlebih dahulu.
// Contoh: CREATE TABLE tugas (id_tugas INT AUTO_INCREMENT PRIMARY KEY, id_proyek INT, id_pegawai INT, nama_tugas VARCHAR(255), status VARCHAR(50));
/**
 * File: tambah.php | Modul: Tugas
 * Deskripsi: Halaman untuk menambah data tugas baru.
 */
require_once '../config/session.php';
require_once '../config/koneksi.php';

// Letakkan logika Anda di sini.
if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Tambah Tugas';
$base_path = '../';

$pegawai_list = $conn->query("SELECT id_pegawai, nama_lengkap FROM pegawai ORDER BY nama_lengkap ASC")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_tugas = $_POST['nama_tugas'];
    $id_pegawai = $_POST['id_pegawai'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("INSERT INTO tugas (nama_tugas, id_pegawai, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $nama_tugas, $id_pegawai, $status);
    if ($stmt->execute()) {
        header("Location: index.php?status=tambah_sukses");
        exit();
    } else {
        $error = "Gagal menyimpan data: " . $stmt->error;
    }
    $stmt->close();
}
require_once '../includes/header.php';
?>
<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>Tambah Tugas Baru</h1>
        <?php if (isset($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form action="tambah.php" method="post">
            <div class="form-group"><label for="nama_tugas">Nama Tugas:</label><input type="text" class="form-control" id="nama_tugas" name="nama_tugas" required></div>
            <div class="form-group"><label for="id_pegawai">Pegawai Bertugas:</label><select class="form-control" id="id_pegawai" name="id_pegawai" required><option value="">-- Pilih Pegawai --</option><?php foreach($pegawai_list as $p): ?><option value="<?= $p['id_pegawai'] ?>"><?= htmlspecialchars($p['nama_lengkap']) ?></option><?php endforeach; ?></select></div>
            <div class="form-group"><label for="status">Status:</label><input type="text" class="form-control" id="status" name="status" value="Baru" required></div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </main>
</div>
<?php require_once '../includes/footer.php'; ?>