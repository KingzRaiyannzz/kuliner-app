<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();

        return view('admin/categories/index', [
            'categories' => $categoryModel->getWithCount()
        ]);
    }

    public function store()
    {
        $name = trim($this->request->getPost('name'));

        $categoryModel = new CategoryModel();

        $categoryModel->save([
            'name' => $name,
            'slug' => CategoryModel::makeSlug($name),
            'icon' => $this->request->getPost('icon')
        ]);

        return redirect()->to('/admin/categories')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $categoryModel = new CategoryModel();

        $categoryModel->delete($id);

        return redirect()->to('/admin/categories')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
