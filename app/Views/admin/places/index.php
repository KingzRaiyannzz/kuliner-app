<?= $this->extend('admin/layouts/master') ?>

<?= $this->section('content') ?>

<h2>Kelola Tempat</h2>

<table>

    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($places as $place): ?>

            <tr>

                <td><?= $place['id'] ?></td>

                <td><?= esc($place['name']) ?></td>

                <td>

                    <?php if ($place['is_verified']) : ?>

                        Verified

                    <?php else: ?>

                        Pending

                    <?php endif; ?>

                </td>

                <td>

                    <a href="<?= base_url('admin/places/edit/' . $place['id']) ?>"
                        class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <form
                        action="<?= base_url('admin/places/' . $place['id'] . '/delete') ?>"
                        method="post"
                        style="display:inline;">

                        <?= csrf_field() ?>

                        <button
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus data?')">

                            Hapus

                        </button>

                    </form>

                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

<?= $this->endSection() ?>