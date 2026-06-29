<?= $this->include('admin/layouts/header') ?>
<?= $this->include('admin/layouts/sidebar') ?>

<h1>Kelola Tempat</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama Tempat</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($places as $place): ?>
        <tr>
            <td><?= $place['id'] ?></td>
            <td><?= $place['name'] ?></td>
            <td>
                <?= $place['is_verified'] ? 'Verified' : 'Pending' ?>
            </td>
            <td>
                Verifikasi | Hapus
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?= $this->include('admin/layouts/footer') ?>