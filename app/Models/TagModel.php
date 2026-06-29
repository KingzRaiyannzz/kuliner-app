<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table         = 'tags';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['name', 'slug'];

    protected $validationRules = [
        'name' => 'required|is_unique[tags.name,id,{id}]',
        'slug' => 'required|is_unique[tags.slug,id,{id}]',
    ];

    public function getWithCount(): array
    {
        return $this->db->table('tags t')
            ->select('t.*, COUNT(pt.place_id) AS place_count')
            ->join('place_tags pt', 'pt.tag_id = t.id', 'left')
            ->groupBy('t.id')
            ->orderBy('place_count', 'DESC')
            ->get()->getResultArray();
    }

    public function findOrCreate(string $name): int
    {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($name)));
        $tag  = $this->where('slug', $slug)->first();

        if ($tag) return $tag['id'];

        $this->insert(['name' => ucfirst(trim($name)), 'slug' => $slug]);
        return $this->insertID();
    }
}
