<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'kontributor', 'pengunjung'],
                'default'    => 'kontributor',
                'after'      => 'email',
                'comment'    => 'admin=kelola semua, kontributor=tambah+review, pengunjung=read only',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'role');
    }
}
