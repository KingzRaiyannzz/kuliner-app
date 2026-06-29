<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * KontributorFilter
 * Memastikan user adalah kontributor atau admin.
 * Pengunjung (tanpa login atau role pengunjung) tidak bisa akses.
 */
class KontributorFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('redirect_url', current_url());
            session()->setFlashdata('error', 'Kamu harus login untuk melakukan aksi ini.');
            return redirect()->to('/login');
        }

        $role = session()->get('user_role');

        // Pengunjung hanya bisa browse — tidak bisa tambah atau review
        if ($role === 'pengunjung') {
            session()->setFlashdata('error', 'Fitur ini hanya untuk Kontributor. Upgrade akunmu terlebih dahulu.');
            return redirect()->to('/places');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
