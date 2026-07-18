-- Skema Database untuk Modul Jabatan
-- Digunakan untuk dokumentasi dan referensi.
-- Skema utama ada di file /config/database_schema.sql

CREATE TABLE IF NOT EXISTS `jabatan` (
  `id_jabatan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(100) NOT NULL,
  `gaji_pokok` int(11) DEFAULT 0,
  PRIMARY KEY (`id_jabatan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;