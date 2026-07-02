<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?></title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #F8FAFC;
        }

        .layout {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            height: 100vh;
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

        /* stop sidebar*/

        /* Main Content*/
        .main {
            flex: 1;
            min-width: 0;
            height: 100vh;
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

        /*button log out*/
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

        /*main stop*/

        .card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #1D9E75;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .btn {
            background: #1D9E75;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-success {
            background: #1D9E75;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            text-decoration: none;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 18px;
        }

        .form-group label {
            margin-bottom: 6px;
            font-weight: 600;
            color: #374151;
        }

        .form-group input,
        .form-group textarea {

            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
        }

        .form-group textarea {
            height: 120px;
            resize: none;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        /* stop */

        /* css untuk edit */
        .action {
            display: flex;
            gap: 8px;
        }

        .btn {
            text-decoration: none;
            padding: 7px 15px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: .2s;
        }

        .btn-edit {
            background: #1D9E75;
            color: white;
        }

        .btn-edit:hover {
            background: #167d5d;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
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

                <?= $this->renderSection('content') ?>

            </div>

        </div>

    </div>

</body>

</html>