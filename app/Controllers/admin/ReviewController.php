<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewModel;

class ReviewController extends BaseController
{
    public function index()
    {
        $reviewModel = new ReviewModel();

        $reviews = $reviewModel
            ->select('reviews.*, users.name AS user_name, places.name AS place_name')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->join('places', 'places.id = reviews.place_id', 'left')
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();

        return view('admin/reviews/index', [
            'title' => 'Kelola Review',
            'reviews' => $reviews
        ]);
    }

    public function destroy(int $id)
    {
        $reviewModel = new ReviewModel();
        $review = $reviewModel->find($id);

        if (!$review) {
            return redirect()
                ->to('/admin/reviews')
                ->with('error', 'Review tidak ditemukan.');
        }

        $reviewModel->delete($id);

        return redirect()
            ->to('/admin/reviews')
            ->with('success', 'Review berhasil dihapus.');
    }
}
