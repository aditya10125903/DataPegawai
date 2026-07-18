--
-- Struktur dari tabel `jabatanpegawai`
-- Tabel ini menghubungkan antara pegawai dan jabatan (riwayat jabatan).
--

CREATE TABLE IF NOT EXISTS `jabatanpegawai` (
  `id_jabatan_pegawai` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) DEFAULT NULL,
  `id_jabatan` int(11) DEFAULT NULL,
  `id_divisi` int(11) DEFAULT NULL,
  `id_departemen` int(11) DEFAULT NULL,
  `id_golongan` int(11) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('Aktif','Riwayat') DEFAULT 'Aktif',
  PRIMARY KEY (`id_jabatan_pegawai`),
  KEY `id_pegawai` (`id_pegawai`),
  KEY `id_jabatan` (`id_jabatan`),
  KEY `id_divisi` (`id_divisi`),
  KEY `id_departemen` (`id_departemen`),
  KEY `id_golongan` (`id_golongan`),
  CONSTRAINT `jabatanpegawai_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE,
  CONSTRAINT `jabatanpegawai_ibfk_2` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE CASCADE,
  CONSTRAINT `jabatanpegawai_ibfk_3` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`) ON DELETE SET NULL,
  CONSTRAINT `jabatanpegawai_ibfk_4` FOREIGN KEY (`id_departemen`) REFERENCES `departemen` (`id_departemen`) ON DELETE SET NULL,
  CONSTRAINT `jabatanpegawai_ibfk_5` FOREIGN KEY (`id_golongan`) REFERENCES `golongan` (`id_golongan`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;