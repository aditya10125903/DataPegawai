-- Skema Database Terpusat untuk Proyek DataPegawai
-- Skema Database Terpusat untuk Proyek DataPegawai (Versi Sinkron)
-- Database: db_pegawai

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- =================================================================================
-- Tabel Master
-- =================================================================================
-- Struktur dari tabel `pengguna`
-- Tabel ini menyimpan data login untuk pengguna sistem.
--

CREATE TABLE IF NOT EXISTS `pengguna` (
  `id_pengguna` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `agama` (
  `id_agama` int(11) NOT NULL AUTO_INCREMENT,
  `nama_agama` varchar(50) NOT NULL,
  PRIMARY KEY (`id_agama`),
  UNIQUE KEY `nama_agama` (`nama_agama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pendidikan` (
  `id_pendidikan` int(11) NOT NULL AUTO_INCREMENT,
  `tingkat_pendidikan` varchar(50) NOT NULL,
  PRIMARY KEY (`id_pendidikan`),
  UNIQUE KEY `tingkat_pendidikan` (`tingkat_pendidikan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `jabatan` (
  `id_jabatan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(100) NOT NULL,
  `gaji_pokok` decimal(15,2) DEFAULT 0.00,
  PRIMARY KEY (`id_jabatan`),
  UNIQUE KEY `nama_jabatan` (`nama_jabatan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `divisi` (
  `id_divisi` int(11) NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(100) NOT NULL,
  PRIMARY KEY (`id_divisi`),
  UNIQUE KEY `nama_divisi` (`nama_divisi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- =================================================================================
-- Tabel Utama
-- =================================================================================
CREATE TABLE IF NOT EXISTS `pegawai` (
  `id_pegawai` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengguna` int(11) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `id_agama` int(11) DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_pendidikan` int(11) DEFAULT NULL,
  `status_pernikahan` varchar(50) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `status_pegawai` enum('Aktif','Tidak Aktif','Cuti','Resign') NOT NULL DEFAULT 'Aktif',
  `foto_pegawai` varchar(255) DEFAULT 'default.png',
  PRIMARY KEY (`id_pegawai`),
  UNIQUE KEY `nip` (`nip`),
  UNIQUE KEY `email` (`email`),
  KEY `id_pengguna` (`id_pengguna`),
  KEY `id_agama` (`id_agama`),
  KEY `id_pendidikan` (`id_pendidikan`),
  CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pegawai_ibfk_2` FOREIGN KEY (`id_agama`) REFERENCES `agama` (`id_agama`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `pegawai_ibfk_3` FOREIGN KEY (`id_pendidikan`) REFERENCES `pendidikan` (`id_pendidikan`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- =================================================================================
-- Tabel Transaksional & Riwayat
-- =================================================================================
--

CREATE TABLE IF NOT EXISTS `presensi` (
  `id_presensi` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) DEFAULT NULL,
  `waktu_masuk` datetime DEFAULT NULL,
  `waktu_keluar` datetime DEFAULT NULL,
  `status` enum('Hadir','Izin','Sakit','Cuti','Alpa') NOT NULL,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id_presensi`),
  KEY `idx_presensi_pegawai` (`id_pegawai`),
  CONSTRAINT `presensi_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cuti` (
  `id_cuti` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jenis_cuti` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status_pengajuan` enum('Diajukan','Disetujui','Ditolak') DEFAULT 'Diajukan',
  PRIMARY KEY (`id_cuti`),
  KEY `idx_cuti_pegawai` (`id_pegawai`),
  CONSTRAINT `cuti_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lembur` (
  `id_lembur` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('Diajukan','Disetujui','Ditolak') DEFAULT 'Diajukan',
  PRIMARY KEY (`id_lembur`),
  KEY `idx_lembur_pegawai` (`id_pegawai`),
  CONSTRAINT `lembur_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
CREATE TABLE IF NOT EXISTS `penggajian` (
  `id_gaji` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) DEFAULT NULL,
  `periode_bulan` int(11) NOT NULL,
  `periode_tahun` int(11) NOT NULL,
  `gaji_pokok` decimal(15,2) DEFAULT 0.00,
  `total_tunjangan` decimal(15,2) DEFAULT 0.00,
  `total_lembur` decimal(15,2) DEFAULT 0.00,
  `total_potongan` decimal(15,2) DEFAULT 0.00,
  `gaji_bersih` decimal(15,2) DEFAULT 0.00,
  `tanggal_pembayaran` date DEFAULT NULL,
  PRIMARY KEY (`id_gaji`),  
  UNIQUE KEY `uq_gaji_pegawai_periode` (`id_pegawai`,`periode_bulan`,`periode_tahun`),
  CONSTRAINT `penggajian_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `jabatanpegawai` (
  `id_jabatan_pegawai` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `id_divisi` int(11) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('Aktif','Riwayat') NOT NULL DEFAULT 'Aktif',
  PRIMARY KEY (`id_jabatan_pegawai`),
  KEY `id_pegawai` (`id_pegawai`),
  KEY `id_jabatan` (`id_jabatan`),
  CONSTRAINT `jabatanpegawai_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE,
  CONSTRAINT `jabatanpegawai_ibfk_2` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `proyek` (
  `id_proyek` int(11) NOT NULL AUTO_INCREMENT,
  `nama_proyek` varchar(255) NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_proyek`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tugas` (
  `id_tugas` int(11) NOT NULL AUTO_INCREMENT,
  `nama_tugas` varchar(255) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_tugas`),
  KEY `idx_tugas_pegawai` (`id_pegawai`),
  CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `meeting` (
  `id_meeting` int(11) NOT NULL AUTO_INCREMENT,
  `topik_meeting` varchar(255) NOT NULL,
  `waktu_meeting` datetime DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_meeting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `surat` (
  `id_surat` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(100) NOT NULL,
  `perihal` varchar(255) NOT NULL,
  `tanggal_surat` date NOT NULL,
  `jenis_surat` varchar(100) DEFAULT NULL,
  `file_surat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_surat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- =================================================================================
-- Data Awal (Seed Data)
-- =================================================================================
INSERT INTO `agama` (`id_agama`, `nama_agama`) VALUES (1, 'Islam'), (2, 'Kristen Protestan'), (3, 'Katolik'), (4, 'Hindu'), (5, 'Buddha'), (6, 'Khonghucu');
INSERT INTO `pendidikan` (`id_pendidikan`, `tingkat_pendidikan`) VALUES (1, 'SMA/SMK'), (2, 'D3'), (3, 'S1'), (4, 'S2'), (5, 'S3');

COMMIT;