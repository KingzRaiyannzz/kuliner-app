<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KulinerSeeder extends Seeder
{
    public function run()
    {
        // ----------------------------------------
        // Seed: Categories
        // ----------------------------------------
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

        // ----------------------------------------
        // Seed: Tags
        // ----------------------------------------
        $tags = [
            ['name' => 'Murah',       'slug' => 'murah'],
            ['name' => 'Enak',        'slug' => 'enak'],
            ['name' => 'Porsi Besar', 'slug' => 'porsi-besar'],
            ['name' => 'Buka Malam',  'slug' => 'buka-malam'],
            ['name' => 'Halal',       'slug' => 'halal'],
            ['name' => 'Pedas',       'slug' => 'pedas'],
            ['name' => 'Dekat Kampus','slug' => 'dekat-kampus'],
            ['name' => 'WiFi',        'slug' => 'wifi'],
            ['name' => 'Parkir Luas', 'slug' => 'parkir-luas'],
            ['name' => 'Favorit Mhs', 'slug' => 'favorit-mhs'],
        ];

        $this->db->table('tags')->insertBatch($tags);

        // ----------------------------------------
        // Seed: 1 user dummy
        // ----------------------------------------
        $this->db->table('users')->insert([
            'name'          => 'Admin Kuliner',
            'email'         => 'admin@kuliner.test',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        $userId = $this->db->insertID();

        // ----------------------------------------
        // Seed: 3 tempat makan contoh
        // ----------------------------------------
        $places = [
            [
                'user_id'     => $userId,
                'name'        => 'Warung Bu Siti',
                'description' => 'Nasi uduk murah meriah, buka dari subuh.',
                'address'     => 'Jl. Kampus Raya No. 12',
                'latitude'    => -6.3728,
                'longitude'   => 106.8342,
                'avg_rating'  => 4.5,
                'is_verified' => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'     => $userId,
                'name'        => 'Mie Ayam Pak Budi',
                'description' => 'Mie ayam jamur dengan kuah bening yang gurih.',
                'address'     => 'Jl. Melati No. 5, depan gedung D',
                'latitude'    => -6.3745,
                'longitude'   => 106.8360,
                'avg_rating'  => 4.2,
                'is_verified' => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'     => $userId,
                'name'        => 'Es Teh Joss Corner',
                'description' => 'Minuman segar aneka rasa, harga mulai 2ribuan.',
                'address'     => 'Kantin Gedung A Lantai 1',
                'latitude'    => -6.3720,
                'longitude'   => 106.8330,
                'avg_rating'  => 4.0,
                'is_verified' => 0,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('places')->insertBatch($places);
    }
}
