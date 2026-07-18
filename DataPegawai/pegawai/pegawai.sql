-- Skema Database untuk Modul Pegawai
-- Skema ini diselaraskan dengan skema utama di /config/database_schema.sql

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
  `status_pernikahan` enum('Belum Menikah','Menikah','Cerai Hidup','Cerai Mati') DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `status_pegawai` enum('Aktif','Tidak Aktif','Cuti','Resign') DEFAULT 'Aktif',
  `foto_pegawai` varchar(255) DEFAULT NULL,
  `npwp` varchar(25) DEFAULT NULL,
  `rekening_bank` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_pegawai`),
  UNIQUE KEY `nip` (`nip`),
  UNIQUE KEY `email` (`email`),
  KEY `id_pengguna` (`id_pengguna`),
  KEY `id_agama` (`id_agama`),
  KEY `id_pendidikan` (`id_pendidikan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;