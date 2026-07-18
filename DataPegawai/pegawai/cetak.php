<?php
/**
 * File: cetak.php
 * Deskripsi: Halaman untuk mencetak daftar pegawai.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$page_title = 'Cetak Data Pegawai';

$query = "SELECT nip, nama_lengkap, email, no_hp, status_pegawai FROM pegawai ORDER BY nama_lengkap ASC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <h1>Laporan Data Pegawai</h1>
    <p>Tanggal Cetak: <?= date('d F Y H:i:s') ?></p>
    <hr>

    <table>
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0):
                while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nip'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['no_hp']) ?></td>
                    <td><?= htmlspecialchars($row['status_pegawai']) ?></td>
                </tr>
                <?php endwhile;
            else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script> window.print(); </script>
</body>
</html>