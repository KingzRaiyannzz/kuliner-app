<?php

namespace App\Controllers;

use App\Models\PlaceModel;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\ReviewModel;

class PlaceController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // ... (Tetap gunakan kode index Anda yang sebelumnya) ...
        $search     = $this->request->getGet('search') ?? '';
        $category   = $this->request->getGet('category') ?? '';
        $tag        = $this->request->getGet('tag') ?? '';
        $minRating  = $this->request->getGet('min_rating') ?? 0;
        $sort       = $this->request->getGet('sort') ?? 'created_at';
        $page       = max((int)($this->request->getGet('page') ?? 1), 1);
        $perPage    = 10;

        $filters = [
            'search'     => $search,
            'category'   => $category,
            'tag'        => $tag,
            'min_rating' => $minRating,
            'sort'       => $sort,
            'page'       => $page
        ];

        $builder = $this->db->table('places p')
            ->select('p.*, 
                      COALESCE(AVG(r.rating), 0) as avg_rating, 
                      COUNT(r.id) as review_count,
                      GROUP_CONCAT(DISTINCT c.name SEPARATOR ", ") as category_names')
            ->join('reviews r', 'r.place_id = p.id', 'left')
            ->join('place_categories pc', 'pc.place_id = p.id', 'left')
            ->join('categories c', 'c.id = pc.category_id', 'left')
            ->join('place_tags pt', 'pt.place_id = p.id', 'left')
            ->join('tags t', 't.id = pt.tag_id', 'left')
            ->groupBy('p.id');

        if (!empty($search)) {
            $builder->like('p.name', $search)->orLike('p.address', $search);
        }
        if (!empty($category)) {
            $builder->where('c.slug', $category);
        }
        if (!empty($tag)) {
            $builder->where('t.slug', $tag);
        }

        if ($minRating > 0) {
            $builder->having('avg_rating >=', $minRating);
        }

        $mapBuilder = clone $builder;

        switch ($sort) {
            case 'avg_rating':
                $builder->orderBy('avg_rating', 'DESC');
                break;
            case 'review_count':
                $builder->orderBy('review_count', 'DESC');
                break;
            case 'name':
                $builder->orderBy('p.name', 'ASC');
                break;
            case 'created_at':
            default:
                $builder->orderBy('p.created_at', 'DESC');
                break;
        }

        $totalPlacesResult = $mapBuilder->get()->getResultArray();
        $totalItems        = count($totalPlacesResult);
        $lastPage          = max(ceil($totalItems / $perPage), 1);
        $offset            = ($page - 1) * $perPage;

        $places = $builder->limit($perPage, $offset)->get()->getResultArray();

        $pagination = [
            'total'     => $totalItems,
            'page'      => $page,
            'last_page' => $lastPage
        ];

        $categories = $this->db->table('categories c')
            ->select('c.*, COUNT(pc.place_id) as place_count')
            ->join('place_categories pc', 'pc.category_id = c.id', 'left')
            ->groupBy('c.id')
            ->get()->getResultArray();

        $tags = $this->db->table('tags')->get()->getResultArray();

        $data = [
            'filters'    => $filters,
            'categories' => $categories,
            'tags'       => $tags,
            'places'     => $places,
            'pagination' => $pagination,
            'mapData'    => json_encode($totalPlacesResult)
        ];

        return view('places/index', $data);
    }

    // ==========================================
    // METHOD CREATE (Disesuaikan dengan kebutuhan View Anda)
    // ==========================================
    public function create()
    {
        $categories = $this->db->table('categories')->get()->getResultArray();
        $tags = $this->db->table('tags')->get()->getResultArray();

        // Mengambil data input lama dan error agar tidak memicu "undefined variable" di view Anda
        $data = [
            'categories' => $categories,
            'tags'       => $tags,
            'title'      => 'Tambah Tempat Kuliner Baru',
            'errors'     => session()->getFlashdata('errors') ?? [],
            'old'        => session()->getFlashdata('_ci_old_input')['post'] ?? []
        ];

        return view('places/create', $data);
    }

    // ==========================================
    // METHOD STORE (Mendukung upload foto & tag dinamis)
    // ==========================================
    public function store()
    {
        // 1. Validasi Input Form
        $rules = [
            'name'        => 'required|min_length[3]',
            'address'     => 'required',
            'latitude'    => 'required',
            'longitude'   => 'required',
            'thumbnail'   => 'max_size[thumbnail,2048]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Proses Upload Gambar (jika ada file yang diunggah)
        $thumbnailName = null;
        $fileFile = $this->request->getFile('thumbnail');

        if ($fileFile && $fileFile->isValid() && !$fileFile->hasMoved()) {
            $thumbnailName = $fileFile->getRandomName();
            // File akan disimpan di folder: public/uploads/
            $fileFile->move(FCPATH . 'uploads', $thumbnailName);
        }

        // 3. Siapkan data utama tabel places
        $dataPlace = [
            'user_id'     => session()->get('user_id'),

            'name'        => $this->request->getPost('name'),
            'address'     => $this->request->getPost('address'),
            'description' => $this->request->getPost('description') ?? '',
            'latitude'    => $this->request->getPost('latitude'),
            'longitude'   => $this->request->getPost('longitude'),
            'thumbnail'   => $thumbnailName,

            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        // Mulai database transaction
        $this->db->transStart();

        // Simpan data ke tabel 'places'
        $this->db->table('places')->insert($dataPlace);

        $placeId = $this->db->insertID();

        // 4. Simpan relasi Kategori
        $categories = $this->request->getPost('categories');
        if (!empty($categories) && is_array($categories)) {
            foreach ($categories as $categoryId) {
                $this->db->table('place_categories')->insert([
                    'place_id'    => $placeId,
                    'category_id' => $categoryId
                ]);
            }
        }

        // 5. Simpan relasi Tag (Mendukung ID Angka maupun Teks Tag Baru)
        $tagsInput = $this->request->getPost('tags');
        if (!empty($tagsInput) && is_array($tagsInput)) {
            foreach ($tagsInput as $tagData) {

                if (is_numeric($tagData)) {
                    // Jika data berupa ID angka (Tag yang sudah ada)
                    $tagId = $tagData;
                } else {
                    // Jika data berupa Teks/String (Tag baru dari input custom)
                    // Cek dulu apakah nama tag tersebut sebenarnya sudah ada di database
                    $existingTag = $this->db->table('tags')->where('name', $tagData)->get()->getRowArray();

                    if ($existingTag) {
                        $tagId = $existingTag['id'];
                    } else {
                        // Jika benar-benar baru, buat tag dan slug baru lalu simpan ke tabel tags
                        $slug = strtolower(url_title($tagData));
                        $this->db->table('tags')->insert([
                            'name' => $tagData,
                            'slug' => $slug
                        ]);
                        $tagId = $this->db->insertID();
                    }
                }

                // Masukkan relasi ke tabel pivot 'place_tags'
                $this->db->table('place_tags')->insert([
                    'place_id' => $placeId,
                    'tag_id'   => $tagId
                ]);
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data kuliner.');
        }

        return redirect()->to('/places/' . $placeId)
            ->with('success', 'Tempat kuliner berhasil ditambahkan!');
    }

    public function show($id)
    {
        $placeModel  = new \App\Models\PlaceModel();
        $reviewModel = new \App\Models\ReviewModel();

        $place = $placeModel->getDetail((int)$id);

        //dd($place);

        if (!$place) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $reviews = $this->db->table('reviews r')
            ->select('r.*, u.name as reviewer_name')
            ->join('users u', 'u.id = r.user_id', 'left')
            ->where('r.place_id', $id)
            ->orderBy('r.id', 'DESC')
            ->get()
            ->getResultArray();

        $distribution = [];

        foreach ([1, 2, 3, 4, 5] as $star) {
            $distribution[$star] = $this->db->table('reviews')
                ->where('place_id', $id)
                ->where('rating', $star)
                ->countAllResults();
        }

        $hasReviewed = false;

        if (session()->get('user_id')) {
            $hasReviewed = $this->db->table('reviews')
                ->where('user_id', session()->get('user_id'))
                ->where('place_id', $id)
                ->countAllResults() > 0;
        }

        return view('places/show', [
            'place'        => $place,
            'reviews'      => $reviews,
            'distribution' => $distribution,
            'hasReviewed'  => $hasReviewed
        ]);
    }

    public function edit($id) // method untuk edit tempat kuliner
    {
        $placeModel = new PlaceModel();

        $place = $placeModel->find($id);

        if (!$place) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'place' => $place,
            'categories' => $this->db->table('categories')->get()->getResultArray(),
            'tags' => $this->db->table('tags')->get()->getResultArray()
        ];

        return view('places/edit', $data);
    }

    public function update($id) // method untuk uppdate tempat kuliner
    {
        $placeModel = new PlaceModel();

        $place = $placeModel->find($id);

        if (!$place) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'address'     => $this->request->getPost('address'),
            'description' => $this->request->getPost('description'),
            'latitude'    => $this->request->getPost('latitude'),
            'longitude'   => $this->request->getPost('longitude'),
        ];

        $placeModel->update($id, $data);

        return redirect()->to('/places/' . $id)
            ->with('success', 'Tempat berhasil diperbarui.');
    }

    public function delete($id)
    {
        $placeModel = new PlaceModel();

        $place = $placeModel->find($id);

        if (!$place) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $placeModel->delete($id);

        return redirect()->to('/places')
            ->with('success', 'Tempat berhasil dihapus.');
    }
}
