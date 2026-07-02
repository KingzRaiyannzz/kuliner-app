<?= $this->extend('admin/layouts/master') ?>

<?= $this->section('content') ?>

<div class="card">

    <h2>Kelola Review</h2>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Tempat</th>
                <th>User</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php if (empty($reviews)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;">Belum ada review</td>
                </tr>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= esc($review['id']) ?></td>
                        <td><?= esc($review['place_name'] ?? '-') ?></td>
                        <td><?= esc($review['user_name'] ?? '-') ?></td>
                        <td><?= esc($review['rating']) ?></td>
                        <td><?= esc($review['comment'] ?? '-') ?></td>
                        <td><?= esc($review['created_at'] ?? '-') ?></td>
                        <td>
                            <form
                                action="<?= base_url('admin/reviews/' . $review['id'] . '/delete') ?>"
                                method="post"
                                style="display:inline;">

                                <?= csrf_field() ?>

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus review ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif; ?>
        </tbody>

    </table>

</div>

<?= $this->endSection() ?>