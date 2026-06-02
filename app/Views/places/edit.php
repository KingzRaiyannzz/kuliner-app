<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?= esc($place['name']) ?> — Kuliner App</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .page-header {
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header a {
            color: #1D9E75;
            text-decoration: none;
            font-size: 14px;
        }

        .container {
            max-width: 820px;
            margin: 32px auto;
            padding: 0 16px 60px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #dc2626;
            margin-bottom: 16px;
        }

        .alert-error p {
            margin: 2px 0;
        }

        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .form-group {
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }

        label .req {
            color: #e53e3e;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            transition: border-color .2s;
            font-family: inherit;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #1D9E75;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        input[readonly] {
            background: #f9fafb;
            color: #666;
            cursor: not-allowed;
        }

        .coord-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        #map-pick {
            width: 100%;
            height: 280px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            margin-top: 10px;
        }

        .map-hint {
            font-size: 12px;
            color: #888;
            text-align: center;
            margin-top: 5px;
        }

        .checkbox-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .checkbox-item {
            display: none;
        }

        .checkbox-label {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            border: 1px solid #d1d5db;
            color: #555;
            transition: all .15s;
        }

        .checkbox-item:checked+.checkbox-label {
            background: #1D9E75;
            border-color: #1D9E75;
            color: #fff;
        }

        .thumb-preview {
            margin-top: 8px;
        }

        .thumb-preview img {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
        }

        .btn-row {
            display: flex;
            gap: 10px;
        }

        .btn-submit {
            flex: 1;
            padding: 11px;
            background: #1D9E75;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-submit:hover {
            background: #0F6E56;
        }

        .btn-cancel {
            padding: 11px 20px;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #555;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .btn-cancel:hover {
            background: #f9fafb;
        }

        .search-wrapper {
            position: relative;
        }

        #autocomplete-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 999;
            background: #fff;
            border: 1px solid #d1d5db;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }

        .auto-item {
            padding: 9px 14px;
            font-size: 13px;
            cursor: pointer;
            border-bottom: 1px solid #f5f5f5;
        }

        .auto-item:hover {
            background: #f0fdf4;
        }

        .auto-name {
            font-weight: 500;
            color: #111;
        }

        .auto-detail {
            font-size: 11px;
            color: #aaa;
            margin-top: 2px;
        }
    </style>
</head>

