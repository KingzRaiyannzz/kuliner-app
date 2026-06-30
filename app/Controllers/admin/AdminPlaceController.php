<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PlaceModel;

class AdminPlaceController extends BaseController
{
    protected $placeModel;

    public function __construct()
    {
        $this->placeModel = new PlaceModel();
    }

    public function index()
    {
        $data['places'] = $this->placeModel->findAll();

        return view('admin/places/index', $data);
    }

    public function verify(int $id)
    {
        $place = $this->placeModel->find($id);

        if (!$place) {
            return redirect()
                ->to('/admin/places')
                ->with('error', 'Tempat tidak ditemukan.');
        }

        $this->placeModel->update($id, [
            'is_verified' => 1
        ]);

        return redirect()
            ->to('/admin/places')
            ->with('success', 'Tempat berhasil diverifikasi.');
    }

    public function edit($id)
    {
        $placeModel = new PlaceModel();

        $place = $placeModel->find($id);

        if (!$place) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/places/edit', [
            'title' => 'Edit Tempat',
            'place' => $place,
            'categories' => \Config\Database::connect()->table('categories')->get()->getResultArray(),
            'tags' => \Config\Database::connect()->table('tags')->get()->getResultArray(),
            'selectedCategories' => array_column(
                \Config\Database::connect()->table('place_categories')
                    ->select('category_id')
                    ->where('place_id', $id)
                    ->get()
                    ->getResultArray(),
                'category_id'
            ),
            'selectedTags' => array_column(
                \Config\Database::connect()->table('place_tags')
                    ->select('tag_id')
                    ->where('place_id', $id)
                    ->get()
                    ->getResultArray(),
                'tag_id'
            ),
            'errors' => session()->getFlashdata('errors') ?? [],
            'old' => session()->getFlashdata('_ci_old_input')['post'] ?? [],
        ]);
    }

    public function update($id)
    {
        $placeModel = new PlaceModel();

        $place = $placeModel->find($id);

        if (!$place) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'name'        => 'required|min_length[3]',
            'address'     => 'required',
            'latitude'    => 'required',
            'longitude'   => 'required',
            'description' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $placeModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'address'     => $this->request->getPost('address'),
            'description' => $this->request->getPost('description'),
            'latitude'    => $this->request->getPost('latitude'),
            'longitude'   => $this->request->getPost('longitude'),
        ]);

        $categories = $this->request->getPost('categories') ?? [];
        $placeModel->syncCategories((int) $id, is_array($categories) ? $categories : []);

        $tags = $this->request->getPost('tags') ?? [];
        $placeModel->syncTags((int) $id, is_array($tags) ? $tags : []);

        return redirect()->to('/admin/places')
            ->with('success', 'Data berhasil diupdate');
    }

    public function destroy(int $id)
    {
        $place = $this->placeModel->find($id);

        if (!$place) {
            return redirect()
                ->to('/admin/places')
                ->with('error', 'Tempat tidak ditemukan.');
        }

        $this->placeModel->delete($id);

        return redirect()
            ->to('/admin/places')
            ->with('success', 'Tempat berhasil dihapus.');
    }
}
