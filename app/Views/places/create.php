<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — Kuliner App</title>

    <!-- Leaflet CSS -->
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
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header a {
            color: #1D9E75;
            text-decoration: none;
            font-size: 14px;
        }

        .page-header h1 {
            font-size: 20px;
            font-weight: 600;
        }

        .container {
            max-width: 820px;
            margin: 32px auto;
            padding: 0 16px 60px;
        }

        /* Alert error */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }

        .alert-error p {
            color: #dc2626;
            font-size: 13px;
            margin: 2px 0;
        }

        /* Card section */
        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
            color: #111;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            margin-bottom: 6px;
        }

        label .req {
            color: #e53e3e;
            margin-left: 2px;
        }

        input[type="text"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background: #fff;
            transition: border-color .2s;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #1D9E75;
            box-shadow: 0 0 0 3px rgba(29, 158, 117, .1);
        }

        input.error {
            border-color: #e53e3e;
        }

        .field-hint {
            font-size: 11px;
            color: #888;
            margin-top: 4px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Autocomplete lokasi */
        .search-wrapper {
            position: relative;
        }

        .search-loading {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #888;
            display: none;
        }

        #autocomplete-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #fff;
            border: 1px solid #d1d5db;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
            max-height: 220px;
            overflow-y: auto;
            display: none;
        }

        .autocomplete-item {
            padding: 10px 14px;
            font-size: 13px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            line-height: 1.4;
        }

        .autocomplete-item:hover {
            background: #f0fdf4;
        }

        .autocomplete-item .item-name {
            font-weight: 500;
            color: #111;
        }

        .autocomplete-item .item-detail {
            font-size: 11px;
            color: #888;
            margin-top: 2px;
        }

        /* Koordinat (readonly) */
        .coord-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        input[readonly] {
            background: #f9fafb;
            color: #666;
            cursor: not-allowed;
        }

        /* Peta Leaflet */
        #map-pick {
            width: 100%;
            height: 320px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            margin-top: 12px;
            z-index: 1;
        }

        .map-hint {
            font-size: 12px;
            color: #888;
            margin-top: 6px;
            text-align: center;
        }

        /* Checkbox grid (kategori & tag) */
        .checkbox-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .checkbox-item {
            display: none;
        }

        .checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all .2s;
            color: #555;
        }

        .checkbox-item:checked+.checkbox-label {
            background: #1D9E75;
            border-color: #1D9E75;
            color: #fff;
        }

        /* Tag input dinamis */
        .tag-input-wrap {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .tag-input-wrap input {
            flex: 1;
            padding: 8px 12px;
            font-size: 13px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
        }

        .tag-input-wrap button {
            padding: 8px 14px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
        }

        .tag-input-wrap button:hover {
            background: #e5e7eb;
        }

        #custom-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .custom-tag-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #dbeafe;
            color: #1d4ed8;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .custom-tag-chip button {
            background: none;
            border: none;
            cursor: pointer;
            color: #1d4ed8;
            font-size: 14px;
            line-height: 1;
        }

        /* Tombol submit */
        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1D9E75;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background .2s;
            width: 100%;
            justify-content: center;
        }

        .btn-submit:hover {
            background: #0F6E56;
        }

        .btn-submit:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="page-header">
        <a href="/places">← Kembali</a>
        <span style="color:#ccc">|</span>
        <h1>🍜 <?= esc($title) ?></h1>
    </div>

    <div class="container">

        <!-- Tampilkan error validasi jika ada -->
        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <?php foreach ($errors as $error): ?>
                    <p>⚠️ <?= esc($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/places" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- ============================================
         SECTION 1: Informasi Dasar
    ============================================ -->
            <div class="card">
                <div class="card-title">📝 Informasi Tempat</div>

                <div class="form-group">
                    <label for="name">Nama Tempat <span class="req">*</span></label>
                    <input type="text" id="name" name="name"
                        placeholder="Contoh: Warung Bu Siti, Mie Ayam Pak Budi"
                        value="<?= esc($old['name'] ?? '') ?>"
                        class="<?= isset($errors['name']) ? 'error' : '' ?>">
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description"
                        placeholder="Ceritakan sedikit tentang tempat ini..."><?= esc($old['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="thumbnail">Foto Tempat (opsional)</label>
                    <input type="file" name="thumbnail" accept="image/*">
                    <div class="field-hint">Ukuran maksimal 2MB, format JPG/PNG</div>
                </div>
            </div>

            <!-- ============================================
         SECTION 2: Lokasi + Peta
    ============================================ -->
            <div class="card">
                <div class="card-title">📍 Lokasi</div>

                <div class="form-group">
                    <label for="address-search">Cari Alamat <span class="req">*</span></label>
                    <div class="search-wrapper">
                        <input type="text" id="address-search"
                            placeholder="Ketik nama jalan, gedung, atau tempat..."
                            autocomplete="off">
                        <span class="search-loading" id="search-loading">mencari...</span>
                        <div id="autocomplete-list"></div>
                    </div>
                    <div class="field-hint">Ketik minimal 3 karakter untuk memunculkan saran lokasi</div>
                </div>

                <!-- Field alamat lengkap (diisi otomatis setelah pilih dari autocomplete) -->
                <div class="form-group">
                    <label for="address">Alamat Lengkap <span class="req">*</span></label>
                    <input type="text" id="address" name="address"
                        placeholder="Alamat lengkap akan terisi otomatis"
                        value="<?= esc($old['address'] ?? '') ?>"
                        class="<?= isset($errors['address']) ? 'error' : '' ?>">
                </div>

                <!-- Koordinat (diisi otomatis, readonly) -->
                <div class="coord-row">
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" id="latitude" name="latitude"
                            value="<?= esc($old['latitude'] ?? '') ?>"
                            placeholder="-6.200000" readonly>
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" id="longitude" name="longitude"
                            value="<?= esc($old['longitude'] ?? '') ?>"
                            placeholder="106.816666" readonly>
                    </div>
                </div>

                <!-- Hidden: simpan OSM place_id untuk referensi -->
                <input type="hidden" id="osm_place_id" name="osm_place_id">

                <!-- Peta Leaflet — klik peta untuk pindahkan marker -->
                <div id="map-pick"></div>
                <div class="map-hint">💡 Klik di peta untuk menggeser marker secara manual</div>
            </div>

            <!-- ============================================
         SECTION 3: Kategori
    ============================================ -->
            <div class="card">
                <div class="card-title">🏷️ Kategori</div>
                <div class="checkbox-grid">
                    <?php foreach ($categories as $cat): ?>
                        <div>
                            <input type="checkbox"
                                class="checkbox-item"
                                id="cat_<?= $cat['id'] ?>"
                                name="categories[]"
                                value="<?= $cat['id'] ?>"
                                <?= in_array($cat['id'], $old['categories'] ?? []) ? 'checked' : '' ?>>
                            <label class="checkbox-label" for="cat_<?= $cat['id'] ?>">
                                <?= esc($cat['icon']) ?> <?= esc($cat['name']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ============================================
         SECTION 4: Tags
    ============================================ -->
            <div class="card">
                <div class="card-title">🔖 Tag</div>
                <div class="checkbox-grid" id="tags-grid">
                    <?php foreach ($tags as $tag): ?>
                        <div>
                            <input type="checkbox"
                                class="checkbox-item"
                                id="tag_<?= $tag['id'] ?>"
                                name="tags[]"
                                value="<?= $tag['id'] ?>"
                                <?= in_array($tag['id'], $old['tags'] ?? []) ? 'checked' : '' ?>>
                            <label class="checkbox-label" for="tag_<?= $tag['id'] ?>">
                                <?= esc($tag['name']) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Input untuk tambah tag baru yang belum ada -->
                <div class="tag-input-wrap">
                    <input type="text" id="new-tag-input" placeholder="Tambah tag baru (tekan Enter)...">
                    <button type="button" onclick="addCustomTag()">+ Tambah</button>
                </div>
                <div id="custom-tags"></div>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-submit" id="btn-submit">
                🍜 Tambahkan Tempat Kuliner
            </button>

        </form>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // ================================================================
        // INISIALISASI PETA LEAFLET
        // ================================================================
        const defaultLat = <?= $old['latitude'] ?? -6.2 ?>;
        const defaultLng = <?= $old['longitude'] ?? 106.816666 ?>;
        const defaultZoom = <?= ($old['latitude'] ?? false) ? 15 : 12 ?>;

        const map = L.map('map-pick').setView([defaultLat, defaultLng], defaultZoom);

        // Tile layer OpenStreetMap (gratis)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);

        // Marker yang bisa di-drag
        const marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        // Update koordinat input saat marker di-drag
        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            updateCoords(pos.lat, pos.lng);
            // Reverse geocode — ambil nama alamat dari koordinat hasil drag
            reverseGeocode(pos.lat, pos.lng);
        });

        // Klik di peta → pindahkan marker
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoords(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        function updateCoords(lat, lng) {
            document.getElementById('latitude').value = lat.toFixed(7);
            document.getElementById('longitude').value = lng.toFixed(7);
        }


        // ================================================================
        // AUTOCOMPLETE ALAMAT (memanggil GeoController → Nominatim)
        // ================================================================
        let debounceTimer = null;

        document.getElementById('address-search').addEventListener('input', function() {
            const q = this.value.trim();
            clearTimeout(debounceTimer);

            if (q.length < 3) {
                closeAutocomplete();
                return;
            }

            // Debounce 400ms agar tidak spam request saat user masih mengetik
            debounceTimer = setTimeout(() => searchLocation(q), 400);
        });

        async function searchLocation(q) {
            document.getElementById('search-loading').style.display = 'inline';

            try {
                const res = await fetch('/geo/search?q=' + encodeURIComponent(q));
                const data = await res.json();
                renderAutocomplete(data);
            } catch (err) {
                console.error('Gagal fetch lokasi:', err);
            } finally {
                document.getElementById('search-loading').style.display = 'none';
            }
        }

        function renderAutocomplete(results) {
            const list = document.getElementById('autocomplete-list');

            if (!results || results.length === 0) {
                list.style.display = 'none';
                return;
            }

            list.innerHTML = results.map((item, idx) =>
                `<div class="autocomplete-item" onclick="selectLocation(${idx})"
          data-lat="${item.lat}" data-lon="${item.lon}"
          data-name="${escapeAttr(item.display_name)}"
          data-osm="${item.place_id}">
       <div class="item-name">${escapeHtml(item.display_name.split(',')[0])}</div>
       <div class="item-detail">${escapeHtml(item.display_name)}</div>
     </div>`
            ).join('');

            list.style.display = 'block';
        }

        function selectLocation(idx) {
            const item = document.querySelectorAll('.autocomplete-item')[idx];
            if (!item) return;

            const lat = parseFloat(item.dataset.lat);
            const lon = parseFloat(item.dataset.lon);
            const name = item.dataset.name;
            const osm = item.dataset.osm;

            // Isi semua field lokasi
            document.getElementById('address-search').value = name.split(',')[0];
            document.getElementById('address').value = name;
            document.getElementById('osm_place_id').value = osm;
            updateCoords(lat, lon);

            // Pindahkan peta & marker ke lokasi yang dipilih
            map.setView([lat, lon], 17);
            marker.setLatLng([lat, lon]);

            closeAutocomplete();
        }

        function closeAutocomplete() {
            document.getElementById('autocomplete-list').style.display = 'none';
        }

        // Tutup autocomplete saat klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-wrapper')) closeAutocomplete();
        });


        // ================================================================
        // REVERSE GEOCODE (koordinat → alamat)
        // Dipanggil saat user drag marker atau klik peta
        // ================================================================
        async function reverseGeocode(lat, lon) {
            try {
                const res = await fetch(`/geo/reverse?lat=${lat}&lon=${lon}`);
                const data = await res.json();
                if (data.display_name) {
                    document.getElementById('address').value = data.display_name;
                }
            } catch (err) {
                console.error('Reverse geocode gagal:', err);
            }
        }


        // ================================================================
        // TAG CUSTOM — tambah tag baru yang belum ada di daftar
        // ================================================================
        let customTagCount = 0;

        document.getElementById('new-tag-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addCustomTag();
            }
        });

        function addCustomTag() {
            const input = document.getElementById('new-tag-input');
            const val = input.value.trim();
            if (!val) return;

            customTagCount++;
            const chipId = 'ctag_' + customTagCount;

            // Tambahkan hidden input agar nama tag ikut terkirim ke controller
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'tags[]';
            hiddenInput.value = val;
            hiddenInput.id = chipId + '_input';
            document.querySelector('form').appendChild(hiddenInput);

            // Tampilkan chip visual
            const chip = document.createElement('div');
            chip.className = 'custom-tag-chip';
            chip.id = chipId;
            chip.innerHTML = `${escapeHtml(val)} <button type="button" onclick="removeCustomTag('${chipId}')">×</button>`;
            document.getElementById('custom-tags').appendChild(chip);

            input.value = '';
        }

        function removeCustomTag(chipId) {
            document.getElementById(chipId)?.remove();
            document.getElementById(chipId + '_input')?.remove();
        }


        // ================================================================
        // HELPER — escape HTML untuk mencegah XSS di JavaScript
        // ================================================================
        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function escapeAttr(str) {
            return String(str).replace(/"/g, '&quot;');
        }
    </script>
</body>

</html>