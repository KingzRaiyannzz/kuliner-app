<?= $this->extend('admin/layouts/master') ?>

<?= $this->section('content') ?>

<style>
    .edit-place-card {
        max-width: 860px;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 22px;
    }

    .edit-place-card h2 {
        margin: 0 0 18px;
        font-size: 24px;
    }

    .edit-place-card label {
        display: block;
        margin: 14px 0 6px;
        font-size: 13px;
        font-weight: 600;
        color: #555;
    }

    .edit-place-card input[type="text"],
    .edit-place-card textarea {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 12px;
        font: inherit;
    }

    .edit-place-card textarea {
        min-height: 120px;
        resize: vertical;
    }

    .edit-place-card input:focus,
    .edit-place-card textarea:focus {
        outline: none;
        border-color: #1D9E75;
    }

    .edit-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
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

    .edit-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .edit-actions .btn {
        border: 0;
        border-radius: 8px;
        padding: 11px 14px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
    }

    .btn-save {
        background: #1D9E75;
        color: #fff;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #555;
    }

    .form-alert {
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 14px;
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    @media (max-width: 640px) {
        .edit-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="edit-place-card">
    <h2>Edit Tempat</h2>

    <?php if (!empty($errors)): ?>
        <div class="form-alert">
            <?php foreach ($errors as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/places/update/' . $place['id']) ?>">
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

        <div class="edit-grid">
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
            <?php
            $activeCategories = array_map('strval', $old['categories'] ?? $selectedCategories);
            ?>
            <label>Kategori</label>
            <div class="check-row">
                <?php foreach ($categories as $category): ?>
                    <label class="check-item">
                        <input
                            type="checkbox"
                            name="categories[]"
                            value="<?= (int) $category['id'] ?>"
                            <?= in_array((string) $category['id'], $activeCategories, true) ? 'checked' : '' ?>>
                        <span><?= esc($category['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($tags)): ?>
            <?php
            $activeTags = array_map('strval', $old['tags'] ?? $selectedTags);
            ?>
            <label>Tag</label>
            <div class="check-row">
                <?php foreach ($tags as $tag): ?>
                    <label class="check-item">
                        <input
                            type="checkbox"
                            name="tags[]"
                            value="<?= (int) $tag['id'] ?>"
                            <?= in_array((string) $tag['id'], $activeTags, true) ? 'checked' : '' ?>>
                        <span>#<?= esc($tag['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="edit-actions">
            <button class="btn btn-save" type="submit">Simpan Perubahan</button>
            <a href="<?= base_url('admin/places') ?>" class="btn btn-cancel">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
