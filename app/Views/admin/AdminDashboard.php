<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — Kuliner App</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
        }

        /* Sidebar */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: #111827;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
        }

        .sidebar-logo {
            font-size: 18px;
            font-weight: 700;
            color: #1D9E75;
            padding: 0 20px 24px;
            border-bottom: 1px solid #1F2937;
        }

        .sidebar-menu {
            padding: 16px 12px;
            flex: 1;
        }

        .menu-label {
            font-size: 10px;
            font-weight: 500;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 0 8px;
            margin-bottom: 6px;
            margin-top: 16px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: #9CA3AF;
            text-decoration: none;
            margin-bottom: 2px;
            transition: all .15s;
        }

        .menu-item:hover {
            background: #1F2937;
            color: #fff;
        }

        .menu-item.active {
            background: #1D9E75;
            color: #fff;
        }

        .sidebar-user {
            padding: 16px 20px;
            border-top: 1px solid #1F2937;
            font-size: 12px;
            color: #6B7280;
        }

        .sidebar-user strong {
            display: block;
            color: #D1FAE5;
            margin-bottom: 2px;
        }

        /* Main content */
        .main {
            flex: 1;
            overflow-y: auto;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar h1 {
            font-size: 18px;
            font-weight: 600;
            color: #111;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-role {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #7C3AED;
            color: #fff;
        }

        .btn-logout {
            padding: 6px 14px;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            font-size: 13px;
            color: #555;
            text-decoration: none;
            background: #fff;
        }

        .btn-logout:hover {
            background: #f9fafb;
        }

        .content {
            padding: 24px;
        }

        /* Alert flash */
        .alert {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
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

        /* Stat cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e5e5;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .stat-icon {
            font-size: 26px;
        }

        .stat-num {
            font-size: 30px;
            font-weight: 700;
            color: #111;
            line-height: 1;
        }

        .stat-lbl {
            font-size: 12px;
            color: #888;
        }

        .stat-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .badge-red {
            background: #fef2f2;
            color: #dc2626;
        }

        .badge-green {
            background: #dcfce7;
            color: #166534;
        }

        /* Tables */
        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: #111;
            margin-bottom: 12px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .table-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: .5px;
            text-align: left;
            background: #f9fafb;
            border-bottom: 1px solid #f0f0f0;
        }

        td {
            padding: 10px 14px;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #f5f5f5;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .btn-verify {
            padding: 4px 12px;
            background: #1D9E75;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-verify:hover {
            background: #0F6E56;
        }

        .text-muted {
            color: #aaa;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="layout">

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">🍜 Kuliner Admin</div>
            <div class="sidebar-menu">
                <div class="menu-label">Menu</div>
                <a href="/admin" class="menu-item active">📊 Dashboard</a>
                <a href="/places" class="menu-item">🗺️ Lihat Peta</a>
                <div class="menu-label">Kelola</div>
                <a href="/admin/places" class="menu-item">📍 Tempat</a>
                <a href="/admin/categories" class="menu-item">🏷️ Kategori</a>
                <a href="/admin/tags" class="menu-item">🔖 Tag</a>
                <a href="/admin/reviews" class="menu-item">💬 Review</a>
                <a href="/admin/users" class="menu-item">👥 User</a>
            </div>
            <div class="sidebar-user">
                <strong><?= esc(session()->get('user_name')) ?></strong>
                Administrator
            </div>
        </div>

        <!-- Main -->
        <div class="main">
            <div class="topbar">
                <h1>Dashboard</h1>
                <div class="topbar-right">
                    <span class="badge-role">🛡️ Admin</span>
                    <a href="/logout" class="btn-logout">Keluar</a>
                </div>
            </div>

            <div class="content">

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">✅ <?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">⚠️ <?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <!-- Stat Cards -->
                <div class="stat-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📍</div>
                        <div class="stat-num"><?= $total_places ?></div>
                        <div class="stat-lbl">Total Tempat</div>
                        <?php if ($unverified > 0): ?>
                            <span class="stat-badge badge-red"><?= $unverified ?> belum diverifikasi</span>
                        <?php else: ?>
                            <span class="stat-badge badge-green">Semua terverifikasi</span>
                        <?php endif; ?>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">💬</div>
                        <div class="stat-num"><?= $total_reviews ?></div>
                        <div class="stat-lbl">Total Review</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-num"><?= $total_users ?></div>
                        <div class="stat-lbl">Total User</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🏷️</div>
                        <div class="stat-num"><?= $total_categories ?></div>
                        <div class="stat-lbl">Kategori</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🔖</div>
                        <div class="stat-num"><?= $total_tags ?></div>
                        <div class="stat-lbl">Tag</div>
                    </div>
                </div>

                <!-- Tables -->
                <div class="grid-2">

                    <!-- Tempat terbaru -->
                    <div>
                        <div class="section-title">📍 Tempat Terbaru</div>
                        <div class="table-card">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama Tempat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recent_places)): ?>
                                        <tr>
                                            <td colspan="3" class="text-muted" style="text-align:center;padding:20px">Belum ada data</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recent_places as $p): ?>
                                            <tr>
                                                <td><?= esc($p['name']) ?></td>
                                                <td>
                                                    <?php if ($p['is_verified']): ?>
                                                        <span class="stat-badge badge-green">✓ Verified</span>
                                                    <?php else: ?>
                                                        <span class="stat-badge badge-red">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!$p['is_verified']): ?>
                                                        <form action="/admin/places/<?= $p['id'] ?>/verify" method="POST" style="display:inline">
                                                            <?= csrf_field() ?>
                                                            <button class="btn-verify">Verify</button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Review terbaru -->
                    <div>
                        <div class="section-title">💬 Review Terbaru</div>
                        <div class="table-card">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Komentar</th>
                                        <th>Rating</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recent_reviews)): ?>
                                        <tr>
                                            <td colspan="3" class="text-muted" style="text-align:center;padding:20px">Belum ada review</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recent_reviews as $r): ?>
                                            <tr>
                                                <td><?= esc(substr($r['comment'] ?? '(no comment)', 0, 35)) ?>...</td>
                                                <td><?= str_repeat('⭐', (int)$r['rating']) ?></td>
                                                <td class="text-muted"><?= date('d M', strtotime($r['created_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>