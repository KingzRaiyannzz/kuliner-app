<?= $this->extend('admin/layouts/master') ?>

<?= $this->section('content') ?>


<h1>Kelola Tag</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
    </tr>

    <?php foreach ($tags as $tag): ?>
        <tr>
            <td><?= $tag['id'] ?></td>
            <td><?= $tag['name'] ?></td>
        </tr>
    <?php endforeach ?>
</table>

<?= $this->endSection() ?>