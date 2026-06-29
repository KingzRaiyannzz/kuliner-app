<?= $this->extend('admin/layouts/master') ?>

<?= $this->section('content') ?>

<h1>Kelola Kategori</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
    </tr>

    <?php foreach ($categories as $category): ?>
        <tr>
            <td><?= $category['id'] ?></td>
            <td><?= $category['name'] ?></td>
        </tr>
    <?php endforeach ?>
</table>

<?= $this->endSection() ?>