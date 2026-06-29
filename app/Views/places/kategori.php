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