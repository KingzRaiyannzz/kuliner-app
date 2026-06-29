<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temukan Kuliner — Kuliner App</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* Masukkan semua CSS Anda di sini agar tidak hilang */
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

        .main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

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

        #map {
            flex: 1;
            height: 100%;
            z-index: 1;
        }

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

    <!-- Memanggil Navbar -->
    <?= $this->include('places/partials/navbar') ?>

    <div class="main">
        <!-- Memanggil Sidebar Filter -->
        <?= $this->include('places/partials/sidebar') ?>

        <!-- Memanggil Peta & Script-nya -->
        <?= $this->include('places/map') ?>

        <!-- Memanggil Panel List Kuliner -->
        <?= $this->include('places/place') ?>
    </div>

</body>

</html>