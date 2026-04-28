<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($place['name']) ?> — Kuliner App</title>
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

        .layout {
            max-width: 1000px;
            margin: 28px auto;
            padding: 0 16px 60px;
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
        }

        @media (max-width: 700px) {
            .layout {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        /* Hero */
        .place-thumbnail {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 14px;
        }

        .place-thumbnail-empty {
            width: 100%;
            height: 140px;
            border-radius: 10px;
            margin-bottom: 14px;
            background: linear-gradient(135deg, #e0f7f0, #d1fae5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 52px;
        }

        .place-name {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .place-meta {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 11px;
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .badge-v {
            background: #dcfce7;
            color: #166534;
        }

        .badge-uv {
            background: #f3f4f6;
            color: #6b7280;
        }

        .place-address {
            font-size: 13px;
            color: #777;
            margin-bottom: 10px;
        }

        .place-desc {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .chip-row {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .chip-cat {
            background: #e0f7f0;
            color: #065f46;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .chip-tag {
            background: #f3f4f6;
            color: #374151;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        /* Alert */
        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }

        /* Review list */
        .review-item {
            padding: 14px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
            background: #1D9E75;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
        }

        .reviewer-name {
            font-weight: 500;
            font-size: 14px;
            line-height: 1;
        }

        .review-date {
            font-size: 11px;
            color: #bbb;
            margin-top: 2px;
        }

        .review-stars {
            font-size: 13px;
            margin-left: auto;
            white-space: nowrap;
        }

        .review-comment {
            font-size: 13px;
            color: #555;
            line-height: 1.6;
        }

        .review-photo {
            margin-top: 8px;
            max-width: 200px;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .btn-delete-review {
            font-size: 11px;
            color: #e53e3e;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin-top: 4px;
        }

        .btn-delete-review:hover {
            text-decoration: underline;
        }

        /* Rating summary sidebar */
        .rating-big {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .score-num {
            font-size: 44px;
            font-weight: 700;
            color: #1D9E75;
            line-height: 1;
        }

        .score-stars {
            font-size: 18px;
            margin: 4px 0 2px;
        }

        .score-total {
            font-size: 12px;
            color: #aaa;
        }

        .bar-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }

        .bar-label {
            font-size: 12px;
            color: #777;
            width: 14px;
            text-align: right;
        }

        .bar-track {
            flex: 1;
            height: 7px;
            background: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: #1D9E75;
            border-radius: 4px;
        }

        .bar-count {
            font-size: 11px;
            color: #bbb;
            width: 18px;
        }

        /* Peta */
        #map-detail {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
        }

        /* Form review */
        .star-picker {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 2px;
            margin-bottom: 4px;
        }

        .star-picker input {
            display: none;
        }

        .star-picker label {
            font-size: 32px;
            color: #d1d5db;
            cursor: pointer;
            transition: color .1s;
            line-height: 1;
        }

        .star-picker label:hover,
        .star-picker label:hover~label,
        .star-picker input:checked~label {
            color: #f59e0b;
        }

        .rating-hint {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 14px;
            min-height: 16px;
        }

        label.flabel {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }

        textarea.ta {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            resize: vertical;
            min-height: 90px;
            font-family: inherit;
        }

        textarea.ta:focus {
            outline: none;
            border-color: #1D9E75;
            box-shadow: 0 0 0 3px rgba(29, 158, 117, .12);
        }

        .char-hint {
            font-size: 11px;
            color: #bbb;
            text-align: right;
            margin-top: 3px;
            margin-bottom: 10px;
        }

        input.file-input {
            width: 100%;
            padding: 8px;
            border: 1px dashed #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            background: #fafafa;
            margin-bottom: 4px;
        }

        .btn-submit-review {
            width: 100%;
            padding: 12px;
            background: #1D9E75;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
            margin-top: 6px;
        }

        .btn-submit-review:hover {
            background: #0F6E56;
        }

        .login-prompt {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            font-size: 14px;
            color: #666;
        }

        .login-prompt a {
            color: #1D9E75;
            font-weight: 500;
        }

        .already-reviewed {
            text-align: center;
            padding: 16px;
            background: #f0fdf4;
            border-radius: 8px;
            font-size: 14px;
            color: #166534;
        }
    </style>
</head>

<body>

    <div class="page-header">
        <a href="/places">← Kembali</a>
        <span style="color:#ddd">|</span>
        <span style="font-size:13px; color:#777"><?= esc($place['name']) ?></span>
    </div>

    <div class="layout">

        <!-- =============================================
       KIRI: Detail + Review List + Form Review
  ============================================= -->
        <div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">✅ <?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error">⚠️ <?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <!-- Detail tempat -->
            <div class="card">
                <?php if ($place['thumbnail']): ?>
                    <img class="place-thumbnail" src="/<?= esc($place['thumbnail']) ?>" alt="<?= esc($place['name']) ?>">
                <?php else: ?>
                    <div class="place-thumbnail-empty">🍜</div>
                <?php endif; ?>

                <div class="place-name"><?= esc($place['name']) ?></div>

                <div class="place-meta">
                    <span>oleh <strong><?= esc($place['author_name'] ?? 'Anonim') ?></strong></span>
                    <span>·</span>
                    <span><?= date('d M Y', strtotime($place['created_at'])) ?></span>
                    <span>·</span>
                    <span class="badge <?= $place['is_verified'] ? 'badge-v' : 'badge-uv' ?>">
                        <?= $place['is_verified'] ? '✓ Terverifikasi' : 'Belum diverifikasi' ?>
                    </span>
                </div>

                <div class="place-address">📍 <?= esc($place['address']) ?></div>

                <?php if ($place['description']): ?>
                    <div class="place-desc"><?= nl2br(esc((string)$place['description'])) ?></div>
                <?php endif; ?>

                <?php if (!empty($place['categories'])): ?>
                    <div class="chip-row" style="margin-bottom:6px">
                        <?php foreach ($place['categories'] as $c): ?>
                            <span class="chip-cat"><?= esc($c['icon']) ?> <?= esc($c['name']) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($place['tags'])): ?>
                    <div class="chip-row">
                        <?php foreach ($place['tags'] as $t): ?>
                            <span class="chip-tag">#<?= esc($t['name']) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Daftar Review -->
            <div class="card" id="reviews">
                <div class="card-title">💬 Ulasan (<?= (int)$place['review_count'] ?>)</div>

                <?php if (empty($reviews)): ?>
                    <p style="text-align:center; color:#bbb; padding:24px 0; font-size:14px">
                        Belum ada ulasan. Jadilah yang pertama! 🌟
                    </p>
                <?php else: ?>
                    <?php foreach ($reviews as $rv): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="avatar-circle">
                                    <?= strtoupper(substr($rv['reviewer_name'] ?? 'A', 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="reviewer-name"><?= esc($rv['reviewer_name'] ?? 'Anonim') ?></div>
                                    <div class="review-date"><?= date('d M Y', strtotime($rv['created_at'])) ?></div>
                                </div>
                                <div class="review-stars">
                                    <?= str_repeat('⭐', (int)$rv['rating']) ?>
                                </div>
                            </div>
                            <?php if (isset($rv['comment']) && is_string($rv['comment']) && $rv['comment'] !== ''): ?>
                                <div class="review-comment"><?= nl2br(esc($rv['comment'])) ?></div>
                            <?php endif; ?>
                            <?php if ($rv['photo']): ?>
                                <img src="/<?= esc($rv['photo']) ?>" class="review-photo" alt="Foto review">
                            <?php endif; ?>
                            <!-- Tombol hapus hanya untuk pemilik review -->
                            <?php if (session()->get('user_id') == $rv['user_id']): ?>
                                <form action="/reviews/<?= (int)$rv['id'] ?>/delete" method="POST"
                                    onsubmit="return confirm('Hapus ulasan ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-delete-review">🗑 Hapus ulasan saya</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Form Tulis Review -->
            <div class="card" id="review-form">
                <div class="card-title">✍️ Tulis Ulasan</div>

                <?php $rvErrors = session()->getFlashdata('review_errors'); ?>
                <?php if (!empty($rvErrors)): ?>
                    <div class="alert alert-error">
                        <?php foreach ($rvErrors as $e): ?><p>⚠️ <?= esc($e) ?></p><?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!session()->get('user_id')): ?>
                    <div class="login-prompt">
                        <a href="/login">Login</a> atau <a href="/register">daftar</a> untuk memberikan ulasan.
                    </div>

                <?php elseif ($hasReviewed): ?>
                    <div class="already-reviewed">
                        ✅ Kamu sudah memberikan ulasan untuk tempat ini.
                    </div>

                <?php else: ?>
                    <form action="/reviews" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="place_id" value="<?= (int)$place['id'] ?>">

                        <!-- Pilih bintang -->
                        <label class="flabel">Rating <span style="color:#e53e3e">*</span></label>
                        <div class="star-picker">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="rating" id="s<?= $i ?>" value="<?= $i ?>">
                                <label for="s<?= $i ?>">★</label>
                            <?php endfor; ?>
                        </div>
                        <div class="rating-hint" id="rating-hint">Klik bintang untuk memberi rating</div>

                        <!-- Komentar -->
                        <div style="margin-bottom:10px">
                            <label class="flabel" for="comment">Komentar (opsional)</label>
                            <textarea name="comment" id="comment" class="ta"
                                placeholder="Ceritakan soal rasa, harga, suasana, pelayanan..." maxlength="500"></textarea>
                            <div class="char-hint"><span id="char-used">0</span>/500 karakter</div>
                        </div>

                        <!-- Foto -->
                        <div style="margin-bottom:10px">
                            <label class="flabel">Foto (opsional)</label>
                            <input type="file" name="photo" class="file-input" accept="image/*">
                            <div style="font-size:11px; color:#bbb; margin-top:3px">Maks 2MB · JPG / PNG / WebP</div>
                        </div>

                        <button type="submit" class="btn-submit-review">⭐ Kirim Ulasan</button>
                    </form>
                <?php endif; ?>
            </div>

        </div>

        <!-- =============================================
       KANAN: Rating Summary + Peta + Aksi
  ============================================= -->
        <div>

            <!-- Rating summary -->
            <div class="card">
                <div class="card-title">⭐ Ringkasan Rating</div>
                <?php
                $avg   = (float)($place['avg_rating'] ?? 0);
                $total = (int)  ($place['review_count'] ?? 0);
                ?>
                <div class="rating-big">
                    <div>
                        <div class="score-num"><?= number_format($avg, 1) ?></div>
                        <div class="score-stars"><?= str_repeat('⭐', (int)round($avg)) ?></div>
                        <div class="score-total"><?= $total ?> ulasan</div>
                    </div>
                    <div style="flex:1">
                        <?php foreach ([5, 4, 3, 2, 1] as $star): ?>
                            <?php $cnt = (int)($distribution[(string)$star] ?? 0);
                            $pct = $total > 0 ? round($cnt / $total * 100) : 0; ?>
                            <div class="bar-row">
                                <div class="bar-label"><?= $star ?></div>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width:<?= $pct ?>%"></div>
                                </div>
                                <div class="bar-count"><?= $cnt ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Peta mini -->
            <?php if ($place['latitude'] && $place['longitude']): ?>
                <div class="card">
                    <div class="card-title">🗺️ Lokasi</div>
                    <div id="map-detail"></div>
                    <div style="font-size:11px; color:#aaa; text-align:center; margin-top:8px; line-height:1.5">
                        <?= esc($place['address']) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tombol aksi untuk pemilik -->
            <?php if (session()->get('user_id') == $place['user_id']): ?>
                <div class="card">
                    <a href="/places/<?= (int)$place['id'] ?>/edit"
                        style="display:block; padding:10px; text-align:center; background:#f9fafb;
                border:1px solid #e5e5e5; border-radius:8px; font-size:13px;
                color:#555; text-decoration:none; margin-bottom:8px">
                        ✏️ Edit Tempat Ini
                    </a>
                    <form action="/places/<?= (int)$place['id'] ?>/delete" method="POST"
                        onsubmit="return confirm('Yakin hapus tempat ini? Semua ulasan ikut terhapus.')">
                        <?= csrf_field() ?>
                        <button type="submit"
                            style="width:100%; padding:10px; background:#fff; border:1px solid #fca5a5;
                       color:#dc2626; border-radius:8px; font-size:13px; cursor:pointer">
                            🗑️ Hapus Tempat
                        </button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ── Peta mini ──────────────────────────────────────────
        <?php if ($place['latitude'] && $place['longitude']): ?>
            const map = L.map('map-detail', {
                    scrollWheelZoom: false
                })
                .setView([<?= (float)$place['latitude'] ?>, <?= (float)$place['longitude'] ?>], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);
            L.marker([<?= (float)$place['latitude'] ?>, <?= (float)$place['longitude'] ?>])
                .addTo(map)
                .bindPopup('<strong><?= esc($place['name']) ?></strong>')
                .openPopup();
        <?php endif; ?>

        // ── Label rating bintang ────────────────────────────────
        const ratingLabel = {
            1: '😞 Buruk',
            2: '😐 Kurang',
            3: '😊 Cukup',
            4: '😄 Bagus',
            5: '🤩 Luar biasa!'
        };
        document.querySelectorAll('.star-picker input').forEach(input => {
            input.addEventListener('change', () => {
                document.getElementById('rating-hint').textContent = ratingLabel[input.value] + ' (' + input.value + ' bintang)';
            });
        });

        // ── Hitung karakter komentar ────────────────────────────
        document.getElementById('comment')?.addEventListener('input', function() {
            document.getElementById('char-used').textContent = this.value.length;
        });
    </script>

</body>

</html>