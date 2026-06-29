<?= $this->include('admin/layouts/header') ?>
<?= $this->include('admin/layouts/sidebar') ?>

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

<?= $this->include('admin/layouts/footer') ?>