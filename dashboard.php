<?php
require_once 'config/session.php';

// Pastikan pengguna sudah login, jika belum, arahkan ke halaman login
if (!is_user_logged_in()) {
    header("Location: index.php");
    exit();
}

// --- Integrasi Database untuk Data Dashboard ---
require_once 'config/koneksi.php';

// Inisialisasi variabel untuk data dashboard dengan nilai default
$total_pegawai = 0;
$hadir_hari_ini = 0;
$pengajuan_cuti = 0;
$total_agama = 0;
$total_riwayat_jabatan = 0;
$total_jabatan = 0;

// Pastikan koneksi database berhasil sebelum menjalankan query
if ($conn) {
    // 1. Query untuk mendapatkan total pegawai aktif
    $result_pegawai = $conn->query("SELECT COUNT(id_pegawai) AS total FROM pegawai WHERE status_pegawai = 'Aktif'");
    if ($result_pegawai) {
        $total_pegawai = $result_pegawai->fetch_assoc()['total'];
    }

    // 2. Query untuk mendapatkan jumlah yang hadir hari ini
    $result_hadir = $conn->query("SELECT COUNT(id_presensi) AS total FROM presensi WHERE status = 'Hadir' AND DATE(waktu_masuk) = CURDATE()");
    if ($result_hadir) {
        $hadir_hari_ini = $result_hadir->fetch_assoc()['total'];
    }

    // 3. Query untuk mendapatkan jumlah pengajuan cuti yang belum diproses
    $result_cuti = $conn->query("SELECT COUNT(id_cuti) AS total FROM cuti WHERE status_pengajuan = 'Diajukan'");
    if ($result_cuti) {
        $pengajuan_cuti = $result_cuti->fetch_assoc()['total'];
    }

    // 4. Query untuk mendapatkan total jenis agama
    $result_agama = $conn->query("SELECT COUNT(id_agama) AS total FROM agama");
    if ($result_agama) {
        $total_agama = $result_agama->fetch_assoc()['total'];
    }

    // 5. Query untuk mendapatkan total data riwayat jabatan
    $result_riwayat_jabatan = $conn->query("SELECT COUNT(id_jabatan_pegawai) AS total FROM jabatanpegawai");
    if ($result_riwayat_jabatan) {
        $total_riwayat_jabatan = $result_riwayat_jabatan->fetch_assoc()['total'];
    }

    // 6. Query untuk mendapatkan total jenis jabatan
    $result_jabatan = $conn->query("SELECT COUNT(id_jabatan) AS total FROM jabatan");
    if ($result_jabatan) {
        $total_jabatan = $result_jabatan->fetch_assoc()['total'];
    }

    // Ambil data foto profil dari tabel pegawai berdasarkan id_pengguna di sesi
    $foto_profil = 'default.png'; // Default
    if (isset($_SESSION['id_pengguna'])) {
        $id_pengguna_session = $_SESSION['id_pengguna'];
        $stmt_foto = $conn->prepare("SELECT foto_pegawai FROM pegawai WHERE id_pengguna = ?");
        if($stmt_foto) {
            $stmt_foto->bind_param("i", $id_pengguna_session);
            $stmt_foto->execute();
            $result_foto = $stmt_foto->get_result();
            if ($result_foto->num_rows > 0) {
                $pegawai_data = $result_foto->fetch_assoc();
                // Pastikan foto_pegawai tidak kosong atau null
                $foto_profil = !empty($pegawai_data['foto_pegawai']) ? $pegawai_data['foto_pegawai'] : 'default.png';
            }
            $stmt_foto->close();
        }
    }
}
// --- Akhir Integrasi Database ---
$page_title = 'Dashboard';
$base_path = './'; // Path relatif untuk file CSS/JS

require_once 'includes/header.php';
require_once 'includes/navbar.php'; // Memanggil navbar
?>
<div class="container-fluid">
    <div class="main-layout">
        <?php require_once 'includes/sidebar.php'; ?>
        <main class="main-content">
            <div class="content-wrapper" style="animation: fadeIn 0.5s ease-out;">
    
                <!-- Kartu Selamat Datang Futuristik -->
                <div class="welcome-card">
                    <div class="profile-pic-container">
                        <?php 
                            $path_foto = $base_path . 'assets/img/profil/' . htmlspecialchars($foto_profil); // Variabel $foto_profil sudah diambil dari query di atas
                        ?>
                        <img src="<?= $path_foto ?>" alt="Foto Profil" class="profile-pic">
                    </div>
                    <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?></h2>
                    <p class="jabatan">Jabatan: <?= htmlspecialchars($_SESSION['nama_jabatan'] ?? 'N/A') ?></p>
                    <p class="activity-text">Silahkan Beraktifitas!!!</p>
                </div>
    
                <h2><i class="fas fa-tachometer-alt"></i> System Overview</h2>
                <p>Ringkasan data sistem terkini.</p>
                <div class="dashboard-widgets">
                    <div class="widget">
                        <h3>Total Pegawai</h3>
                        <p><?= $total_pegawai; ?></p>
                    </div>
                    <div class="widget">
                        <h3>Hadir Hari Ini</h3>
                        <p><?= $hadir_hari_ini; ?></p>
                    </div>
                    <div class="widget">
                        <h3>Pengajuan Cuti</h3>
                        <p><?= $pengajuan_cuti; ?></p>
                    </div>
                    <div class="widget">
                        <h3>Jenis Agama</h3>
                        <p><?= $total_agama; ?></p>
                    </div>
                    <div class="widget">
                        <h3>Total Riwayat Jabatan</h3>
                        <p><?= $total_riwayat_jabatan; ?></p>
                    </div>
                    <div class="widget">
                        <h3>Jenis Jabatan</h3>
                        <p><?= $total_jabatan; ?></p>
                    </div>
                </div>
        
                <p style="margin-top: 2rem;">Gunakan menu di samping untuk mengakses berbagai fitur yang tersedia.</p>
            </div>
        </main>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>