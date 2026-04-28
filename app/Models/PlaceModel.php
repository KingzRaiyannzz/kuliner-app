<?php

namespace App\Models;

use CodeIgniter\Model;

class PlaceModel extends Model
{
    protected $table            = 'places';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields = [
        'user_id',
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'osm_place_id',
        'thumbnail',
        'avg_rating',
        'is_verified',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'name'        => 'required|min_length[3]|max_length[150]',
        'address'     => 'required|min_length[5]',
        'latitude'    => 'required|decimal',
        'longitude'   => 'required|decimal',
        'description' => 'permit_empty|max_length[1000]',
    ];

    protected $validationMessages = [
        'name'     => ['required' => 'Nama tempat wajib diisi.'],
        'address'  => ['required' => 'Alamat wajib diisi.'],
        'latitude' => ['required' => 'Koordinat latitude wajib ada (gunakan fitur pencarian lokasi).'],
        'longitude' => ['required' => 'Koordinat longitude wajib ada (gunakan fitur pencarian lokasi).'],
    ];

    // ----------------------------------------------------------------
    // Query: ambil semua places beserta kategori, tags, jumlah review
    // Mendukung filter kategori, tag, rating minimum, dan pencarian nama
    // ----------------------------------------------------------------
    public function getWithFilters(array $filters = [], int $perPage = 12): array
    {
        $builder = $this->db->table('places p')
            ->select('p.*, u.name AS author_name,
                      COUNT(DISTINCT r.id) AS review_count,
                      GROUP_CONCAT(DISTINCT c.name ORDER BY c.name SEPARATOR ", ") AS category_names,
                      GROUP_CONCAT(DISTINCT t.name ORDER BY t.name SEPARATOR ", ") AS tag_names')
            ->join('users u',           'u.id = p.user_id',          'left')
            ->join('reviews r',         'r.place_id = p.id',         'left')
            ->join('place_categories pc', 'pc.place_id = p.id',       'left')
            ->join('categories c',      'c.id = pc.category_id',     'left')
            ->join('place_tags pt',     'pt.place_id = p.id',        'left')
            ->join('tags t',            't.id = pt.tag_id',          'left')
            ->where('p.deleted_at IS NULL')
            ->groupBy('p.id');

        // Filter: pencarian nama / alamat
        if (!empty($filters['search'])) {
            $keyword = $this->db->escapeString($filters['search']);
            $builder->groupStart()
                ->like('p.name', $keyword)
                ->orLike('p.address', $keyword)
                ->groupEnd();
        }

        // Filter: kategori (by slug)
        if (!empty($filters['category'])) {
            $builder->join('place_categories pc2', 'pc2.place_id = p.id', 'inner')
                ->join('categories c2', 'c2.id = pc2.category_id', 'inner')
                ->where('c2.slug', $filters['category']);
        }

        // Filter: tag (by slug)
        if (!empty($filters['tag'])) {
            $builder->join('place_tags pt2', 'pt2.place_id = p.id', 'inner')
                ->join('tags t2', 't2.id = pt2.tag_id', 'inner')
                ->where('t2.slug', $filters['tag']);
        }

        // Filter: rating minimum
        if (!empty($filters['min_rating'])) {
            $builder->where('p.avg_rating >=', (float) $filters['min_rating']);
        }

        // Filter: hanya yang sudah diverifikasi
        if (isset($filters['verified']) && $filters['verified']) {
            $builder->where('p.is_verified', 1);
        }

        // Urutan
        $sortOptions = ['avg_rating', 'created_at', 'review_count', 'name'];
        $sort  = in_array($filters['sort'] ?? '', $sortOptions) ? $filters['sort'] : 'created_at';
        $order = ($filters['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $builder->orderBy("p.{$sort}", $order);

        // Pagination manual — kembalikan data + total
        $total   = $builder->countAllResults(false);
        $page    = max(1, (int) ($filters['page'] ?? 1));
        $offset  = ($page - 1) * $perPage;
        $results = $builder->limit($perPage, $offset)->get()->getResultArray();

        return [
            'data'       => $results,
            'total'      => $total,
            'per_page'   => $perPage,
            'page'       => $page,
            'last_page'  => (int) ceil($total / $perPage),
        ];
    }

    // ----------------------------------------------------------------
    // Query: detail satu place beserta reviews, kategori, dan tags
    // ----------------------------------------------------------------
    public function getDetail(int $id): ?array
    {
        $place = $this->db->table('places p')
            ->select('p.*, u.name AS author_name, u.avatar AS author_avatar,
                      COUNT(DISTINCT r.id) AS review_count')
            ->join('users u',   'u.id = p.user_id', 'left')
            ->join('reviews r', 'r.place_id = p.id', 'left')
            ->where('p.id', $id)
            ->where('p.deleted_at IS NULL')
            ->groupBy('p.id')
            ->get()->getRowArray();

        if (!$place) return null;

        // Ambil kategori & tags secara terpisah (lebih bersih dari GROUP_CONCAT)
        $place['categories'] = $this->db->table('place_categories pc')
            ->select('c.id, c.name, c.slug, c.icon')
            ->join('categories c', 'c.id = pc.category_id')
            ->where('pc.place_id', $id)
            ->get()->getResultArray();

        $place['tags'] = $this->db->table('place_tags pt')
            ->select('t.id, t.name, t.slug')
            ->join('tags t', 't.id = pt.tag_id')
            ->where('pt.place_id', $id)
            ->get()->getResultArray();

        return $place;
    }

    // ----------------------------------------------------------------
    // Recalculate avg_rating setelah ada review baru / dihapus
    // Dipanggil oleh ReviewController setelah insert/delete
    // ----------------------------------------------------------------
    public function recalcAvgRating(int $placeId): void
    {
        $result = $this->db->table('reviews')
            ->selectAvg('rating', 'avg')
            ->where('place_id', $placeId)
            ->get()->getRowArray();

        $avg = round((float) ($result['avg'] ?? 0), 1);

        $this->db->table('places')
            ->where('id', $placeId)
            ->update(['avg_rating' => $avg]);
    }

    // ----------------------------------------------------------------
    // Simpan category & tag pivot setelah insert place
    // ----------------------------------------------------------------
    public function syncCategories(int $placeId, array $categoryIds): void
    {
        $this->db->table('place_categories')->where('place_id', $placeId)->delete();
        if (empty($categoryIds)) return;

        $rows = array_map(fn($cid) => ['place_id' => $placeId, 'category_id' => (int) $cid], $categoryIds);
        $this->db->table('place_categories')->insertBatch($rows);
    }

    public function syncTags(int $placeId, array $tagIds): void
    {
        $this->db->table('place_tags')->where('place_id', $placeId)->delete();
        if (empty($tagIds)) return;

        $rows = array_map(fn($tid) => ['place_id' => $placeId, 'tag_id' => (int) $tid], $tagIds);
        $this->db->table('place_tags')->insertBatch($rows);
    }

    // ----------------------------------------------------------------
    // Semua places untuk tampilan peta (hanya kolom yang dibutuhkan Leaflet)
    // ----------------------------------------------------------------
    public function getForMap(): array
    {
        return $this->db->table('places')
            ->select('id, name, address, latitude, longitude, avg_rating, thumbnail')
            ->where('deleted_at IS NULL')
            ->where('latitude IS NOT NULL')
            ->where('longitude IS NOT NULL')
            ->get()->getResultArray();
    }
}
