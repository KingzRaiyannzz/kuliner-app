<?php

namespace App\Controllers;

use App\Models\ReviewModel;
use App\Models\PlaceModel;

class ReviewController extends BaseController
{
    protected ReviewModel $reviewModel;
    protected PlaceModel  $placeModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->placeModel  = new PlaceModel();
    }

    // ----------------------------------------------------------------
    // POST /reviews
    // Simpan review baru ke database
    // Dipanggil dari form di halaman detail tempat (places/show.php)
    // ----------------------------------------------------------------
    public function store()
    {
        // Harus login
        if (!session()->get('user_id')) {
            return redirect()->to('/login')
                ->with('error', 'Login dulu untuk memberikan ulasan.');
        }

        $placeId = (int) $this->request->getPost('place_id');
        $userId  = (int) session()->get('user_id');

        // Pastikan tempat ada
        $place = $this->placeModel->find($placeId);
        if (!$place) {
            return redirect()->to('/places')
                ->with('error', 'Tempat tidak ditemukan.');
        }

        // Cek apakah user sudah pernah review tempat ini
        if ($this->reviewModel->hasReviewed($userId, $placeId)) {
            return redirect()->to('/places/' . $placeId)
                ->with('error', 'Kamu sudah pernah memberikan ulasan untuk tempat ini.');
        }

        // Validasi input
        $rules = [
            'place_id' => 'required|is_natural_no_zero',
            'rating'   => 'required|in_list[1,2,3,4,5]',
            'comment'  => 'permit_empty|min_length[5]|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('review_errors', $this->validator->getErrors());
            return redirect()->to('/places/' . $placeId . '#review-form');
        }

        // Upload foto review (opsional)
        $photoPath = null;
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($photo->getMimeType(), $allowedTypes)) {
                session()->setFlashdata('review_errors', ['photo' => 'Format foto harus JPG, PNG, atau WebP.']);
                return redirect()->to('/places/' . $placeId . '#review-form');
            }
            if ($photo->getSizeByUnit('mb') > 2) {
                session()->setFlashdata('review_errors', ['photo' => 'Ukuran foto maksimal 2MB.']);
                return redirect()->to('/places/' . $placeId . '#review-form');
            }

            $uploadPath = FCPATH . 'uploads/reviews';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            $newName = $photo->getRandomName();
            $photo->move($uploadPath, $newName);
            $photoPath = 'uploads/reviews/' . $newName;
        }

        // Simpan review ke database
        $this->reviewModel->insert([
            'user_id'  => $userId,
            'place_id' => $placeId,
            'rating'   => (int) $this->request->getPost('rating'),
            'comment'  => $this->request->getPost('comment'),
            'photo'    => $photoPath,
        ]);

        // Recalculate avg_rating di tabel places
        $this->placeModel->recalcAvgRating($placeId);

        return redirect()->to('/places/' . $placeId . '#reviews')
            ->with('success', 'Ulasan berhasil ditambahkan. Terima kasih! 🎉');
    }

    // ----------------------------------------------------------------
    // POST /reviews/{id}/delete
    // Hapus review milik sendiri
    // ----------------------------------------------------------------
    public function destroy(int $id)
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $review = $this->reviewModel->find($id);

        if (!$review) {
            return redirect()->back()->with('error', 'Ulasan tidak ditemukan.');
        }

        // Hanya pemilik review yang boleh menghapus
        if ((int) $review['user_id'] !== (int) $userId) {
            return redirect()->back()->with('error', 'Kamu tidak bisa menghapus ulasan orang lain.');
        }

        $placeId = $review['place_id'];

        // Hapus foto dari storage jika ada
        if ($review['photo'] && file_exists(FCPATH . $review['photo'])) {
            unlink(FCPATH . $review['photo']);
        }

        $this->reviewModel->delete($id);

        // Recalculate avg_rating setelah review dihapus
        $this->placeModel->recalcAvgRating($placeId);

        return redirect()->to('/places/' . $placeId . '#reviews')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
