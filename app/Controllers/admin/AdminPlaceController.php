<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlaceModel;

class AdminPlaceController extends BaseController
{

    public function index()
    {
        // Contoh jika ingin mengambil data dari model
        $placeModel = new PlaceModel();
        $data['places'] = $placeModel->findAll();

        // Arahkan ke file view admin tempat kamu
        return view('admin/places/index', $data); 
    }
    // Verifikasi tempat kuliner
    public function verify(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (!$place) {
            return redirect()->to('/admin')
                ->with('error', 'Tempat tidak ditemukan.');
        }

        $placeModel->update($id, ['is_verified' => 1]);

        return redirect()->to('/admin')
            ->with('success', "Tempat \"{$place['name']}\" berhasil diverifikasi. ✅");
    }

    // Hapus tempat oleh admin
    public function destroy(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (!$place) {
            return redirect()->to('/admin')
                ->with('error', 'Tempat tidak ditemukan.');
        }

        $placeModel->delete($id);

        return redirect()->to('/admin')
            ->with('success', "Tempat \"{$place['name']}\" berhasil dihapus.");
    }
}
