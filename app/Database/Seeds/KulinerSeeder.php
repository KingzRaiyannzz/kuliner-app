<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KulinerSeeder extends Seeder
{
    public function run()
    {
        // Matikan foreign key check dulu agar truncate bisa jalan
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        $this->db->table('place_tags')->truncate();
        $this->db->table('place_categories')->truncate();
        $this->db->table('reviews')->truncate();
        $this->db->table('places')->truncate();
        $this->db->table('tags')->truncate();
        $this->db->table('categories')->truncate();
        $this->db->table('users')->truncate();

        // Nyalakan kembali foreign key check
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        // ── Categories ────────────────────────────────────────────
        $categories = [
            ['name' => 'Makanan Berat',  'slug' => 'makanan-berat',  'icon' => '🍱'],
            ['name' => 'Jajanan',        'slug' => 'jajanan',        'icon' => '🍢'],
            ['name' => 'Minuman',        'slug' => 'minuman',        'icon' => '🥤'],
            ['name' => 'Gorengan',       'slug' => 'gorengan',       'icon' => '🍟'],
            ['name' => 'Dessert',        'slug' => 'dessert',        'icon' => '🍰'],
            ['name' => 'Bakso & Mie',    'slug' => 'bakso-mie',      'icon' => '🍜'],
            ['name' => 'Nasi & Lauk',    'slug' => 'nasi-lauk',      'icon' => '🍚'],
            ['name' => 'Seafood',        'slug' => 'seafood',        'icon' => '🦐'],
        ];
        $this->db->table('categories')->insertBatch($categories);

        // ── Tags ──────────────────────────────────────────────────
        $tags = [
            ['name' => 'Murah',        'slug' => 'murah'],
            ['name' => 'Enak',         'slug' => 'enak'],
            ['name' => 'Porsi Besar',  'slug' => 'porsi-besar'],
            ['name' => 'Buka Malam',   'slug' => 'buka-malam'],
            ['name' => 'Halal',        'slug' => 'halal'],
            ['name' => 'Pedas',        'slug' => 'pedas'],
            ['name' => 'Dekat Kampus', 'slug' => 'dekat-kampus'],
            ['name' => 'WiFi',         'slug' => 'wifi'],
            ['name' => 'Parkir Luas',  'slug' => 'parkir-luas'],
            ['name' => 'Favorit Mhs',  'slug' => 'favorit-mhs'],
            ['name' => 'AC',           'slug' => 'ac'],
            ['name' => 'Buka Pagi',    'slug' => 'buka-pagi'],
        ];
        $this->db->table('tags')->insertBatch($tags);

        // ── Users ─────────────────────────────────────────────────
        $this->db->table('users')->insertBatch([
            [
                //admin
                'name'          => 'Admin Kuliner',
                'email'         => 'admin@kuliner.com',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'admin',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                //kontributor
                'name'          => 'Budi Santoso',
                'email'         => 'budi@mahasiswa.com',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'kontributor',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Siti Rahayu',
                'email'         => 'siti@mahasiswa.com',
                'password_hash' => password_hash('password', PASSWORD_DEFAULT),
                'role'          => 'kontributor',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ]);

        // Ambil ID dengan query langsung — lebih aman dari insertID()
        $adminId = $this->db->table('users')->where('email', 'admin@kuliner.com')->get()->getRowArray()['id'];
        $budiId  = $this->db->table('users')->where('email', 'budi@mahasiswa.com')->get()->getRowArray()['id'];
        $sitiId  = $this->db->table('users')->where('email', 'siti@mahasiswa.com')->get()->getRowArray()['id'];

        // ── 22 Places ─────────────────────────────────────────────
        $places = [
            ['user_id' => $adminId, 'name' => 'Warung Bu Siti',          'description' => 'Nasi uduk murah meriah, buka dari subuh. Porsi besar dan lauk lengkap.',          'address' => 'Jl. Imam Bonjol No. 12, Semarang',          'latitude' => -6.9934, 'longitude' => 110.4203, 'avg_rating' => 4.5, 'is_verified' => 1],
            ['user_id' => $adminId, 'name' => 'Mie Ayam Pak Budi',        'description' => 'Mie ayam jamur dengan kuah bening yang gurih dan segar.',                         'address' => 'Jl. Pemuda No. 5, Semarang',                'latitude' => -6.9870, 'longitude' => 110.4228, 'avg_rating' => 4.2, 'is_verified' => 1],
            ['user_id' => $adminId, 'name' => 'Es Teh Joss Corner',       'description' => 'Minuman segar aneka rasa, harga mulai 2ribuan. Favorit mahasiswa.',               'address' => 'Jl. Dr. Cipto No. 8, Semarang',             'latitude' => -6.9912, 'longitude' => 110.4187, 'avg_rating' => 4.0, 'is_verified' => 1],
            ['user_id' => $budiId,  'name' => 'Bakso Malang Cak Jo',      'description' => 'Bakso urat dan halus dengan kuah kaldu sapi asli yang kaya rasa.',                'address' => 'Jl. Gajahmada No. 45, Semarang',            'latitude' => -6.9850, 'longitude' => 110.4150, 'avg_rating' => 4.7, 'is_verified' => 1],
            ['user_id' => $budiId,  'name' => 'Gorengan Mpok Inem',       'description' => 'Gorengan hangat: pisang goreng, tempe mendoan, tahu isi. Murah dan renyah.',      'address' => 'Depan Gerbang Barat Universitas',            'latitude' => -6.9945, 'longitude' => 110.4220, 'avg_rating' => 4.1, 'is_verified' => 1],
            ['user_id' => $sitiId,  'name' => 'Kantin Gedung A',          'description' => 'Kantin resmi kampus dengan menu beragam: nasi rames, soto, mie goreng.',         'address' => 'Gedung A Lantai 1, Jl. Nakula 5',           'latitude' => -6.9925, 'longitude' => 110.4195, 'avg_rating' => 3.8, 'is_verified' => 1],
            ['user_id' => $sitiId,  'name' => 'Soto Ayam Pak Dhe',        'description' => 'Soto ayam kampung dengan kuah bening segar. Buka pagi sampai siang.',             'address' => 'Jl. Veteran No. 23, Semarang',              'latitude' => -6.9890, 'longitude' => 110.4210, 'avg_rating' => 4.4, 'is_verified' => 1],
            ['user_id' => $adminId, 'name' => 'Warmindo Mas Agus',        'description' => 'Warung mie indomie dengan berbagai topping. Buka 24 jam.',                        'address' => 'Jl. Ngesrep Timur V No. 10, Semarang',      'latitude' => -7.0521, 'longitude' => 110.4382, 'avg_rating' => 3.9, 'is_verified' => 1],
            ['user_id' => $budiId,  'name' => 'Nasi Goreng Bang Udin',    'description' => 'Nasi goreng spesial dengan telur mata sapi dan kerupuk. Pedasnya pas.',           'address' => 'Jl. Tembalang Baru No. 7, Semarang',        'latitude' => -7.0498, 'longitude' => 110.4367, 'avg_rating' => 4.3, 'is_verified' => 1],
            ['user_id' => $sitiId,  'name' => 'Bubur Ayam Buk Narti',     'description' => 'Bubur ayam dengan kuah kental, cakwe, dan kacang. Cocok untuk sarapan.',          'address' => 'Jl. Banjarsari No. 3, Tembalang',           'latitude' => -7.0510, 'longitude' => 110.4355, 'avg_rating' => 4.2, 'is_verified' => 1],
            ['user_id' => $adminId, 'name' => 'Depot Bu Rukmini',         'description' => 'Menu masakan Jawa rumahan: pecel, rawon, dan lodeh. Rasanya seperti masakan ibu.', 'address' => 'Jl. Tirto Agung No. 15, Semarang',          'latitude' => -7.0534, 'longitude' => 110.4391, 'avg_rating' => 4.6, 'is_verified' => 1],
            ['user_id' => $budiId,  'name' => 'Minuman Segar Bu Endah',   'description' => 'Es campur, es buah, dan jus segar. Harga mulai Rp 3.000.',                        'address' => 'Jl. Prof Sudarto No. 8, Tembalang',         'latitude' => -7.0487, 'longitude' => 110.4375, 'avg_rating' => 4.0, 'is_verified' => 1],
            ['user_id' => $sitiId,  'name' => 'Pecel Pincuk Mbak Yuni',   'description' => 'Pecel sayuran dengan sambal kacang khas Jawa. Disajikan dengan pincuk daun pisang.', 'address' => 'Jl. Ngesrep Barat V, Semarang',          'latitude' => -7.0542, 'longitude' => 110.4360, 'avg_rating' => 4.4, 'is_verified' => 1],
            ['user_id' => $adminId, 'name' => 'Martabak Mas Doni',        'description' => 'Martabak manis dan telur dengan berbagai topping keju, coklat, dan kacang.',      'address' => 'Jl. Setiabudi No. 45, Semarang',            'latitude' => -7.0467, 'longitude' => 110.4340, 'avg_rating' => 4.5, 'is_verified' => 1],
            ['user_id' => $budiId,  'name' => 'Warung Seafood Pak Haji',  'description' => 'Seafood segar: cumi, udang, ikan bakar. Harga terjangkau dan porsi besar.',       'address' => 'Jl. Fatmawati No. 22, Semarang',            'latitude' => -7.0476, 'longitude' => 110.4328, 'avg_rating' => 4.3, 'is_verified' => 1],
            ['user_id' => $sitiId,  'name' => 'Kopi Kenangan Kampus',     'description' => 'Kopi susu, americano, dan berbagai minuman kekinian. Ada WiFi gratis.',           'address' => 'Jl. Tembalang Selatan No. 5, Semarang',     'latitude' => -7.0515, 'longitude' => 110.4395, 'avg_rating' => 4.2, 'is_verified' => 1],
            ['user_id' => $adminId, 'name' => 'Ayam Geprek Sambal Bawang', 'description' => 'Ayam geprek dengan sambal bawang super pedas. Tersedia level kepedasan 1-10.',   'address' => 'Jl. Gondang Raya No. 12, Semarang',         'latitude' => -7.0530, 'longitude' => 110.4412, 'avg_rating' => 4.4, 'is_verified' => 0],
            ['user_id' => $budiId,  'name' => 'Warung Pecel Lele Bu Tin', 'description' => 'Pecel lele dan ayam goreng dengan sambal terasi. Murah, enak, dan mengenyangkan.', 'address' => 'Jl. Mulawarman No. 8, Tembalang',          'latitude' => -7.0503, 'longitude' => 110.4370, 'avg_rating' => 4.1, 'is_verified' => 1],
            ['user_id' => $sitiId,  'name' => 'Kwetiau Goreng Ahong',     'description' => 'Kwetiau goreng dan siram dengan topping udang dan cumi. Khas masakan Tionghoa.',  'address' => 'Jl. Gang Baru No. 5, Semarang',             'latitude' => -6.9861, 'longitude' => 110.4136, 'avg_rating' => 4.5, 'is_verified' => 1],
            ['user_id' => $adminId, 'name' => 'Dimsum Murah Meriah',      'description' => 'Dimsum kukus dan goreng aneka rasa. Harga mulai Rp 5.000 per pcs.',              'address' => 'Jl. Pandanaran No. 30, Semarang',           'latitude' => -6.9919, 'longitude' => 110.4166, 'avg_rating' => 4.3, 'is_verified' => 1],
            ['user_id' => $budiId,  'name' => 'Tahu Gimbal Pak Kumis',    'description' => 'Tahu gimbal khas Semarang dengan saus kacang. Kuliner legendaris wajib coba.',    'address' => 'Jl. Imam Bonjol No. 40, Semarang',          'latitude' => -6.9941, 'longitude' => 110.4211, 'avg_rating' => 4.8, 'is_verified' => 1],
            ['user_id' => $sitiId,  'name' => 'Rujak Cingur Bu Dar',      'description' => 'Rujak cingur dengan bumbu petis yang kuat. Segar dan nikmat untuk makan siang.',  'address' => 'Jl. MT Haryono No. 17, Semarang',           'latitude' => -6.9877, 'longitude' => 110.4243, 'avg_rating' => 4.2, 'is_verified' => 1],
        ];

        foreach ($places as &$p) {
            $p['created_at'] = date('Y-m-d H:i:s', strtotime('-' . rand(1, 90) . ' days'));
            $p['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('places')->insertBatch($places);
        $firstPlaceId = $this->db->insertID() - count($places) + 1;

        // ── Place Categories ──────────────────────────────────────
        $catMap = [];
        $cats = $this->db->table('categories')->get()->getResultArray();
        foreach ($cats as $c) $catMap[$c['slug']] = $c['id'];

        $placeCategories = [
            [$firstPlaceId + 0,  'nasi-lauk'],
            [$firstPlaceId + 1,  'bakso-mie'],
            [$firstPlaceId + 2,  'minuman'],
            [$firstPlaceId + 3,  'bakso-mie'],
            [$firstPlaceId + 4,  'gorengan'],
            [$firstPlaceId + 5,  'makanan-berat'],
            [$firstPlaceId + 6,  'makanan-berat'],
            [$firstPlaceId + 7,  'makanan-berat'],
            [$firstPlaceId + 8,  'nasi-lauk'],
            [$firstPlaceId + 9,  'makanan-berat'],
            [$firstPlaceId + 10, 'makanan-berat'],
            [$firstPlaceId + 11, 'minuman'],
            [$firstPlaceId + 12, 'makanan-berat'],
            [$firstPlaceId + 13, 'jajanan'],
            [$firstPlaceId + 14, 'seafood'],
            [$firstPlaceId + 15, 'minuman'],
            [$firstPlaceId + 16, 'makanan-berat'],
            [$firstPlaceId + 17, 'makanan-berat'],
            [$firstPlaceId + 18, 'bakso-mie'],
            [$firstPlaceId + 19, 'jajanan'],
            [$firstPlaceId + 20, 'jajanan'],
            [$firstPlaceId + 21, 'makanan-berat'],
        ];

        $pcRows = [];
        foreach ($placeCategories as [$pid, $slug]) {
            if (isset($catMap[$slug])) {
                $pcRows[] = ['place_id' => $pid, 'category_id' => $catMap[$slug]];
            }
        }
        if (!empty($pcRows)) $this->db->table('place_categories')->insertBatch($pcRows);

        // ── Place Tags ────────────────────────────────────────────
        $tagMap = [];
        $tagRows = $this->db->table('tags')->get()->getResultArray();
        foreach ($tagRows as $t) $tagMap[$t['slug']] = $t['id'];

        $placeTags = [
            [$firstPlaceId + 0,  ['murah', 'halal', 'buka-pagi', 'dekat-kampus']],
            [$firstPlaceId + 1,  ['enak', 'halal', 'favorit-mhs']],
            [$firstPlaceId + 2,  ['murah', 'dekat-kampus', 'favorit-mhs']],
            [$firstPlaceId + 3,  ['enak', 'porsi-besar', 'halal']],
            [$firstPlaceId + 4,  ['murah', 'dekat-kampus']],
            [$firstPlaceId + 5,  ['murah', 'halal', 'dekat-kampus']],
            [$firstPlaceId + 6,  ['murah', 'buka-pagi', 'halal']],
            [$firstPlaceId + 7,  ['murah', 'buka-malam', 'favorit-mhs']],
            [$firstPlaceId + 8,  ['pedas', 'enak', 'buka-malam']],
            [$firstPlaceId + 9,  ['murah', 'buka-pagi', 'halal']],
            [$firstPlaceId + 10, ['enak', 'halal', 'parkir-luas']],
            [$firstPlaceId + 11, ['murah', 'dekat-kampus']],
            [$firstPlaceId + 12, ['murah', 'halal', 'enak']],
            [$firstPlaceId + 13, ['enak', 'buka-malam']],
            [$firstPlaceId + 14, ['porsi-besar', 'parkir-luas', 'halal']],
            [$firstPlaceId + 15, ['wifi', 'ac', 'favorit-mhs']],
            [$firstPlaceId + 16, ['pedas', 'murah', 'favorit-mhs']],
            [$firstPlaceId + 17, ['murah', 'halal', 'enak']],
            [$firstPlaceId + 18, ['enak', 'halal']],
            [$firstPlaceId + 19, ['murah', 'enak']],
            [$firstPlaceId + 20, ['enak', 'favorit-mhs', 'halal']],
            [$firstPlaceId + 21, ['enak', 'murah']],
        ];

        $ptRows = [];
        foreach ($placeTags as [$pid, $slugs]) {
            foreach ($slugs as $slug) {
                if (isset($tagMap[$slug])) {
                    $ptRows[] = ['place_id' => $pid, 'tag_id' => $tagMap[$slug]];
                }
            }
        }
        if (!empty($ptRows)) $this->db->table('place_tags')->insertBatch($ptRows);

        // ── Reviews ───────────────────────────────────────────────
        $reviewData = [
            ['user_id' => $budiId, 'place_id' => $firstPlaceId + 0,  'rating' => 5, 'comment' => 'Enak banget dan murah! Nasi uduknya gurih, lauk pauknya lengkap.'],
            ['user_id' => $sitiId, 'place_id' => $firstPlaceId + 0,  'rating' => 4, 'comment' => 'Tempatnya sederhana tapi bersih. Harga mahasiswa banget!'],
            ['user_id' => $sitiId, 'place_id' => $firstPlaceId + 1,  'rating' => 4, 'comment' => 'Mie ayamnya enak, kuahnya gurih. Porsinya cukup besar.'],
            ['user_id' => $budiId, 'place_id' => $firstPlaceId + 3,  'rating' => 5, 'comment' => 'Bakso paling enak di sekitar kampus! Kuahnya mantap.'],
            ['user_id' => $sitiId, 'place_id' => $firstPlaceId + 5,  'rating' => 4, 'comment' => 'Lumayan untuk makan siang di kampus. Harganya terjangkau.'],
            ['user_id' => $budiId, 'place_id' => $firstPlaceId + 10, 'rating' => 5, 'comment' => 'Masakan rumahan yang enak banget. Seperti masakan ibu!'],
            ['user_id' => $sitiId, 'place_id' => $firstPlaceId + 15, 'rating' => 4, 'comment' => 'Kopinya enak, ada WiFi gratis. Nyaman buat ngerjain tugas.'],
            ['user_id' => $budiId, 'place_id' => $firstPlaceId + 20, 'rating' => 5, 'comment' => 'Tahu gimbal legendaris! Wajib dicoba kalau ke Semarang.'],
        ];

        foreach ($reviewData as &$rv) {
            $rv['created_at'] = date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'));
            $rv['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('reviews')->insertBatch($reviewData);

        echo "Seeder selesai: 22 tempat, 8 kategori, 12 tag, 3 user, 8 review\n";
    }
}
