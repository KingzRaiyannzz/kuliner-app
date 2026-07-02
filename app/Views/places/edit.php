<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tempat - Kuliner App</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .page-header {
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            padding: 14px 24px;
        }

        .page-header a {
            color: #1D9E75;
            text-decoration: none;
            font-size: 14px;
        }

        .wrap {
            max-width: 760px;
            margin: 28px auto 60px;
            padding: 0 16px;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 22px;
        }

        h1 {
            margin: 0 0 18px;
            font-size: 24px;
        }

        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 14px;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        label {
            display: block;
            margin: 14px 0 6px;
            font-size: 13px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            font: inherit;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #1D9E75;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        .check-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .check-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e5e5e5;
            border-radius: 999px;
            padding: 7px 10px;
            font-size: 13px;
            background: #fafafa;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            border: 0;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: #1D9E75;
            color: #fff;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #555;
        }

        .current-photo {
            width: 100%;
            max-height: 220px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e5e5e5;
            margin-top: 8px;
        }

        .field-hint {
            color: #888;
            font-size: 12px;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div class="page-header">
        <a href="/places/<?= (int) $place['id'] ?>">Kembali ke detail</a>
    </div>

    <main class="wrap">
        <div class="card">
            <h1>Edit Tempat</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?= esc($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="/places/<?= (int) $place['id'] ?>/update" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <label for="name">Nama tempat</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?= esc($old['name'] ?? $place['name']) ?>"
                    required>

                <label for="description">Deskripsi</label>
                <textarea id="description" name="description"><?= esc($old['description'] ?? $place['description'] ?? '') ?></textarea>

                <label for="address">Alamat</label>
                <input
                    type="text"
                    id="address"
                    name="address"
                    value="<?= esc($old['address'] ?? $place['address']) ?>"
                    required>

                <div class="grid">
                    <div>
                        <label for="latitude">Latitude</label>
                        <input
                            type="text"
                            id="latitude"
                            name="latitude"
                            value="<?= esc($old['latitude'] ?? $place['latitude']) ?>"
                            required>
                    </div>
                    <div>
                        <label for="longitude">Longitude</label>
                        <input
                            type="text"
                            id="longitude"
                            name="longitude"
                            value="<?= esc($old['longitude'] ?? $place['longitude']) ?>"
                            required>
                    </div>
                </div>

                <?php if (!empty($categories)): ?>
                    <label>Kategori</label>
                    <div class="check-row">
                        <?php foreach ($categories as $category): ?>
                            <?php $checked = in_array($category['id'], $old['categories'] ?? $selectedCategories, true); ?>
                            <label class="check-item">
                                <input
                                    type="checkbox"
                                    name="categories[]"
                                    value="<?= (int) $category['id'] ?>"
                                    <?= $checked ? 'checked' : '' ?>>
                                <span><?= esc($category['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($tags)): ?>
                    <label>Tag</label>
                    <div class="check-row">
                        <?php foreach ($tags as $tag): ?>
                            <?php $checked = in_array($tag['id'], $old['tags'] ?? $selectedTags, true); ?>
                            <label class="check-item">
                                <input
                                    type="checkbox"
                                    name="tags[]"
                                    value="<?= (int) $tag['id'] ?>"
                                    <?= $checked ? 'checked' : '' ?>>
                                <span>#<?= esc($tag['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <label for="thumbnail">Foto tempat</label>
                <?php if (!empty($place['thumbnail'])): ?>
                    <img
                        src="/<?= esc($place['thumbnail']) ?>"
                        class="current-photo"
                        alt="<?= esc($place['name']) ?>">
                <?php endif; ?>
                <input
                    type="file"
                    id="thumbnail"
                    name="thumbnail"
                    accept="image/jpeg,image/png,image/webp">
                <div class="field-hint">Kosongkan jika tidak ingin mengganti foto. Maksimal 2MB, format JPG/PNG/WebP.</div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="/places/<?= (int) $place['id'] ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>