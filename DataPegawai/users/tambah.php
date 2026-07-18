<?php
/**
 * File: tambah.php | Modul: Users
 * Deskripsi: Halaman untuk menambah pengguna sistem baru.
 */
require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Tambah Pengguna';
$base_path = '../';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO pengguna (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);
        
        if ($stmt->execute()) {
            header("Location: index.php?status=tambah_sukses");
            exit();
        } else {
            // Cek jika error karena username duplikat
            if ($conn->errno == 1062) {
                $error = "Gagal menyimpan: Username '{$username}' sudah digunakan.";
            } else {
                $error = "Gagal menyimpan data: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

require_once '../includes/header.php';
?>

<div class="container-fluid">
    <?php require_once '../includes/sidebar.php'; ?>
    <main class="main-content">
        <h1>Tambah Pengguna Baru</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="tambah.php" method="post">
            <div class="form-group"><label for="username">Username:</label><input type="text" class="form-control" id="username" name="username" required></div>
            <div class="form-group"><label for="password">Password:</label><input type="password" class="form-control" id="password" name="password" required></div>
            <div class="form-group"><label for="confirm_password">Konfirmasi Password:</label><input type="password" class="form-control" id="confirm_password" name="confirm_password" required></div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </main>
</div>

<?php
require_once '../includes/footer.php';
?>