<?php
/**
 * File: cetak.php | Modul: Jabatan
 * Deskripsi: Halaman untuk mencetak daftar jabatan dalam format PDF.
 * Catatan: File ini memerlukan library seperti TCPDF atau Dompdf.
 * Contoh ini menggunakan output HTML sederhana untuk demonstrasi.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$query = "SELECT id_jabatan, nama_jabatan, gaji_pokok FROM jabatan ORDER BY nama_jabatan";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Jabatan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body onload="window.print()">

    <h1>Data Jabatan</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Jabatan</th>
                <th>Gaji Pokok</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_jabatan']) ?></td>
                    <td><?= htmlspecialchars($row['nama_jabatan']) ?></td>
                    <td>Rp <?= number_format($row['gaji_pokok'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" style="text-align: center;">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>