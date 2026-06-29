<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Kuliner App</title>
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
            max-width: 420px;
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

        .form-group {
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }

        input[type="text"],
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

        /* Indikator kekuatan password */
        .pwd-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 6px;
            background: #f0f0f0;
            overflow: hidden;
        }

        .pwd-bar {
            height: 100%;
            width: 0;
            border-radius: 2px;
            transition: width .3s, background .3s;
        }

        .pwd-label {
            font-size: 11px;
            color: #aaa;
            margin-top: 3px;
            min-height: 14px;
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
            margin-top: 6px;
        }

        .btn:hover {
            background: #0F6E56;
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
        <div class="app-name">Buat Akun</div>
        <div class="app-sub">Bergabung dan tambahkan tempat kuliner favoritmu</div>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <?php foreach ($errors as $e): ?><p>⚠️ <?= esc($e) ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name"
                    placeholder="Nama kamu..."
                    value="<?= esc($old['name'] ?? '') ?>"
                    class="<?= isset($errors['name']) ? 'error' : '' ?>">
            </div>

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
                    placeholder="Minimal 8 karakter..."
                    autocomplete="new-password">
                <div class="pwd-strength">
                    <div class="pwd-bar" id="pwd-bar"></div>
                </div>
                <div class="pwd-label" id="pwd-label"></div>
            </div>

            <div class="form-group">
                <label for="password_confirm">Konfirmasi Password</label>
                <input type="password" id="password_confirm" name="password_confirm"
                    placeholder="Ulangi password..."
                    class="<?= isset($errors['password_confirm']) ? 'error' : '' ?>"
                    autocomplete="new-password">
                <div class="pwd-label" id="match-label"></div>
            </div>

            <button type="submit" class="btn">Daftar Sekarang</button>
        </form>

        <div class="bottom-text">
            Sudah punya akun? <a href="/login">Masuk</a>
        </div>
    </div>

    <script>
        // Indikator kekuatan password
        const pwdInput = document.getElementById('password');
        const pwdBar = document.getElementById('pwd-bar');
        const pwdLabel = document.getElementById('pwd-label');
        const confInput = document.getElementById('password_confirm');
        const matchLbl = document.getElementById('match-label');

        const levels = [{
                min: 0,
                color: '#e53e3e',
                label: 'Terlalu pendek',
                width: '20%'
            },
            {
                min: 6,
                color: '#f59e0b',
                label: 'Lemah',
                width: '40%'
            },
            {
                min: 8,
                color: '#3b82f6',
                label: 'Cukup',
                width: '60%'
            },
            {
                min: 10,
                color: '#1D9E75',
                label: 'Kuat',
                width: '80%'
            },
            {
                min: 12,
                color: '#0F6E56',
                label: 'Sangat kuat 💪',
                width: '100%'
            },
        ];

        pwdInput.addEventListener('input', function() {
            const len = this.value.length;
            const lvl = [...levels].reverse().find(l => len >= l.min) || levels[0];
            pwdBar.style.width = len === 0 ? '0' : lvl.width;
            pwdBar.style.background = lvl.color;
            pwdLabel.textContent = len === 0 ? '' : lvl.label;
            checkMatch();
        });

        confInput.addEventListener('input', checkMatch);

        function checkMatch() {
            if (!confInput.value) {
                matchLbl.textContent = '';
                return;
            }
            if (confInput.value === pwdInput.value) {
                matchLbl.style.color = '#1D9E75';
                matchLbl.textContent = '✓ Password cocok';
            } else {
                matchLbl.style.color = '#e53e3e';
                matchLbl.textContent = '✗ Password tidak cocok';
            }
        }
    </script>
</body>

</html>