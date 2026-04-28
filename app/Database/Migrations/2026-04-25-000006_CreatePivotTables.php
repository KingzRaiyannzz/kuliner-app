<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePivotTables extends Migration
{
    public function up()
    {
        // ------------------------------------------------
        // Tabel pivot: place_categories (places <-> categories)
        // ------------------------------------------------
        $this->forge->addField([
            'place_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);

        // Composite primary key — satu tempat tidak bisa punya kategori yang sama dua kali
        $this->forge->addPrimaryKey(['place_id', 'category_id']);
        $this->forge->addForeignKey('place_id',    'places',     'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('place_categories');

        // ------------------------------------------------
        // Tabel pivot: place_tags (places <-> tags)
        // ------------------------------------------------
        $this->forge->addField([
            'place_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tag_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);

        $this->forge->addPrimaryKey(['place_id', 'tag_id']);
        $this->forge->addForeignKey('place_id', 'places', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tag_id',   'tags',   'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('place_tags');
    }

    public function down()
    {
        $this->forge->dropTable('place_tags');
        $this->forge->dropTable('place_categories');
    }
}