<body>

    <div class="page-header">
        <a href="/places/<?= (int)$place['id'] ?>">← Kembali ke Detail</a>
        <span style="color:#ccc">|</span>
        <span style="font-size:14px; color:#555">✏️ Edit Tempat</span>
    </div>

    <div class="container">

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <?php foreach ($errors as $e): ?><p>⚠️ <?= esc($e) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/places/<?= (int)$place['id'] ?>/update" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Info Dasar -->
            <div class="card">
                <div class="card-title">📝 Informasi Tempat</div>
                <div class="form-group">
                    <label>Nama Tempat <span class="req">*</span></label>
                    <input type="text" name="name" value="<?= esc($place['name']) ?>" placeholder="Nama tempat...">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" placeholder="Ceritakan tentang tempat ini..."><?= esc($place['description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Ganti Foto (kosongkan jika tidak ingin mengganti)</label>
                    <input type="file" name="thumbnail" accept="image/*">
                    <?php if ($place['thumbnail']): ?>
                        <div class="thumb-preview">
                            <img src="/<?= esc($place['thumbnail']) ?>" alt="Foto saat ini">
                            <div style="font-size:11px;color:#aaa;margin-top:4px">Foto saat ini</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lokasi -->
            <div class="card">
                <div class="card-title">📍 Lokasi</div>
                <div class="form-group">
                    <label>Cari Alamat Baru (opsional)</label>
                    <div class="search-wrapper">
                        <input type="text" id="address-search" placeholder="Ketik untuk cari alamat baru..." autocomplete="off">
                        <div id="autocomplete-list"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap <span class="req">*</span></label>
                    <input type="text" name="address" id="address" value="<?= esc($place['address']) ?>">
                </div>
                <div class="coord-row">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" name="latitude" id="latitude" value="<?= esc($place['latitude']) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" name="longitude" id="longitude" value="<?= esc($place['longitude']) ?>" readonly>
                    </div>
                </div>
                <input type="hidden" id="osm_place_id" name="osm_place_id" value="<?= esc($place['osm_place_id']) ?>">
                <div id="map-pick"></div>
                <div class="map-hint">💡 Klik di peta atau drag marker untuk ubah lokasi</div>
            </div>

            <!-- Kategori -->
            <div class="card">
                <div class="card-title">🏷️ Kategori</div>
                <div class="checkbox-grid">
                    <?php
                    // Kumpulkan id kategori yang sudah dipilih
                    $selectedCats = array_column($place['categories'], 'id');
                    foreach ($categories as $cat):
                    ?>
                        <div>
                            <input type="checkbox" class="checkbox-item"
                                id="cat_<?= $cat['id'] ?>" name="categories[]" value="<?= $cat['id'] ?>"
                                <?= in_array($cat['id'], $selectedCats) ? 'checked' : '' ?>>
                            <label class="checkbox-label" for="cat_<?= $cat['id'] ?>">
                                <?= esc($cat['icon']) ?> <?= esc($cat['name']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tag -->
            <div class="card">
                <div class="card-title">🔖 Tag</div>
                <div class="checkbox-grid">
                    <?php
                    $selectedTags = array_column($place['tags'], 'id');
                    foreach ($tags as $tag):
                    ?>
                        <div>
                            <input type="checkbox" class="checkbox-item"
                                id="tag_<?= $tag['id'] ?>" name="tags[]" value="<?= $tag['id'] ?>"
                                <?= in_array($tag['id'], $selectedTags) ? 'checked' : '' ?>>
                            <label class="checkbox-label" for="tag_<?= $tag['id'] ?>">
                                <?= esc($tag['name']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="btn-row">
                <a href="/places/<?= (int)$place['id'] ?>" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── Peta dengan posisi saat ini ───────────────────────────────────
        const lat = <?= (float)($place['latitude']  ?? -6.2) ?>;
        const lng = <?= (float)($place['longitude'] ?? 106.8) ?>;
        const map = L.map('map-pick').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        const marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', e => {
            const p = e.target.getLatLng();
            updateCoords(p.lat, p.lng);
            reverseGeocode(p.lat, p.lng);
        });

        map.on('click', e => {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        function updateCoords(la, ln) {
            document.getElementById('latitude').value = la.toFixed(7);
            document.getElementById('longitude').value = ln.toFixed(7);
        }

        // ── Autocomplete Nominatim ─────────────────────────────────────────
        let timer = null;
        document.getElementById('address-search').addEventListener('input', function() {
            clearTimeout(timer);
            if (this.value.length < 3) {
                closeAuto();
                return;
            }
            timer = setTimeout(() => fetchLocations(this.value), 400);
        });

        async function fetchLocations(q) {
            const res = await fetch('/geo/search?q=' + encodeURIComponent(q));
            const data = await res.json();
            const list = document.getElementById('autocomplete-list');
            if (!data.length) {
                closeAuto();
                return;
            }
            list.innerHTML = data.map((item, i) =>
                `<div class="auto-item" onclick="selectLoc(${i})"
          data-lat="${item.lat}" data-lon="${item.lon}"
          data-name="${item.display_name.replace(/"/g,'&quot;')}">
       <div class="auto-name">${item.display_name.split(',')[0]}</div>
       <div class="auto-detail">${item.display_name}</div>
     </div>`
            ).join('');
            list.style.display = 'block';
        }

        function selectLoc(i) {
            const item = document.querySelectorAll('.auto-item')[i];
            const la = parseFloat(item.dataset.lat),
                ln = parseFloat(item.dataset.lon);
            document.getElementById('address').value = item.dataset.name;
            document.getElementById('address-search').value = item.dataset.name.split(',')[0];
            updateCoords(la, ln);
            map.setView([la, ln], 16);
            marker.setLatLng([la, ln]);
            closeAuto();
        }

        function closeAuto() {
            document.getElementById('autocomplete-list').style.display = 'none';
        }
        document.addEventListener('click', e => {
            if (!e.target.closest('.search-wrapper')) closeAuto();
        });

        async function reverseGeocode(la, ln) {
            const res = await fetch(`/geo/reverse?lat=${la}&lon=${ln}`);
            const data = await res.json();
            if (data.display_name) document.getElementById('address').value = data.display_name;
        }
    </script>

</body>

</html>