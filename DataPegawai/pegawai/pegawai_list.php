<?php
/**
 * File: pegawai_list.php
 * Deskripsi: Menampilkan daftar pegawai dalam format baris tabel.
 */

if ($result && $result->num_rows > 0):
    while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['nip'] ?? 'N/A') ?></td>
        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['no_hp']) ?></td>
        <td>
            <span class="badge badge-<?= $row['status_pegawai'] == 'Aktif' ? 'success' : 'secondary' ?>">
                <?= htmlspecialchars($row['status_pegawai']) ?>
            </span>
        </td>
        <td>
            <a href="detail.php?id=<?= $row['id_pegawai'] ?>" class="btn btn-info btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
            <a href="edit.php?id=<?= $row['id_pegawai'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
            <a href="hapus.php?id=<?= $row['id_pegawai'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
        </td>
    </tr>
    <?php endwhile;
else: ?>
    <tr>
        <td colspan="6" class="text-center">Tidak ada data pegawai.</td>
    </tr>
<?php endif; ?>