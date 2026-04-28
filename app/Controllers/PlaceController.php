<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\ReviewModel;

class PlaceController extends BaseController
{
    protected PlaceModel $placeModel;
    protected CategoryModel $categoryModel;
    protected TagModel $tagModel;

    public function __construct()
    {
        $this->placeModel = new PlaceModel();
        $this->categoryModel = new CategoryModel();
        $this->tagModel = new TagModel();
    }

    // ----------------------------------------------------------------
    // GET /places
    // Tampilkan daftar semua tempat + peta Leaflet
    // ----------------------------------------------------------------
    public function index()
    {
        // Ambil filter dari query string (?search=...&category=...&tag=...&min_rating=...)
        $filters = [
            'search'     => $this->request->getGet('search'),
            'category'   => $this->request->getGet('category'),
            'tag'        => $this->request->getGet('tag'),
            'min_rating' => $this->request->getGet('min_rating'),
            'sort'       => $this->request->getGet('sort') ?? 'created_at',
            'page'       => $this->request->getGet('page') ?? 1,
        ];

        $result = $this->placeModel->getWithFilters($filters, 12);

        return view('places/index', [
            'title'      => 'Temukan Kuliner',
            'places'     => $result['data'],
            'pagination' => $result,
            'filters'    => $filters,
            'categories' => $this->categoryModel->getWithCount(),
            'tags'       => $this->tagModel->getWithCount(),
            // Data JSON untuk marker peta Leaflet
            'mapData'    => json_encode($this->placeModel->getForMap()),
        ]);
    }

    // ----------------------------------------------------------------
    // GET /places/create
    // Tampilkan form tambah tempat baru
    // ----------------------------------------------------------------
    public function create()
    {
        // Cek login — hanya user yang login bisa tambah tempat
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Login dulu untuk menambahkan tempat.');
        }

        return view('places/create', [
            'title'      => 'Tambah Tempat Kuliner',
            'categories' => $this->categoryModel->findAll(),
            'tags'       => $this->tagModel->findAll(),
            // Kirim balik data lama jika form disubmit tapi gagal validasi
            'old'        => session()->getFlashdata('old_input') ?? [],
            'errors'     => session()->getFlashdata('errors') ?? [],
        ]);
    }

    // ----------------------------------------------------------------
    // POST /places
    // Proses simpan tempat baru ke database
    // ----------------------------------------------------------------
    public function store()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }

        // Validasi input
        $rules = [
            'name'        => 'required|min_length[3]|max_length[150]',
            'address'     => 'required|min_length[5]',
            'latitude'    => 'required|decimal',
            'longitude'   => 'required|decimal',
            'description' => 'permit_empty|max_length[1000]',
            'categories'  => 'permit_empty',
            'tags'        => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            // Simpan error dan data lama ke flash session, lalu redirect kembali ke form
            session()->setFlashdata('errors',    $this->validator->getErrors());
            session()->setFlashdata('old_input', $this->request->getPost());
            return redirect()->back();
        }

        // Upload thumbnail (opsional)
        $thumbnailPath = null;
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/places', $newName);
            $thumbnailPath = 'uploads/places/' . $newName;
        }

        // Simpan data utama ke tabel places
        $placeId = $this->placeModel->insert([
            'user_id'      => session()->get('user_id'),
            'name'         => $this->request->getPost('name'),
            'description'  => $this->request->getPost('description'),
            'address'      => $this->request->getPost('address'),
            'latitude'     => $this->request->getPost('latitude'),
            'longitude'    => $this->request->getPost('longitude'),
            'osm_place_id' => $this->request->getPost('osm_place_id'),
            'thumbnail'    => $thumbnailPath,
        ]);

        // Simpan relasi categories (tabel pivot place_categories)
        $categoryIds = $this->request->getPost('categories') ?? [];
        if (!empty($categoryIds)) {
            $this->placeModel->syncCategories($placeId, $categoryIds);
        }

        // Simpan relasi tags — bisa dari checkbox (id) atau input teks (nama baru)
        $tagIds = [];
        $tagInputs = $this->request->getPost('tags') ?? [];
        foreach ($tagInputs as $input) {
            // Jika input berupa angka = id tag existing, jika teks = buat tag baru
            if (is_numeric($input)) {
                $tagIds[] = (int) $input;
            } else {
                $tagIds[] = (new TagModel())->findOrCreate($input);
            }
        }
        if (!empty($tagIds)) {
            $this->placeModel->syncTags($placeId, $tagIds);
        }

        return redirect()->to('/places/' . $placeId)
            ->with('success', 'Tempat kuliner berhasil ditambahkan!');
    }

    // ----------------------------------------------------------------
    // GET /places/{id}
    // Tampilkan halaman detail + peta + semua review
    // ----------------------------------------------------------------
    public function show(int $id)
    {
        $place = $this->placeModel->getDetail($id);

        if (!$place) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Tempat dengan ID ' . $id . ' tidak ditemukan.'
            );
        }

        $reviewModel = new ReviewModel();

        return view('places/show', [
            'title'        => $place['name'],
            'place'        => $place,
            'reviews'      => $reviewModel->getByPlace($id, 10),
            'distribution' => $reviewModel->getRatingDistribution($id),
            // Cek apakah user yang sedang login sudah pernah review
            'hasReviewed'  => session()->get('user_id')
                ? $reviewModel->hasReviewed(session()->get('user_id'), $id)
                : false,
        ]);
    }

    // ----------------------------------------------------------------
    // GET /places/{id}/edit
    // ----------------------------------------------------------------
    public function edit(int $id)
    {
        $place = $this->placeModel->find($id);

        if (!$place || $place['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/places')->with('error', 'Kamu tidak punya akses untuk mengedit tempat ini.');
        }

        return view('places/edit', [
            'title'      => 'Edit: ' . $place['name'],
            'place'      => $this->placeModel->getDetail($id),
            'categories' => $this->categoryModel->findAll(),
            'tags'       => $this->tagModel->findAll(),
        ]);
    }

    // ----------------------------------------------------------------
    // POST /places/{id}/update
    // ----------------------------------------------------------------
    public function update(int $id)
    {
        $place = $this->placeModel->find($id);

        if (!$place || $place['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/places')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'name'    => 'required|min_length[3]|max_length[150]',
            'address' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back();
        }

        $data = [
            'name'         => $this->request->getPost('name'),
            'description'  => $this->request->getPost('description'),
            'address'      => $this->request->getPost('address'),
            'latitude'     => $this->request->getPost('latitude'),
            'longitude'    => $this->request->getPost('longitude'),
        ];

        // Ganti thumbnail jika ada upload baru
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(WRITEPATH . 'uploads/places', $newName);
            $data['thumbnail'] = 'uploads/places/' . $newName;
        }

        $this->placeModel->update($id, $data);
        $this->placeModel->syncCategories($id, $this->request->getPost('categories') ?? []);
        $this->placeModel->syncTags($id, $this->request->getPost('tags') ?? []);

        return redirect()->to('/places/' . $id)->with('success', 'Berhasil diupdate!');
    }

    // ----------------------------------------------------------------
    // POST /places/{id}/delete
    // Soft delete — data tidak benar-benar dihapus dari database
    // ----------------------------------------------------------------
    public function delete(int $id)
    {
        $place = $this->placeModel->find($id);

        if (!$place || $place['user_id'] !== session()->get('user_id')) {
            return redirect()->to('/places')->with('error', 'Akses ditolak.');
        }

        $this->placeModel->delete($id); // soft delete — mengisi kolom deleted_at

        return redirect()->to('/places')->with('success', 'Tempat berhasil dihapus.');
    }
}
