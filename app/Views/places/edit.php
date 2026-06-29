<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Tempat Kuliner</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #0d6efd;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #842029;
        }
    </style>
</head>

<body>

    <h2>Edit Tempat Kuliner</h2>

    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('places/' . $place['id'] . '/update') ?>"
        method="post">

        <?= csrf_field() ?>

        <label>Nama Tempat</label>
        <input type="text"
            name="name"
            value="<?= old('name', $place['name']) ?>"
            required>

        <label>Deskripsi</label>
        <textarea name="description"
            rows="5"><?= old('description', $place['description']) ?></textarea>

        <label>Alamat</label>
        <input type="text"
            name="address"
            value="<?= old('address', $place['address']) ?>"
            required>

        <label>Latitude</label>
        <input type="text"
            name="latitude"
            value="<?= old('latitude', $place['latitude']) ?>"
            required>

        <label>Longitude</label>
        <input type="text"
            name="longitude"
            value="<?= old('longitude', $place['longitude']) ?>"
            required>

        <button type="submit" class="btn btn-primary">
            Simpan Perubahan
        </button>

    </form>

    <hr>

    <form action="<?= base_url('places/' . $place['id'] . '/delete') ?>"
        method="post"
        onsubmit="return confirm('Yakin ingin menghapus tempat ini?')">

        <?= csrf_field() ?>

        <button type="submit" class="btn btn-danger">
            Hapus Tempat
        </button>

    </form>

    <p>
        <a href="<?= base_url('places/' . $place['id']) ?>">
            ← Kembali ke Detail Tempat
        </a>
    </p>

</body>

</html>