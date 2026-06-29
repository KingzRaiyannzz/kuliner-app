<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * AuthFilter
 * Memastikan user sudah login sebelum mengakses route tertentu.
 * Jika belum login → redirect ke /login
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            // Simpan URL yang ingin diakses, supaya setelah login bisa redirect balik
            session()->setFlashdata('redirect_url', current_url());
            session()->setFlashdata('error', 'Kamu harus login terlebih dahulu.');

            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada aksi setelah response
    }
}
