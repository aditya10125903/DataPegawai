<?php
/**
 * File: index.php 
 * Deskripsi: Halaman login utama dan titik masuk aplikasi.
 */

require_once 'config/session.php';
require_once 'config/koneksi.php'; // Selalu butuh koneksi untuk jabatan
require_once 'config/auth.php'; // Logika otentikasi akan menangani jika ada POST action

// Jika pengguna sudah login, arahkan langsung ke dashboard.
if (is_user_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

// Ambil data jabatan untuk dropdown di form registrasi
$jabatan_list = [];
if ($conn) {
    $result = $conn->query("SELECT id_jabatan, nama_jabatan FROM jabatan ORDER BY nama_jabatan ASC");
    if ($result) {
        $jabatan_list = $result->fetch_all(MYSQLI_ASSOC);
    }
}

$page_title = 'Login & Registrasi';
$base_path = './'; // Path relatif untuk file CSS/JS

require_once 'includes/header.php';
?>
<?php
    // Ambil data form lama dari sesi jika ada (untuk mengisi kembali form)
    $old_input = $_SESSION['form_input'] ?? [];
    // Hapus setelah diambil agar tidak digunakan lagi pada refresh berikutnya
    unset($_SESSION['form_input']);
?>
<?php
    // Ambil pesan flash sebelum ditampilkan
    $error_message = get_flash_message('error');
    $success_message = get_flash_message('success');

    // Tentukan halaman aktif. Jika ada input lama (dari reg gagal), paksa ke 'register'.
    // Jika tidak, periksa parameter 'page' atau default ke 'login'.
    $active_page = !empty($old_input) ? 'register' : ($_GET['page'] ?? 'login');
?>

<div class="jarvis-container">
    <?php 
    // Tampilkan welcome screen hanya jika tidak ada pesan flash atau parameter 'page'
    $show_welcome_screen = !$error_message && !$success_message && $active_page === 'login' && empty($old_input);
    if ($show_welcome_screen): 
    ?>
        <!-- Layar Selamat Datang Awal dengan transisi -->
        <div id="welcome-screen">
            <h1>Selamat Datang Di Portal Database</h1>
            <button id="enter-button" class="btn-futuristic">Masuk</button>
        </div>
    <?php endif; ?>

    <?php
        // Jika welcome screen tidak ditampilkan, langsung tampilkan form container
        $formContainerStyle = !$show_welcome_screen
            ? 'display: block; opacity: 1;' 
            : 'display: none; opacity: 0;'; 
    ?>

    <!-- Kontainer untuk Form Login dan Registrasi -->
    <div id="form-container" style="<?= $formContainerStyle ?>">
        <!-- FORM LOGIN -->
        <div id="login-form-wrapper" class="login-form">
            <h2>System Access</h2>
            <p>Authorization Required</p>

            <?php if ($error_message && $active_page !== 'register'): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): // Pesan sukses (dari registrasi) selalu tampil di form login ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=login" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn-futuristic">AUTHENTICATE</button>
            </form>
            <div class="text-center mt-3">
                <p>Pengguna baru? <a href="#" id="show-register-form">Daftar di sini</a></p>
            </div>
        </div>

        <!-- FORM REGISTRASI (disembunyikan awalnya) -->
        <div id="register-form-wrapper" class="login-form" style="display: none;">
            <h2>Create Account</h2>
            <p>Silakan isi detail di bawah ini</p>

            <?php if ($error_message && $active_page === 'register'): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="index.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label for="reg-username">Username</label>
                    <input type="text" id="reg-username" name="username" class="form-control" value="<?= htmlspecialchars($old_input['username'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="reg-confirm-password">Konfirmasi Password</label>
                    <input type="password" id="reg-confirm-password" name="confirm_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="id_jabatan">Jabatan</label>
                    <select id="id_jabatan" name="id_jabatan" class="form-control" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="0">Pengunjung (Belum ada jabatan)</option>
                        <?php foreach ($jabatan_list as $jabatan): 
                            $selected = (isset($old_input['id_jabatan']) && $old_input['id_jabatan'] == $jabatan['id_jabatan']) ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($jabatan['id_jabatan']) ?>" <?= $selected ?>>
                                <?= htmlspecialchars($jabatan['nama_jabatan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="foto_profil">Foto Profil</label>
                    <input type="file" id="foto_profil" name="foto_profil" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn-futuristic">REGISTER</button>
            </form>
            <div class="text-center mt-3">
                <p>Sudah punya akun? <a href="#" id="show-login-form">Masuk di sini</a></p>
            </div>
        </div>
    </div>

    <!-- Footer yang diposisikan di bawah -->
    <div class="login-footer">
        <p>&copy; <?= date('Y'); ?> Sistem Manajemen Data Pegawai.</p>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const enterButton = document.getElementById("enter-button");
    const welcomeScreen = document.getElementById("welcome-screen");
    const formContainer = document.getElementById("form-container");
    const loginForm = document.getElementById("login-form-wrapper");
    const registerForm = document.getElementById("register-form-wrapper");
    const showRegisterLink = document.getElementById("show-register-form");
    const showLoginLink = document.getElementById("show-login-form");

    // Cek apakah elemen-elemen ini ada (hanya ada di halaman login awal)
    if (enterButton && welcomeScreen && formContainer) {
        enterButton.addEventListener("click", function () {
            // 1. Mulai fade out welcome screen
            welcomeScreen.style.opacity = "0";
            welcomeScreen.style.pointerEvents = "none"; // Nonaktifkan interaksi saat transisi

            // 2. Setelah transisi selesai, sembunyikan welcome screen dan tampilkan form
            setTimeout(() => {
                welcomeScreen.style.display = "none";
                
                // Tampilkan kontainer form dan mulai transisi fade-in
                formContainer.style.display = "block";
                requestAnimationFrame(() => { // Memastikan browser siap untuk animasi
                    formContainer.style.opacity = "1";
                });
            }, 500); // Durasi harus cocok dengan transisi CSS (0.5s)
        });
    }

    // Fungsi untuk transisi antar form
    function switchForms(hideForm, showForm) {
        if (hideForm && showForm) {
            hideForm.style.opacity = "0";
            setTimeout(() => {
                hideForm.style.display = "none";
                showForm.style.display = "block";
                requestAnimationFrame(() => {
                    showForm.style.opacity = "1";
                });
            }, 300); // Durasi transisi lebih cepat
        }
    }

    // Event listener untuk link "Daftar di sini"
    if (showRegisterLink) {
        showRegisterLink.addEventListener("click", function(e) {
            e.preventDefault();
            switchForms(loginForm, registerForm);
        });
    }

    // Event listener untuk link "Masuk di sini"
    if (showLoginLink) {
        showLoginLink.addEventListener("click", function(e) {
            e.preventDefault();
            switchForms(registerForm, loginForm);
        });
    }

    // Jika halaman di-load dengan error registrasi, langsung tampilkan form registrasi
    <?php if ($active_page === 'register'): ?>
        // Langsung tampilkan form registrasi tanpa animasi jika halaman dimuat ulang
        if (loginForm && registerForm) {
            loginForm.style.display = 'none';
            loginForm.style.opacity = '0';
            registerForm.style.display = 'block';
            registerForm.style.opacity = '1';
        }
    <?php endif; ?>
});
</script>