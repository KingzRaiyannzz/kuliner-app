<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Kuliner App</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0fdf4;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e5e5;
            padding: 36px 32px;
            width: 100%;
            max-width: 400px;
        }

        .logo {
            text-align: center;
            font-size: 36px;
            margin-bottom: 8px;
        }

        .app-name {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #111;
            margin-bottom: 4px;
        }

        .app-sub {
            text-align: center;
            font-size: 13px;
            color: #888;
            margin-bottom: 28px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #dc2626;
            margin-bottom: 16px;
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #166534;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            transition: border-color .2s;
        }

        input:focus {
            outline: none;
            border-color: #1D9E75;
            box-shadow: 0 0 0 3px rgba(29, 158, 117, .1);
        }

        input.error {
            border-color: #e53e3e;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #1D9E75;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
            margin-top: 4px;
        }

        .btn:hover {
            background: #0F6E56;
        }

        .divider {
            text-align: center;
            font-size: 13px;
            color: #aaa;
            margin: 18px 0;
        }

        .link-btn {
            display: block;
            text-align: center;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #555;
            text-decoration: none;
            transition: background .2s;
        }

        .link-btn:hover {
            background: #f9fafb;
        }

        .bottom-text {
            text-align: center;
            font-size: 13px;
            color: #888;
            margin-top: 20px;
        }

        .bottom-text a {
            color: #1D9E75;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="logo">🍜</div>
        <div class="app-name">Kuliner App</div>
        <div class="app-sub">Temukan jajanan terbaik di sekitarmu</div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success">✅ <?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <?php foreach ($errors as $e): ?><p>⚠️ <?= esc($e) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    placeholder="email@kamu.com"
                    value="<?= esc($old['email'] ?? '') ?>"
                    class="<?= isset($errors['email']) ? 'error' : '' ?>"
                    autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                    placeholder="Masukkan password..."
                    autocomplete="current-password">
            </div>

            <button type="submit" class="btn">Masuk</button>
        </form>

        <div class="divider">atau</div>
        <a href="/places" class="link-btn">🗺️ Lihat peta tanpa login</a>

        <div class="bottom-text">
            Belum punya akun? <a href="/register">Daftar sekarang</a>
        </div>
    </div>
</body>

</html>