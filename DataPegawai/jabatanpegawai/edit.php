<?php
/**
 * File: edit.php | Modul: Riwayat Jabatan
 * Deskripsi: Halaman untuk mengedit riwayat jabatan pegawai.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Edit Riwayat Jabatan';
$base_path = '../';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit();
}

// Ambil data master untuk dropdown
$pegawai_list = $conn->query("SELECT id_pegawai, nama_lengkap FROM pegawai ORDER BY nama_lengkap")->fetch_all(MYSQLI_ASSOC);
$jabatan_list = $conn->query("SELECT id_jabatan, nama_jabatan FROM jabatan ORDER BY nama_jabatan")->fetch_all(MYSQLI_ASSOC);
$divisi_list = $conn->query("SELECT id_divisi, nama_divisi FROM divisi ORDER BY nama_divisi")->fetch_all(MYSQLI_ASSOC);
$departemen_list = $conn->query("SELECT id_departemen, nama_departemen FROM departemen ORDER BY nama_departemen")->fetch_all(MYSQLI_ASSOC);
$golongan_list = $conn->query("SELECT id_golongan, nama_golongan FROM golongan ORDER BY nama_golongan")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pegawai = $_POST['id_pegawai'];
    $id_jabatan = $_POST['id_jabatan'];
    $id_divisi = $_POST['id_divisi'];
    $id_departemen = $_POST['id_departemen'];
    $id_golongan = $_POST['id_golongan'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = !empty($_POST['tanggal_selesai']) ? $_POST['tanggal_selesai'] : null;
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE jabatanpegawai SET id_pegawai = ?, id_jabatan = ?, id_divisi = ?, id_departemen = ?, id_golongan = ?, tanggal_mulai = ?, tanggal_selesai = ?, status = ? WHERE id_jabatan_pegawai = ?");
    $stmt->bind_param("iiiiisssi", $id_pegawai, $id_jabatan, $id_divisi, $id_departemen, $id_golongan, $tanggal_mulai, $tanggal_selesai, $status, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?status=edit_sukses");
        exit();
    } else {
        $error = "Gagal memperbarui data: " . $stmt->error;
    }
    $stmt->close();
}

// Ambil data yang akan diedit
$stmt = $conn->prepare("SELECT * FROM jabatanpegawai WHERE id_jabatan_pegawai = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>Edit Riwayat Jabatan</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="edit.php?id=<?= htmlspecialchars($id) ?>" method="post">
            <div class="form-group">
                <label for="id_pegawai">Pegawai:</label>
                <select class="form-control" id="id_pegawai" name="id_pegawai" required>
                    <?php foreach ($pegawai_list as $pegawai): ?>
                        <option value="<?= $pegawai['id_pegawai'] ?>" <?= ($pegawai['id_pegawai'] == $data['id_pegawai']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pegawai['nama_lengkap']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_jabatan">Jabatan:</label>
                <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                    <?php foreach ($jabatan_list as $jabatan): ?>
                        <option value="<?= $jabatan['id_jabatan'] ?>" <?= ($jabatan['id_jabatan'] == $data['id_jabatan']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($jabatan['nama_jabatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_divisi">Divisi:</label>
                <select class="form-control" id="id_divisi" name="id_divisi" required>
                    <?php foreach ($divisi_list as $divisi): ?>
                        <option value="<?= $divisi['id_divisi'] ?>" <?= ($divisi['id_divisi'] == $data['id_divisi']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($divisi['nama_divisi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_departemen">Departemen:</label>
                <select class="form-control" id="id_departemen" name="id_departemen" required>
                    <?php foreach ($departemen_list as $departemen): ?>
                        <option value="<?= $departemen['id_departemen'] ?>" <?= ($departemen['id_departemen'] == $data['id_departemen']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($departemen['nama_departemen']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_golongan">Golongan:</label>
                <select class="form-control" id="id_golongan" name="id_golongan" required>
                    <?php foreach ($golongan_list as $golongan): ?>
                        <option value="<?= $golongan['id_golongan'] ?>" <?= ($golongan['id_golongan'] == $data['id_golongan']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($golongan['nama_golongan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai:</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?= htmlspecialchars($data['tanggal_mulai']) ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai (kosongkan jika masih aktif):</label>
                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="<?= htmlspecialchars($data['tanggal_selesai']) ?>">
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Aktif" <?= ($data['status'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                    <option value="Riwayat" <?= ($data['status'] == 'Riwayat') ? 'selected' : '' ?>>Riwayat</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </main>
</div>

<?php
require_once '../includes/footer.php';
?>