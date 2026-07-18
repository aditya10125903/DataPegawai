<aside class="sidebar">
    <div class="sidebar-header">
        <h3>SYSTEM NAVIGATION</h3>
    </div>
    <nav class="nav flex-column">
        <a class="nav-link active" href="<?= $base_path ?? './' ?>dashboard.php"><i class="fas fa-tachometer-alt fa-fw mr-2"></i>Dashboard</a>

        <!-- Grup Data Master -->
        <div class="nav-group">
            <a class="nav-link" href="#"><i class="fas fa-database fa-fw mr-2"></i>Data Master</a>
            <div class="nav-group-items">
                <a class="nav-link" href="<?= $base_path ?? './' ?>pegawai/index.php">Data Pegawai</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>jabatan/index.php">Data Jabatan</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>divisi/index.php">Data Divisi</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>departemen/index.php">Data Departemen</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>cabang/index.php">Data Cabang</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>golongan/index.php">Data Golongan</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>pendidikan/index.php">Tingkat Pendidikan</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>agama/index.php">Data Agama</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>status_pegawai/index.php">Status Pegawai</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>status_nikah/index.php">Status Pernikahan</a>
            </div>
        </div>

        <!-- Grup Transaksi -->
        <div class="nav-group">
            <a class="nav-link" href="#"><i class="fas fa-exchange-alt fa-fw mr-2"></i>Transaksi</a>
            <div class="nav-group-items">
                <a class="nav-link" href="<?= $base_path ?? './' ?>presensi/index.php">Presensi</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>cuti/index.php">Cuti</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>lembur/index.php">Lembur</a>
            </div>
        </div>

        <!-- Grup Manajemen SDM -->
        <div class="nav-group">
            <a class="nav-link" href="#"><i class="fas fa-users-cog fa-fw mr-2"></i>Manajemen SDM</a>
            <div class="nav-group-items">
                <a class="nav-link" href="<?= $base_path ?? './' ?>jabatanpegawai/index.php">Riwayat Jabatan</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>mutasi/index.php">Mutasi</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>promosi/index.php">Promosi</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>peringatan/index.php">Surat Peringatan</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>resign/index.php">Pengajuan Resign</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>pensiun/index.php">Data Pensiun</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>rekrutmen/pelamar.php">Rekrutmen</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>penilaian/kinerja.php">Penilaian Kinerja</a>
            </div>
        </div>

        <!-- Grup Manajemen Proyek & Tugas -->
        <div class="nav-group">
            <a class="nav-link" href="#"><i class="fas fa-tasks fa-fw mr-2"></i>Proyek & Tugas</a>
            <div class="nav-group-items">
                <a class="nav-link" href="<?= $base_path ?? './' ?>proyek/index.php">Manajemen Proyek</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>tugas/index.php">Manajemen Tugas</a>
            </div>
        </div>

        <!-- Grup Penggajian -->
        <div class="nav-group">
            <a class="nav-link" href="#"><i class="fas fa-money-bill-wave fa-fw mr-2"></i>Penggajian</a>
            <div class="nav-group-items">
                <a class="nav-link" href="<?= $base_path ?? './' ?>penggajian/index.php">Proses Gaji</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>tunjangan/index.php">Master Tunjangan</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>potongan/index.php">Master Potongan</a>
            </div>
        </div>

        <!-- Grup Administrasi Kantor -->
        <div class="nav-group">
            <a class="nav-link" href="#"><i class="fas fa-briefcase fa-fw mr-2"></i>Administrasi</a>
            <div class="nav-group-items">
                <a class="nav-link" href="<?= $base_path ?? './' ?>surat/index.php"><i class="fas fa-envelope-open-text fa-fw mr-2"></i>Manajemen Surat</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>meeting/index.php">Jadwal Meeting</a>
            </div>
        </div>

        <!-- Grup Inventaris -->
        <div class="nav-group">
            <a class="nav-link" href="#"><i class="fas fa-boxes fa-fw mr-2"></i>Inventaris</a>
            <div class="nav-group-items">
                <a class="nav-link" href="<?= $base_path ?? './' ?>inventaris/index.php">Aset Kantor</a>
            </div>
        </div>

        <!-- Grup Laporan -->
        <div class="nav-group">
            <a class="nav-link" href="#"><i class="fas fa-chart-line fa-fw mr-2"></i>Laporan</a>
            <div class="nav-group-items">
                <a class="nav-link" href="<?= $base_path ?? './' ?>laporan/pegawai.php">Laporan Data Pegawai</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>penggajian/laporan.php">Laporan Gaji</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>laporan/presensi.php">Laporan Presensi</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>laporan/cuti.php">Laporan Cuti</a>
                <a class="nav-link" href="<?= $base_path ?? './' ?>laporan/kinerja.php">Laporan Kinerja</a>
            </div>
        </div>

        <!-- Grup Pengaturan -->
        <div class="nav-group">
            <a class="nav-link" href="#"><i class="fas fa-cogs fa-fw mr-2"></i>Pengaturan</a>
            <div class="nav-group-items">
                <a class="nav-link" href="<?= $base_path ?? './' ?>users/index.php">Manajemen Pengguna</a>
            </div>
        </div>

        <!-- Logout -->
        <a class="nav-link" href="<?= $base_path ?? './' ?>logout.php"><i class="fas fa-sign-out-alt fa-fw mr-2"></i>Logout</a>
    </nav>
</aside>