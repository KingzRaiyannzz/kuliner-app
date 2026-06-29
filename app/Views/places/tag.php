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