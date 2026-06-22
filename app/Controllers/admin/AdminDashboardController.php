<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlaceModel;
use App\Models\ReviewModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\TagModel;

class AdminDashboardController extends BaseController
{
    public function index()
    {
        $placeModel    = new PlaceModel();
        $reviewModel   = new ReviewModel();
        $userModel     = new UserModel();
        $categoryModel = new CategoryModel();
        $tagModel      = new TagModel();

        return view('admin/AdminDashboard', [
            'title'          => 'Dashboard Admin',
            'total_places'   => $placeModel->countAll(),
            'total_reviews'  => $reviewModel->countAll(),
            'total_users'    => $userModel->countAll(),
            'total_categories' => $categoryModel->countAll(),
            'total_tags'     => $tagModel->countAll(),
            'unverified'     => $placeModel->where('is_verified', 0)->countAllResults(),
            'recent_places'  => $placeModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'recent_reviews' => $reviewModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
        ]);
    }

    // Verifikasi tempat
    public function verify(int $id)
    {
        $placeModel = new PlaceModel();
        $place = $placeModel->find($id);

        if (!$place) {
            return redirect()->to('/admin')->with('error', 'Tempat tidak ditemukan.');
        }

        $placeModel->update($id, ['is_verified' => 1]);
        return redirect()->to('/admin')->with('success', "Tempat \"{$place['name']}\" berhasil diverifikasi.");
    }
}
