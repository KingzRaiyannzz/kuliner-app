<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlaceModel;

class AdminPlaceController extends BaseController
{
    protected $placeModel;

    public function __construct()
    {
        $this->placeModel = new PlaceModel();
    }

    public function index()
    {
        $data['places'] = $this->placeModel->findAll();

        return view('admin/places/index', $data);
    }

    public function verify(int $id)
    {
        $place = $this->placeModel->find($id);

        if (!$place) {
            return redirect()
                ->to('/admin/places')
                ->with('error', 'Tempat tidak ditemukan.');
        }

        $this->placeModel->update($id, [
            'is_verified' => 1
        ]);

        return redirect()
            ->to('/admin/places')
            ->with('success', 'Tempat berhasil diverifikasi.');
    }

    public function destroy(int $id)
    {
        $place = $this->placeModel->find($id);

        if (!$place) {
            return redirect()
                ->to('/admin/places')
                ->with('error', 'Tempat tidak ditemukan.');
        }

        $this->placeModel->delete($id);

        return redirect()
            ->to('/admin/places')
            ->with('success', 'Tempat berhasil dihapus.');
    }
}
