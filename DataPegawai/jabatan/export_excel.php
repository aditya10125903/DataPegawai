<?php
/**
 * File: export_excel.php | Modul: Jabatan
 * Deskripsi: Skrip untuk mengekspor data jabatan ke format Excel (CSV).
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

$query = "SELECT id_jabatan, nama_jabatan, gaji_pokok FROM jabatan ORDER BY id_jabatan";
$result = $conn->query($query);

// Nama file dan header
$filename = "data_jabatan_" . date('Ymd') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Buat file pointer
$output = fopen('php://output', 'w');

// Tulis header kolom
fputcsv($output, array('ID Jabatan', 'Nama Jabatan', 'Gaji Pokok'));

// Tulis data baris
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
exit();

?>