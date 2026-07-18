<?php
/**
 * File: edit.php
 * Deskripsi: Halaman untuk mengedit data pegawai. 
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Edit Pegawai';
$base_path = '../';

// Ambil ID dari URL dan validasi
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php?status=error&msg=invalid_id");
    exit();
}

// Proses form jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil semua data dari form dan pastikan variabel ada meskipun nilainya null
    $nip = !empty($_POST['nip']) ? $_POST['nip'] : null;
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $tempat_lahir = !empty($_POST['tempat_lahir']) ? $_POST['tempat_lahir'] : null;
    $tanggal_lahir = !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;
    $jenis_kelamin = !empty($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : null;
    $id_agama = filter_input(INPUT_POST, 'id_agama', FILTER_VALIDATE_INT) ?: null;
    $alamat_lengkap = !empty($_POST['alamat_lengkap']) ? $_POST['alamat_lengkap'] : null;
    $no_hp = !empty($_POST['no_hp']) ? $_POST['no_hp'] : null;
    $email = $_POST['email'] ?? '';
    $id_pendidikan = filter_input(INPUT_POST, 'id_pendidikan', FILTER_VALIDATE_INT) ?: null;
    $status_pernikahan = !empty($_POST['status_pernikahan']) ? $_POST['status_pernikahan'] : null;
    $tanggal_masuk = !empty($_POST['tanggal_masuk']) ? $_POST['tanggal_masuk'] : null;
    $status_pegawai = $_POST['status_pegawai'] ?? 'Aktif';
    $id_jabatan = filter_input(INPUT_POST, 'id_jabatan', FILTER_VALIDATE_INT);

    // Validasi sederhana
    if (empty($nama_lengkap) || empty($email) || !$id_jabatan) {
        $error = "Nama Lengkap, Email, dan Jabatan wajib diisi.";
    } else {
        $conn->begin_transaction();
        try {
            // 1. Update tabel pegawai
            $stmt_pegawai = $conn->prepare("UPDATE pegawai SET nip=?, nama_lengkap=?, tempat_lahir=?, tanggal_lahir=?, jenis_kelamin=?, id_agama=?, alamat_lengkap=?, no_hp=?, email=?, id_pendidikan=?, status_pernikahan=?, tanggal_masuk=?, status_pegawai=? WHERE id_pegawai=?");
            $stmt_pegawai->bind_param("sssssisssisssi", $nip, $nama_lengkap, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $id_agama, $alamat_lengkap, $no_hp, $email, $id_pendidikan, $status_pernikahan, $tanggal_masuk, $status_pegawai, $id);
            $stmt_pegawai->execute();
            $stmt_pegawai->close();

            // 2. Update tabel jabatanpegawai
            // Nonaktifkan semua jabatan sebelumnya untuk pegawai ini
            $stmt_nonaktif = $conn->prepare("UPDATE jabatanpegawai SET status = 'Riwayat', tanggal_selesai = NOW() WHERE id_pegawai = ? AND status = 'Aktif'");
            $stmt_nonaktif->bind_param("i", $id);
            $stmt_nonaktif->execute();
            $stmt_nonaktif->close();

            // Tambahkan jabatan baru sebagai 'Aktif'
            if ($id_jabatan > 0) {
                $stmt_jabatan_baru = $conn->prepare("INSERT INTO jabatanpegawai (id_pegawai, id_jabatan, tanggal_mulai, status) VALUES (?, ?, NOW(), 'Aktif')");
                $stmt_jabatan_baru->bind_param("ii", $id, $id_jabatan);
                $stmt_jabatan_baru->execute();
                $stmt_jabatan_baru->close();
            }

            $conn->commit();
            set_flash_message('success', 'Data pegawai berhasil diperbarui!');
            header("Location: index.php");
            exit();

        } catch (mysqli_sql_exception $exception) {
            $conn->rollback();
            error_log("Gagal update pegawai: " . $exception->getMessage());
            $error = "Terjadi kesalahan saat memperbarui data. Silakan coba lagi.";
        }
    }
}

// Ambil daftar jabatan untuk dropdown
$jabatan_list = [];
$result_jabatan = $conn->query("SELECT id_jabatan, nama_jabatan FROM jabatan ORDER BY nama_jabatan ASC");
if ($result_jabatan) {
    $jabatan_list = $result_jabatan->fetch_all(MYSQLI_ASSOC);
}
// Ambil daftar agama
$agama_list = [];
$result_agama = $conn->query("SELECT id_agama, nama_agama FROM agama ORDER BY nama_agama ASC");
if ($result_agama) {
    $agama_list = $result_agama->fetch_all(MYSQLI_ASSOC);
}

// Ambil daftar pendidikan
$pendidikan_list = [];
$result_pendidikan = $conn->query("SELECT id_pendidikan, tingkat_pendidikan FROM pendidikan ORDER BY id_pendidikan ASC");
if ($result_pendidikan) {
    $pendidikan_list = $result_pendidikan->fetch_all(MYSQLI_ASSOC);
}

// Ambil data pegawai yang akan diedit, termasuk jabatan aktifnya
$stmt = $conn->prepare("
    SELECT 
        p.*, 
        jp.id_jabatan 
    FROM pegawai p
    LEFT JOIN jabatanpegawai jp ON p.id_pegawai = jp.id_pegawai AND jp.status = 'Aktif' 
    WHERE p.id_pegawai = ? 
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pegawai = $result->fetch_assoc();
$stmt->close();

if (!$pegawai) {
    set_flash_message('error', 'Data pegawai tidak ditemukan.');
    header("Location: index.php");
    exit();
}

require_once '../includes/header.php';
?>

<?php require_once '../includes/navbar.php'; ?>
<div class="container-fluid">
    <div class="main-layout">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="content-wrapper" style="animation: fadeIn 0.5s ease-out;">
            <main class="main-content card-custom">
                <div class="card-header-custom">
                    <h1 class="card-title-custom"><i class="fas fa-user-edit"></i> Edit Data Pegawai</h1>
                </div>
                <?php include 'pegawai_form.php'; ?>
            </main>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>