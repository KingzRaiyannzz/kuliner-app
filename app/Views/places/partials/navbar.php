<nav class="navbar">
    <a href="/admin" class="nav-logo">🍜 Kuliner</a>

    <!-- Search bar -->
    <form class="nav-search" action="/places" method="GET">
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