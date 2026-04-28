<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePlacesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'comment'  => 'FK ke users.id — siapa yang menambahkan tempat ini',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 300,
            ],
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,8',
                'null'       => true,
                'comment'    => 'Diisi dari Nominatim API',
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
                'comment'    => 'Diisi dari Nominatim API',
            ],
            'osm_place_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'ID dari OpenStreetMap untuk referensi',
            ],
            'thumbnail' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'avg_rating' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,1',
                'default'    => 0,
                'comment'    => 'Otomatis dihitung ulang setiap ada review baru',
            ],
            'is_verified' => [
                'type'    => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '0 = belum diverifikasi, 1 = sudah diverifikasi admin',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Soft delete — data tidak benar-benar dihapus',
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        // Foreign key ke tabel users
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Index untuk mempercepat query filter dan sort
        $this->forge->addKey('avg_rating');
        $this->forge->addKey('is_verified');
        $this->forge->addKey('deleted_at');

        $this->forge->createTable('places');
    }

    public function down()
    {
        $this->forge->dropTable('places');
    }
}
