<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Daftarkan alias untuk setiap filter
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,

        // ── Filter custom project kuliner ──────────────────────
        // Cek user sudah login
        'auth'          => \App\Filters\AuthFilter::class,
        // Cek user adalah admin
        'admin'         => \App\Filters\AdminFilter::class,
        // Cek user adalah kontributor atau admin
        'kontributor'   => \App\Filters\KontributorFilter::class,
    ];

    /**
     * Filter yang berjalan di semua route
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            'csrf' => ['except' => ['api/*']],
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * Filter berdasarkan HTTP method
     */
    public array $methods = [];

    /**
     * Filter berdasarkan pattern route
     * Ini tidak dipakai — kita pakai filter di Routes.php supaya lebih jelas
     */
    public array $filters = [];
}
