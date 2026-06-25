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
        $placeModel = new PlaceModel();
        $reviewModel = new ReviewModel();
        $userModel = new UserModel();
        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();

        $data = [
            'title' => 'Dashboard Admin',
            'total_places' => $placeModel->countAll(),
            'total_reviews' => $reviewModel->countAll(),
            'total_users' => $userModel->countAll(),
            'total_categories' => $categoryModel->countAll(),
            'total_tags' => $tagModel->countAll(),
            'unverified' => $placeModel
                ->where('is_verified', 0)
                ->countAllResults(),
            'recent_places' => $placeModel
                ->orderBy('created_at', 'DESC')
                ->findAll(5),
            'recent_reviews' => $reviewModel
                ->orderBy('created_at', 'DESC')
                ->findAll(5),
        ];

        return view('admin/AdminDashboard', $data);
    }
}
