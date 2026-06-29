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
            min-height: 100vh;
        }

        /* Sidebar */

        .sidebar {
            width: 240px;
            background: #111827;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 22px;
            font-size: 20px;
            font-weight: bold;
            color: #1D9E75;
            border-bottom: 1px solid #1f2937;
        }

        .menu {
            padding: 15px;
            flex: 1;
        }

        .menu a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: .2s;
        }

        .menu a:hover {
            background: #1D9E75;
            color: white;
        }

        .main {
            flex: 1;
        }

        .topbar {

            height: 70px;

            background: white;

            display: flex;

            justify-content: space-between;

            align-items: center;

            padding: 0 30px;

            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);

        }

        .content {

            padding: 30px;

        }

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
    </style>

</head>

<body>

    <div class="layout">

        <div class="sidebar">

            <div class="logo">
                🍜 Kuliner Admin
            </div>

            <div class="menu">

                <a href="<?= base_url('admin') ?>">📊 Dashboard</a>

                <a href="<?= base_url('admin/places') ?>">📍 Tempat</a>

                <a href="<?= base_url('admin/categories') ?>">🏷️ Kategori</a>

                <a href="<?= base_url('admin/tags') ?>">🔖 Tag</a>

                <a href="<?= base_url('admin/reviews') ?>">💬 Review</a>

                <a href="<?= base_url('admin/users') ?>">👤 User</a>

                <a href="<?= base_url('places') ?>">🗺️ Lihat Website</a>

                <a href="<?= base_url('logout') ?>">🚪 Logout</a>

            </div>

        </div>

        <div class="main">

            <div class="topbar">

                <h2><?= $title ?? 'Dashboard' ?></h2>

                <div>

                    <?= session()->get('user_name') ?>

                </div>

            </div>

            <div class="content">

                <?= $this->renderSection('content') ?>

            </div>

        </div>

    </div>

</body>

</html>