 <?php
/**
 * File: tambah.php
 * Deskripsi: Halaman untuk menambah data pegawai baru.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Tambah Pegawai';
$base_path = '../';

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

// Proses form jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil semua data dari form dan pastikan variabel ada meskipun nilainya null
    $nip = !empty($_POST['nip']) ? $_POST['nip'] : null;
    $nama_lengkap = $_POST['nama_lengkap'] ?? ''; // Wajib diisi
    $tempat_lahir = !empty($_POST['tempat_lahir']) ? $_POST['tempat_lahir'] : null;
    $tanggal_lahir = !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;
    $jenis_kelamin = !empty($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : null;
    $id_agama = filter_input(INPUT_POST, 'id_agama', FILTER_VALIDATE_INT) ?: null;
    $alamat_lengkap = !empty($_POST['alamat_lengkap']) ? $_POST['alamat_lengkap'] : null;
    $no_hp = !empty($_POST['no_hp']) ? $_POST['no_hp'] : null;
    $email = $_POST['email'] ?? ''; // Wajib diisi
    $id_pendidikan = filter_input(INPUT_POST, 'id_pendidikan', FILTER_VALIDATE_INT) ?: null;
    $status_pernikahan = !empty($_POST['status_pernikahan']) ? $_POST['status_pernikahan'] : null;
    $tanggal_masuk = !empty($_POST['tanggal_masuk']) ? $_POST['tanggal_masuk'] : date('Y-m-d');
    $status_pegawai = $_POST['status_pegawai'] ?? 'Aktif'; // Default 'Aktif' jika tidak ada di form
    $id_jabatan = filter_input(INPUT_POST, 'id_jabatan', FILTER_VALIDATE_INT);

    // Validasi sederhana
    if (empty($nama_lengkap) || empty($email) || !$id_jabatan) {
        $error = "Nama Lengkap, Email, dan Jabatan wajib diisi.";
    } else {
        // Mulai transaksi
        $conn->begin_transaction();
        try {
            // 1. Insert ke tabel pegawai
            $stmt_pegawai = $conn->prepare("INSERT INTO pegawai (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, id_agama, alamat_lengkap, no_hp, email, id_pendidikan, status_pernikahan, tanggal_masuk, status_pegawai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_pegawai->bind_param("sssssisssisss", $nip, $nama_lengkap, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $id_agama, $alamat_lengkap, $no_hp, $email, $id_pendidikan, $status_pernikahan, $tanggal_masuk, $status_pegawai);
            $stmt_pegawai->execute();

            $id_pegawai_baru = $conn->insert_id; // Ambil ID pegawai yang baru dibuat
            $stmt_pegawai->close();

            // 2. Insert ke tabel jabatanpegawai
            $stmt_jabatan = $conn->prepare("INSERT INTO jabatanpegawai (id_pegawai, id_jabatan, tanggal_mulai, status) VALUES (?, ?, NOW(), 'Aktif')");
            $stmt_jabatan->bind_param("ii", $id_pegawai_baru, $id_jabatan);
            $stmt_jabatan->execute();
            $stmt_jabatan->close();

            // Jika semua query berhasil, commit transaksi
            $conn->commit();

            set_flash_message('success', 'Data pegawai berhasil ditambahkan!');
            header("Location: index.php");
            exit();

        } catch (mysqli_sql_exception $exception) {
            // Jika terjadi error, rollback semua perubahan
            $conn->rollback();
            
            // Log error dan tampilkan pesan
            error_log("Gagal tambah pegawai: " . $exception->getMessage());
            $error = "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.";
        }
    }
}

require_once '../includes/header.php';
?>

<?php require_once '../includes/navbar.php'; ?>
<div class="container-fluid">
    <div class="main-layout">
        <?php require_once '../includes/sidebar.php'; ?>
        <div class="content-wrapper">
            <main class="main-content">
                <h1><i class="fas fa-user-plus"></i> Tambah Pegawai Baru</h1>
                <?php include 'pegawai_form.php'; ?>
            </main>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>