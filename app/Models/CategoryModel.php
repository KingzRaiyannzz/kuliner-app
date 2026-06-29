<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table         = 'categories';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['name', 'slug', 'icon'];

    protected $validationRules = [
        'name' => 'required|is_unique[categories.name,id,{id}]',
        'slug' => 'required|is_unique[categories.slug,id,{id}]',
    ];

    public function getWithCount(): array
    {
        return $this->db->table('categories c')
            ->select('c.*, COUNT(pc.place_id) AS place_count')
            ->join('place_categories pc', 'pc.category_id = c.id', 'left')
            ->groupBy('c.id')
            ->orderBy('place_count', 'DESC')
            ->get()->getResultArray();
    }

    public static function makeSlug(string $name): string
    {
        return strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($name)));
    }
}
