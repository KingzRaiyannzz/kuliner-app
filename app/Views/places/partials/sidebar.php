<aside class="sidebar">
    <form action="/places" method="GET" id="filter-form">
        <?php if ($filters['search'] ?? ''): ?>
            <input type="hidden" name="search" value="<?= esc($filters['search']) ?>">
        <?php endif; ?>

        <!-- Papan Kategori -->
        <?= $this->include('places/kategori') ?>

        <!-- Papan Tag -->
        <?= $this->include('places/tag') ?>

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