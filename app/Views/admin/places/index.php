<?= $this->extend('admin/layouts/master') ?>

<?= $this->section('content') ?>

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
                <?php if (!$place['is_verified']) : ?>
        <a href="#" class="btn btn-success">
            Verifikasi
        </a>
    <?php endif; ?>

    <form action="<?= base_url('admin/places/' . $place['id'] . '/delete') ?>"
      method="post"
      style="display:inline;">

    <?= csrf_field() ?>

    <button type="submit"
            onclick="return confirm('Yakin ingin menghapus tempat ini?')">
        Hapus
    </button>

    </form>

    </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


<?= $this->endSection() ?>