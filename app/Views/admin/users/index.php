<?= $this->include('admin/layouts/header') ?>
<?= $this->include('admin/layouts/sidebar') ?>

<h1>Kelola User</h1>

<table>

    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
    </tr>

    <?php foreach ($users as $user): ?>

        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['role'] ?></td>
        </tr>

    <?php endforeach ?>

</table>

<?= $this->include('admin/layouts/footer') ?>