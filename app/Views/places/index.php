<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temukan Kuliner — Kuliner App</title>
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
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ── Navbar ── */
        .navbar {
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            padding: 0 20px;
            height: 56px;
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
            z-index: 1000;
        }

        .nav-logo {
            font-size: 22px;
            font-weight: 700;
            color: #1D9E75;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-search {
            flex: 1;
            max-width: 340px;
            display: flex;
            align-items: center;
            gap: 0;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }

        .nav-search input {
            flex: 1;
            padding: 8px 12px;
            border: none;
            font-size: 14px;
            outline: none;
            color: #333;
        }

        .nav-search button {
            padding: 8px 14px;
            background: #1D9E75;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .nav-search button:hover {
            background: #0F6E56;
        }

        .nav-spacer {
            flex: 1;
        }

        .nav-btn {
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .nav-btn-primary {
            background: #1D9E75;
            color: #fff;
        }

        .nav-btn-primary:hover {
            background: #0F6E56;
        }

        .nav-btn-ghost {
            color: #555;
            border: 1px solid #d1d5db;
        }

        .nav-btn-ghost:hover {
            background: #f9fafb;
        }

        .nav-user {
            font-size: 13px;
            color: #555;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .avatar-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #1D9E75;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }

        /* ── Layout utama: filter | peta | list ── */
        .main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ── Sidebar Filter ── */
        .sidebar {
            width: 240px;
            background: #fff;
            border-right: 1px solid #e5e5e5;
            overflow-y: auto;
            flex-shrink: 0;
            padding: 16px;
        }

        .filter-section {
            margin-bottom: 20px;
        }

        .filter-title {
            font-size: 12px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 10px;
        }

        /* Chip filter */
        .chip-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .chip {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            border: 1px solid #d1d5db;
            color: #555;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
            background: #fff;
        }

        .chip:hover {
            border-color: #1D9E75;
            color: #1D9E75;
        }

        .chip.active {
            background: #1D9E75;
            border-color: #1D9E75;
            color: #fff;
        }

        /* Slider rating */
        .rating-slider {
            width: 100%;
            accent-color: #1D9E75;
        }

        .rating-val {
            font-size: 13px;
            color: #1D9E75;
            font-weight: 500;
            margin-top: 4px;
        }

        /* Sort select */
        select.sort-select {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            color: #555;
            background: #fff;
        }

        .btn-reset {
            width: 100%;
            padding: 8px;
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            color: #555;
            cursor: pointer;
            margin-top: 4px;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .btn-reset:hover {
            background: #f0f0f0;
        }

        /* ── Peta ── */
        #map {
            flex: 1;
            height: 100%;
            z-index: 1;
        }

        /* ── Panel list kanan ── */
        .list-panel {
            width: 300px;
            background: #fff;
            border-left: 1px solid #e5e5e5;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow: hidden;
        }

        .list-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            flex-shrink: 0;
        }

        .list-scroll {
            flex: 1;
            overflow-y: auto;
        }

        /* Kartu tempat di list */
        .place-card {
            padding: 12px 14px;
            border-bottom: 1px solid #f5f5f5;
            cursor: pointer;
            transition: background .15s;
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .place-card:hover {
            background: #f9fdfb;
        }

        .place-card.active {
            background: #f0fdf4;
            border-left: 3px solid #1D9E75;
        }

        .pc-top {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .pc-thumb {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            background: #e0f7f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .pc-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .pc-name {
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 3px;
        }

        .pc-addr {
            font-size: 11px;
            color: #aaa;
            line-height: 1.3;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        .pc-rating {
            font-size: 12px;
            color: #f59e0b;
            font-weight: 500;
        }

        .pc-cats {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 6px;
        }

        .pc-cat {
            font-size: 10px;
            background: #e0f7f0;
            color: #065f46;
            padding: 2px 7px;
            border-radius: 20px;
        }

        /* Popup Leaflet custom */
        .popup-content {
            font-family: 'Segoe UI', sans-serif;
            min-width: 180px;
        }

        .popup-name {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .popup-addr {
            font-size: 12px;
            color: #777;
            margin-bottom: 6px;
        }

        .popup-rating {
            font-size: 13px;
            color: #f59e0b;
            margin-bottom: 8px;
        }

        .popup-link {
            display: block;
            text-align: center;
            padding: 6px 12px;
            background: #1D9E75;
            color: #fff;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            font-weight: 500;
        }

        .popup-link:hover {
            background: #0F6E56;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #aaa;
        }

        .empty-state .emoji {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 13px;
        }

        /* Flash message */
        .flash {
            padding: 10px 16px;
            font-size: 13px;
            border-bottom: 1px solid;
            flex-shrink: 0;
        }

        .flash-success {
            background: #dcfce7;
            color: #166534;
            border-color: #bbf7d0;
        }
    </style>
</head>

<body>

    <!-- ════════════════════════════════════
     NAVBAR
════════════════════════════════════ -->
    <nav class="navbar">
        <a href="/places" class="nav-logo">🍜 Kuliner</a>

        <!-- Search bar -->
        <form class="nav-search" action="/places" method="GET">
            <!-- Pertahankan filter lain saat search -->
            <?php if ($filters['category'] ?? ''): ?>
                <input type="hidden" name="category" value="<?= esc($filters['category']) ?>">
            <?php endif; ?>
            <?php if ($filters['tag'] ?? ''): ?>
                <input type="hidden" name="tag" value="<?= esc($filters['tag']) ?>">
            <?php endif; ?>
            <input type="text" name="search"
                placeholder="Cari nama tempat..."
                value="<?= esc($filters['search'] ?? '') ?>">
            <button type="submit">🔍</button>
        </form>

        <div class="nav-spacer"></div>

        <?php if (session()->get('user_id')): ?>
            <a href="/places/create" class="nav-btn nav-btn-primary">+ Tambah Tempat</a>
            <div class="nav-user">
                <div class="avatar-sm"><?= strtoupper(substr(session()->get('user_name'), 0, 1)) ?></div>
                <span><?= esc(session()->get('user_name')) ?></span>
            </div>
            <a href="/logout" class="nav-btn nav-btn-ghost">Keluar</a>
        <?php else: ?>
            <a href="/login" class="nav-btn nav-btn-ghost">Masuk</a>
            <a href="/register" class="nav-btn nav-btn-primary">Daftar</a>
        <?php endif; ?>
    </nav>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success">✅ <?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- ════════════════════════════════════
     MAIN: SIDEBAR | PETA | LIST
════════════════════════════════════ -->
    <div class="main">

        <!-- ── Sidebar Filter ── -->
        <aside class="sidebar">
            <form action="/places" method="GET" id="filter-form">
                <!-- Pertahankan search -->
                <?php if ($filters['search'] ?? ''): ?>
                    <input type="hidden" name="search" value="<?= esc($filters['search']) ?>">
                <?php endif; ?>

                <!-- Kategori -->
                <div class="filter-section">
                    <div class="filter-title">Kategori</div>
                    <div class="chip-list">
                        <a href="<?= base_url('places?' . http_build_query(array_merge($filters, ['category' => '', 'page' => 1]))) ?>"
                            class="chip <?= empty($filters['category']) ? 'active' : '' ?>">Semua</a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="<?= base_url('places?' . http_build_query(array_merge($filters, ['category' => $cat['slug'], 'page' => 1]))) ?>"
                                class="chip <?= ($filters['category'] ?? '') === $cat['slug'] ? 'active' : '' ?>">
                                <?= esc($cat['icon']) ?> <?= esc($cat['name']) ?>
                                <span style="color:inherit;opacity:.6">(<?= $cat['place_count'] ?>)</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tag -->
                <div class="filter-section">
                    <div class="filter-title">Tag</div>
                    <div class="chip-list">
                        <?php foreach (array_slice($tags, 0, 12) as $tag): ?>
                            <a href="<?= base_url('places?' . http_build_query(array_merge($filters, ['tag' => $tag['slug'], 'page' => 1]))) ?>"
                                class="chip <?= ($filters['tag'] ?? '') === $tag['slug'] ? 'active' : '' ?>">
                                #<?= esc($tag['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Rating minimum -->
                <div class="filter-section">
                    <div class="filter-title">Rating Minimum</div>
                    <input type="range" class="rating-slider" name="min_rating"
                        min="0" max="5" step="0.5"
                        value="<?= esc($filters['min_rating'] ?? 0) ?>"
                        id="rating-slider"
                        oninput="document.getElementById('rating-val').textContent = this.value > 0 ? '⭐ ' + this.value + '+' : 'Semua'">
                    <div class="rating-val" id="rating-val">
                        <?= ($filters['min_rating'] ?? 0) > 0 ? '⭐ ' . $filters['min_rating'] . '+' : 'Semua' ?>
                    </div>
                </div>

                <!-- Urutan -->
                <div class="filter-section">
                    <div class="filter-title">Urutkan</div>
                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="created_at" <?= ($filters['sort'] ?? '') === 'created_at'  ? 'selected' : '' ?>>Terbaru</option>
                        <option value="avg_rating" <?= ($filters['sort'] ?? '') === 'avg_rating'  ? 'selected' : '' ?>>Rating tertinggi</option>
                        <option value="review_count" <?= ($filters['sort'] ?? '') === 'review_count' ? 'selected' : '' ?>>Paling banyak ulasan</option>
                        <option value="name" <?= ($filters['sort'] ?? '') === 'name'         ? 'selected' : '' ?>>Nama A–Z</option>
                    </select>
                </div>

                <button type="submit" style="display:none" id="apply-filter"></button>
                <a href="/places" class="btn-reset">↺ Reset Filter</a>
            </form>
        </aside>

        <!-- ── Peta Leaflet ── -->
        <div id="map"></div>

        <!-- ── Panel List Kanan ── -->
        <div class="list-panel">
            <div class="list-header">
                📍 <?= number_format($pagination['total']) ?> tempat ditemukan
                <?php if ($filters['search'] ?? ''): ?>
                    · "<strong><?= esc($filters['search']) ?></strong>"
                <?php endif; ?>
            </div>
            <div class="list-scroll" id="place-list">
                <?php if (empty($places)): ?>
                    <div class="empty-state">
                        <div class="emoji">🔍</div>
                        <p>Tidak ada tempat yang cocok<br>dengan filter yang dipilih.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($places as $p): ?>
                        <a href="/places/<?= (int)$p['id'] ?>"
                            class="place-card"
                            id="card-<?= (int)$p['id'] ?>"
                            data-id="<?= (int)$p['id'] ?>"
                            onmouseenter="highlightMarker(<?= (int)$p['id'] ?>)"
                            onmouseleave="unhighlightMarker(<?= (int)$p['id'] ?>)">
                            <div class="pc-top">
                                <div class="pc-thumb">
                                    <?php if ($p['thumbnail']): ?>
                                        <img src="/<?= esc($p['thumbnail']) ?>" alt="">
                                    <?php else: ?>
                                        🍜
                                    <?php endif; ?>
                                </div>
                                <div style="flex:1; min-width:0">
                                    <div class="pc-name"><?= esc($p['name']) ?></div>
                                    <div class="pc-addr"><?= esc($p['address']) ?></div>
                                    <div class="pc-rating">
                                        <?= str_repeat('⭐', (int)round((float)$p['avg_rating'])) ?>
                                        <span style="color:#555"><?= number_format((float)$p['avg_rating'], 1) ?></span>
                                        <span style="color:#aaa; font-weight:400">(<?= (int)$p['review_count'] ?>)</span>
                                    </div>
                                </div>
                            </div>
                            <?php if ($p['category_names']): ?>
                                <div class="pc-cats">
                                    <?php foreach (explode(',', $p['category_names']) as $cn): ?>
                                        <span class="pc-cat"><?= esc(trim($cn)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>

                    <!-- Pagination -->
                    <?php if ($pagination['last_page'] > 1): ?>
                        <div style="padding:12px; display:flex; justify-content:center; gap:6px; flex-wrap:wrap">
                            <?php for ($pg = 1; $pg <= $pagination['last_page']; $pg++): ?>
                                <a href="<?= base_url('places?' . http_build_query(array_merge($filters, ['page' => $pg]))) ?>"
                                    style="padding:5px 10px; border-radius:6px; font-size:12px; text-decoration:none;
                        background:<?= $pg === $pagination['page'] ? '#1D9E75' : '#f0f0f0' ?>;
                        color:<?= $pg === $pagination['page'] ? '#fff' : '#555' ?>">
                                    <?= $pg ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- ════════════════════════════════════
     LEAFLET JS
════════════════════════════════════ -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── Data marker dari PHP (semua places di database) ──────────────
        const mapData = <?= $mapData ?>;

        // ── Inisialisasi peta ─────────────────────────────────────────────
        const map = L.map('map').setView([-6.2, 106.816666], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);

        // ── Marker custom ─────────────────────────────────────────────────
        const defaultIcon = L.divIcon({
            className: '',
            html: '<div style="background:#1D9E75;color:#fff;width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.25)"><span style="transform:rotate(45deg);font-size:14px">🍜</span></div>',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -34],
        });

        const activeIcon = L.divIcon({
            className: '',
            html: '<div style="background:#f59e0b;color:#fff;width:38px;height:38px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;border:2px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3)"><span style="transform:rotate(45deg);font-size:18px">🍜</span></div>',
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -40],
        });

        // ── Buat semua marker ─────────────────────────────────────────────
        const markers = {};

        mapData.forEach(place => {
            if (!place.latitude || !place.longitude) return;

            const marker = L.marker([place.latitude, place.longitude], {
                    icon: defaultIcon
                })
                .addTo(map)
                .bindPopup(`
      <div class="popup-content">
        <div class="popup-name">${escHtml(place.name)}</div>
        <div class="popup-addr">📍 ${escHtml(place.address)}</div>
        <div class="popup-rating">⭐ ${parseFloat(place.avg_rating).toFixed(1)}</div>
        <a href="/places/${place.id}" class="popup-link">Lihat Detail →</a>
      </div>
    `, {
                    maxWidth: 220
                });

            // Klik marker → highlight kartu di list
            marker.on('click', () => scrollToCard(place.id));

            markers[place.id] = marker;
        });

        // ── Highlight marker saat hover kartu di list ─────────────────────
        function highlightMarker(id) {
            if (markers[id]) markers[id].setIcon(activeIcon);
        }

        function unhighlightMarker(id) {
            if (markers[id]) markers[id].setIcon(defaultIcon);
        }

        // ── Scroll list ke kartu dan buka popup marker ───────────────────
        function scrollToCard(id) {
            const card = document.getElementById('card-' + id);
            if (card) {
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
                document.querySelectorAll('.place-card').forEach(c => c.classList.remove('active'));
                card.classList.add('active');
            }
            if (markers[id]) {
                markers[id].openPopup();
                map.setView(markers[id].getLatLng(), 16, {
                    animate: true
                });
            }
        }

        // ── Apply filter saat slider rating berubah ───────────────────────
        document.getElementById('rating-slider').addEventListener('change', function() {
            document.getElementById('apply-filter').click();
        });

        // ── Escape HTML helper ───────────────────────────────────────────
        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
    </script>

</body>

</html>