<?php

namespace App\Controllers;

class ApiController extends BaseController
{
    /**
     * GET /api/kuliner
     * Parameter opsional:
     *   ?lat=x&lng=y&radius=km  → filter berdasarkan jarak
     *   ?category=slug          → filter kategori
     *   ?tag=slug               → filter tag
     *   ?search=keyword         → cari nama
     *   ?limit=10               → batas hasil
     */
    public function index()
    {
        // Set header JSON
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');

        $lat      = $this->request->getGet('lat');
        $lng      = $this->request->getGet('lng');
        $radius   = (float)($this->request->getGet('radius') ?? 5);
        $category = $this->request->getGet('category');
        $tag      = $this->request->getGet('tag');
        $search   = $this->request->getGet('search');
        $limit    = min((int)($this->request->getGet('limit') ?? 10), 50);

        // Validasi koordinat jika disertakan
        if (($lat && !is_numeric($lat)) || ($lng && !is_numeric($lng))) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Parameter lat dan lng harus berupa angka desimal.',
                'data'    => [],
            ]);
        }

        // Build query
        if ($lat && $lng) {
            // Gunakan formula Haversine untuk hitung jarak dalam KM
            $latF   = (float) $lat;
            $lngF   = (float) $lng;
            $select = "places.*, 
                       categories.name  AS category_name,
                       categories.slug  AS category_slug,
                       (6371 * ACOS(
                           COS(RADIANS({$latF})) * COS(RADIANS(places.latitude)) *
                           COS(RADIANS(places.longitude) - RADIANS({$lngF})) +
                           SIN(RADIANS({$latF})) * SIN(RADIANS(places.latitude))
                       )) AS distance_km";

            $builder = $this->db->table('places')
                ->select($select)
                ->join('place_categories', 'place_categories.place_id = places.id', 'left')
                ->join('categories',       'categories.id = place_categories.category_id', 'left')
                ->where('places.deleted_at IS NULL')
                ->where('places.latitude IS NOT NULL')
                ->having("distance_km <=", $radius)
                ->orderBy('distance_km', 'ASC')
                ->limit($limit);
        } else {
            $builder = $this->db->table('places')
                ->select('places.*, categories.name AS category_name, categories.slug AS category_slug')
                ->join('place_categories', 'place_categories.place_id = places.id', 'left')
                ->join('categories',       'categories.id = place_categories.category_id', 'left')
                ->where('places.deleted_at IS NULL')
                ->orderBy('places.avg_rating', 'DESC')
                ->limit($limit);
        }

        // Filter tambahan
        if ($category) {
            $builder->where('categories.slug', $category);
        }

        if ($tag) {
            $builder->join('place_tags', 'place_tags.place_id = places.id', 'inner')
                ->join('tags',       'tags.id = place_tags.tag_id',     'inner')
                ->where('tags.slug', $tag);
        }

        if ($search) {
            $builder->groupStart()
                ->like('places.name',    $search)
                ->orLike('places.address', $search)
                ->groupEnd();
        }

        $results = $builder->get()->getResultArray();

        // Format response
        $data = array_map(function ($row) {
            return [
                'id'          => (int)  $row['id'],
                'name'        => $row['name'],
                'description' => $row['description'],
                'address'     => $row['address'],
                'latitude'    => (float) $row['latitude'],
                'longitude'   => (float) $row['longitude'],
                'avg_rating'  => (float) $row['avg_rating'],
                'is_verified' => (bool)  $row['is_verified'],
                'category'    => $row['category_name'] ?? null,
                'thumbnail'   => $row['thumbnail']
                    ? base_url($row['thumbnail'])
                    : null,
                'distance_km' => isset($row['distance_km'])
                    ? round((float)$row['distance_km'], 2)
                    : null,
            ];
        }, $results);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => count($data) . ' tempat ditemukan',
            'total'   => count($data),
            'params'  => [
                'lat'      => $lat ? (float) $lat : null,
                'lng'      => $lng ? (float) $lng : null,
                'radius'   => $lat ? $radius : null,
                'category' => $category,
                'tag'      => $tag,
                'search'   => $search,
                'limit'    => $limit,
            ],
            'data'    => $data,
        ]);
    }

    /**
     * GET /api/kuliner/{id}
     * Detail satu tempat
     */
    public function show(int $id)
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');

        $place = $this->db->table('places p')
            ->select('p.*, u.name AS author')
            ->join('users u', 'u.id = p.user_id', 'left')
            ->where('p.id', $id)
            ->where('p.deleted_at IS NULL')
            ->get()->getRowArray();

        if (!$place) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Tempat tidak ditemukan.',
                'data'    => null,
            ]);
        }

        // Ambil reviews
        $reviews = $this->db->table('reviews r')
            ->select('r.rating, r.comment, r.created_at, u.name AS reviewer')
            ->join('users u', 'u.id = r.user_id', 'left')
            ->where('r.place_id', $id)
            ->orderBy('r.created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'id'          => (int)   $place['id'],
                'name'        => $place['name'],
                'description' => $place['description'],
                'address'     => $place['address'],
                'latitude'    => (float) $place['latitude'],
                'longitude'   => (float) $place['longitude'],
                'avg_rating'  => (float) $place['avg_rating'],
                'is_verified' => (bool)  $place['is_verified'],
                'author'      => $place['author'],
                'thumbnail'   => $place['thumbnail'] ? base_url($place['thumbnail']) : null,
                'reviews'     => $reviews,
            ],
        ]);
    }
}
