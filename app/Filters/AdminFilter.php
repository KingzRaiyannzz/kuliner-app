<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * AdminFilter
 * Memastikan user yang mengakses adalah admin.
 * Jika bukan admin → redirect ke /places dengan pesan error
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Harus login dulu
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('redirect_url', current_url());
            session()->setFlashdata('error', 'Kamu harus login terlebih dahulu.');
            return redirect()->to('/login');
        }

        // Harus role admin
        if (session()->get('user_role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak. Halaman ini hanya untuk admin.');
            return redirect()->to('/places');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
