<?= $this->include('admin/layouts/header') ?>
<?= $this->include('admin/layouts/sidebar') ?>

<h1>Kelola Review</h1>

<table>

    <tr>
        <th>ID</th>
        <th>Rating</th>
        <th>Komentar</th>
    </tr>

    <?php foreach ($reviews as $review): ?>

        <tr>
            <td><?= $review['id'] ?></td>
            <td><?= $review['rating'] ?></td>
            <td><?= $review['comment'] ?></td>
        </tr>

    <?php endforeach ?>

</table>

<?= $this->include('admin/layouts/footer') ?>