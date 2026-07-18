<?php
/**
 * File: auth.php
 * Deskripsi: Mengelola logika otentikasi (login, register, dll).
 */
// session_start() sudah dipanggil oleh file yang meng-include (index.php -> config/session.php)

require_once 'koneksi.php';
require_once 'session.php'; // Diperlukan untuk fungsi login_user()

// Cek apakah ada aksi yang dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // Proses Login
    if ($_POST['action'] === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];
 
        $stmt = $conn->prepare("SELECT * FROM pengguna WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
 
        if ($result->num_rows === 1) { 
            $user = $result->fetch_assoc();
            // Verifikasi password dan ambil data lengkap pengguna
            if (password_verify($password, $user['password'])) {
                // Login berhasil
                try {
                    // Ambil jabatan aktif pengguna dan simpan ke sesi
                    $stmt_jabatan = $conn->prepare("
                        SELECT j.nama_jabatan 
                        FROM jabatan j
                        JOIN jabatanpegawai jp ON j.id_jabatan = jp.id_jabatan
                        JOIN pegawai pg ON jp.id_pegawai = pg.id_pegawai
                        WHERE pg.id_pengguna = ? AND jp.status = 'Aktif'
                        ORDER BY jp.tanggal_mulai DESC 
                        LIMIT 1
                    ");
                    $stmt_jabatan->bind_param("i", $user['id_pengguna']);
                    $stmt_jabatan->execute();
                    $result_jabatan = $stmt_jabatan->get_result();
                    $jabatan_data = $result_jabatan->fetch_assoc();
                    
                    // Atur nama jabatan, default ke 'Pengunjung' jika tidak ditemukan
                    $_SESSION['nama_jabatan'] = $jabatan_data['nama_jabatan'] ?? 'Pengunjung';
                } catch (mysqli_sql_exception $e) {
                    // Jika query jabatan gagal, tetap lanjutkan login dengan jabatan default
                    $_SESSION['nama_jabatan'] = 'N/A';
                }

                $_SESSION['user_data'] = $user; // Simpan semua data dari tabel 'pengguna'
                login_user($user);
                header('Location: dashboard.php');
                exit;
            }
        }
        
        // Jika login gagal
        set_flash_message('error', 'Username atau password salah.');
        header('Location: index.php');
        exit;
    }

    // Proses Registrasi (Contoh)
    if ($_POST['action'] === 'register') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $id_jabatan = filter_input(INPUT_POST, 'id_jabatan', FILTER_VALIDATE_INT);

        // Simpan input form ke sesi untuk diisi kembali jika terjadi error
        $_SESSION['form_input'] = $_POST;

        if ($password !== $confirm_password) {
            set_flash_message('error', 'Konfirmasi password tidak cocok.');
            header('Location: index.php?page=register');
            exit;
        }

        if ($id_jabatan === false || $id_jabatan < 0) { // Memperbolehkan id_jabatan = 0
            set_flash_message('error', 'Silakan pilih jabatan yang valid.');
            header('Location: index.php?page=register');
            exit;
        }

        // Hapus password dari data yang disimpan agar tidak terisi otomatis lagi
        unset($_SESSION['form_input']['password']);
        unset($_SESSION['form_input']['confirm_password']);

        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT id_pengguna FROM pengguna WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            set_flash_message('error', 'Username sudah digunakan.');
            header('Location: index.php?page=register');
            exit;
        }
        $stmt->close();

        // --- Mulai Transaksi Database ---
        $conn->begin_transaction();
        try {
            // 4. Handle Upload Foto Profil
            $nama_file_foto = 'default.png'; // Default jika tidak ada foto
            if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
                $foto = $_FILES['foto_profil'];
                // Path relatif dari file auth.php ini, menuju ke root lalu ke assets
                $upload_dir = __DIR__ . '/../assets/img/profil/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $file_extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
 
                if (in_array(strtolower($file_extension), $allowed_extensions)) {
                    // Buat nama file unik untuk menghindari penimpaan
                    $nama_file_foto = uniqid($username . '_', true) . '.' . $file_extension;
                    $lokasi_file_upload = $upload_dir . $nama_file_foto;

                    if (!move_uploaded_file($foto['tmp_name'], $lokasi_file_upload)) {
                        throw new Exception("Gagal memindahkan file yang di-upload.");
                    }
                }
            }

            // 1. Buat pengguna baru terlebih dahulu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt_pengguna = $conn->prepare("INSERT INTO pengguna (username, password) VALUES (?, ?)");
            $stmt_pengguna->bind_param("ss", $username, $hashed_password);
            $stmt_pengguna->execute();
            $id_pengguna_baru = $conn->insert_id;
            $stmt_pengguna->close();

            // 2. Buat pegawai baru yang terhubung dengan pengguna
            // Menggunakan username sebagai nama lengkap sementara dan menyimpan foto profil
            $stmt_pegawai = $conn->prepare("INSERT INTO pegawai (id_pengguna, nama_lengkap, email, tanggal_masuk, status_pegawai, foto_pegawai) VALUES (?, ?, ?, CURDATE(), 'Aktif', ?)");
            $stmt_pegawai->bind_param("isss", $id_pengguna_baru, $username, $username, $nama_file_foto);
            $stmt_pegawai->execute();
            $id_pegawai_baru = $conn->insert_id;
            $stmt_pegawai->close();

            // 3. Tetapkan jabatan untuk pegawai baru, hanya jika jabatan dipilih (bukan 'Pengunjung' dengan id_jabatan > 0)
            if ($id_jabatan > 0) {
                $stmt_jabatan = $conn->prepare("INSERT INTO jabatanpegawai (id_pegawai, id_jabatan, tanggal_mulai, status) VALUES (?, ?, NOW(), 'Aktif')");
                $stmt_jabatan->bind_param("ii", $id_pegawai_baru, $id_jabatan);
                $stmt_jabatan->execute();
                $stmt_jabatan->close();
            }

            // Jika semua berhasil, commit transaksi
            $conn->commit();
            
            set_flash_message('success', 'Registrasi berhasil! Silakan masuk.');
            unset($_SESSION['form_input']); // Hapus data input form dari sesi
            header('Location: index.php?page=login');
            exit;

        } catch (mysqli_sql_exception $exception) {
            // Jika ada error, rollback semua perubahan
            $conn->rollback();
            
            // Log error dan tampilkan pesan umum
            error_log("Registrasi Gagal: " . $exception->getMessage());
            set_flash_message('error', 'Terjadi kesalahan pada sistem saat registrasi. Silakan coba lagi.');
            // Arahkan kembali ke form registrasi agar pesan error dapat ditampilkan di tempat yang benar.
            header('Location: index.php?page=register');
            exit;
        }
    }
}
?>