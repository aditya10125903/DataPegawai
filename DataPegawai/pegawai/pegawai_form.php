<?php
/**
 * File: pegawai_form.php
 * Deskripsi: Form untuk menambah atau mengedit data pegawai.
 *
 * Variabel yang dibutuhkan di file ini:
 * - $error (opsional): Pesan error jika ada.
 * - $pegawai (opsional): Array data pegawai untuk mode edit.
 * - $jabatan_list: Array berisi daftar jabatan dari database.
 * - $agama_list: Array berisi daftar agama dari database.
 * - $pendidikan_list: Array berisi daftar pendidikan dari database.
 */

// Inisialisasi variabel $pegawai jika tidak ada (untuk mode tambah)
$pegawai = $pegawai ?? [];
?>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php
    // Tentukan action form berdasarkan keberadaan $pegawai['id_pegawai']
    $form_action = isset($pegawai['id_pegawai']) ? 'edit.php?id=' . htmlspecialchars($pegawai['id_pegawai']) : 'tambah.php';
?>
<form action="<?= $form_action ?>" method="post" class="card-body-custom">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($pegawai['nama_lengkap'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($pegawai['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="id_jabatan">Jabatan <span class="text-danger">*</span></label>
                <select class="form-control" id="id_jabatan" name="id_jabatan" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <?php foreach ($jabatan_list as $jabatan): ?>
                        <?php $selected = (isset($pegawai['id_jabatan']) && $pegawai['id_jabatan'] == $jabatan['id_jabatan']) ? 'selected' : ''; ?>
                        <option value="<?= $jabatan['id_jabatan'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($jabatan['nama_jabatan']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" class="form-control" id="nip" name="nip" value="<?= htmlspecialchars($pegawai['nip'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="no_hp">No. HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($pegawai['no_hp'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>
                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" <?= (isset($pegawai['jenis_kelamin']) && $pegawai['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= (isset($pegawai['jenis_kelamin']) && $pegawai['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tanggal_masuk">Tanggal Masuk</label>
                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= htmlspecialchars($pegawai['tanggal_masuk'] ?? date('Y-m-d')) ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="tempat_lahir">Tempat Lahir</label>
                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= htmlspecialchars($pegawai['tempat_lahir'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= htmlspecialchars($pegawai['tanggal_lahir'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="id_agama">Agama</label>
                <select class="form-control" id="id_agama" name="id_agama">
                    <option value="">-- Pilih Agama --</option>
                    <?php foreach ($agama_list as $agama): ?>
                        <?php $selected = (isset($pegawai['id_agama']) && $pegawai['id_agama'] == $agama['id_agama']) ? 'selected' : ''; ?>
                        <option value="<?= $agama['id_agama'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($agama['nama_agama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status_pernikahan">Status Pernikahan</label>
                <select class="form-control" id="status_pernikahan" name="status_pernikahan">
                    <option value="">-- Pilih --</option>
                    <option value="Belum Menikah" <?= (isset($pegawai['status_pernikahan']) && $pegawai['status_pernikahan'] == 'Belum Menikah') ? 'selected' : '' ?>>Belum Menikah</option>
                    <option value="Menikah" <?= (isset($pegawai['status_pernikahan']) && $pegawai['status_pernikahan'] == 'Menikah') ? 'selected' : '' ?>>Menikah</option>
                    <option value="Cerai" <?= (isset($pegawai['status_pernikahan']) && $pegawai['status_pernikahan'] == 'Cerai') ? 'selected' : '' ?>>Cerai</option>
                </select>
            </div>
            <div class="form-group">
                <label for="id_pendidikan">Pendidikan Terakhir</label>
                <select class="form-control" id="id_pendidikan" name="id_pendidikan">
                    <option value="">-- Pilih Pendidikan --</option>
                    <?php foreach ($pendidikan_list as $pendidikan): ?>
                        <?php $selected = (isset($pegawai['id_pendidikan']) && $pegawai['id_pendidikan'] == $pendidikan['id_pendidikan']) ? 'selected' : ''; ?>
                        <option value="<?= $pendidikan['id_pendidikan'] ?>" <?= $selected ?>><?= htmlspecialchars($pendidikan['tingkat_pendidikan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="alamat_lengkap">Alamat Lengkap</label>
                <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3"><?= htmlspecialchars($pegawai['alamat_lengkap'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <?php if (isset($pegawai['id_pegawai'])): ?>
            <button type="submit" class="btn-futuristic"><i class="fas fa-save"></i> Update Data</button>
        <?php else: ?>
            <button type="submit" class="btn-futuristic"><i class="fas fa-plus"></i> Tambah Data</button>
        <?php endif; ?>
        <button type="reset" class="btn-futuristic-reset">Clear</button>
        <a href="index.php" class="btn-futuristic-reset" style="text-decoration: none; vertical-align: middle;">Batal</a>
    </div>
</form>