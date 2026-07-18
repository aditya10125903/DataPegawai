<?php
/**
 * File: export_excel.php
 * Deskripsi: Mengekspor data pegawai ke format Excel.
 */

require_once '../config/session.php';
require_once '../config/koneksi.php';

if (!is_user_logged_in()) {
    header("Location: ../index.php");
    exit();
}

// Nama file excel
$filename = "data_pegawai_" . date('Ymd') . ".xls";

// Set header untuk download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

$query = "SELECT nip, nama_lengkap, email, no_hp, status_pegawai, tanggal_masuk, pendidikan_terakhir FROM pegawai ORDER BY nama_lengkap ASC";
$result = $conn->query($query);

// Header tabel
$header = "NIP\tNama Lengkap\tEmail\tNo. HP\tStatus\tTanggal Masuk\tPendidikan\n";

$data = "";
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rowData = [
            $row['nip'] ?? 'N/A',
            $row['nama_lengkap'],
            $row['email'],
            $row['no_hp'],
            $row['status_pegawai'],
            $row['tanggal_masuk'],
            $row['pendidikan_terakhir']
        ];
        // Gabungkan data baris dengan tab sebagai pemisah
        $data .= implode("\t", array_values($rowData)) . "\n";
    }
}

// Output header dan data
echo $header;
echo $data;
exit();