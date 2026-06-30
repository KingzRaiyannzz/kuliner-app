<?= $this->extend('admin/layouts/master') ?>

<?= $this->section('content') ?>

<h2>Edit Tempat</h2>

<form method="post"
    action="<?= base_url('admin/places/update/' . $place['id']) ?>">

    <?= csrf_field() ?>

    <div class="form-group">

        <label>Nama Tempat</label>

        <input
            type="text"
            name="name"
            value="<?= esc($place['name']) ?>"
            class="form-control">

    </div>

    <br>

    <button class="btn btn-success">
        Simpan
    </button>

    <a href="<?= base_url('admin/places') ?>"
        class="btn btn-secondary">
        Batal
    </a>

</form>

<?= $this->endSection() ?>