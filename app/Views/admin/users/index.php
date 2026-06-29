<?= $this->extend('admin/layouts/master') ?>

<?= $this->section('content') ?>

<div class="card">

    <h2>Kelola User</h2>

    <table>

        <tr>

            <th>ID</th>

            <th>Nama</th>

            <th>Email</th>

        </tr>

        <?php foreach ($users as $user): ?>

            <tr>

                <td><?= $user['id'] ?></td>

                <td><?= esc($user['name']) ?></td>

                <td><?= esc($user['email']) ?></td>

            </tr>

        <?php endforeach; ?>

    </table>

</div>

<?= $this->endSection() ?>