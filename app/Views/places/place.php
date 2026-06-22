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