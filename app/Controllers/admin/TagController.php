<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TagModel;

class TagController extends BaseController
{
    public function index()
    {
        $tagModel = new TagModel();

        return view('admin/tags/index', [
            'tags' => $tagModel->getWithCount()
        ]);
    }

    public function store()
    {
        $name = trim($this->request->getPost('name'));

        $tagModel = new TagModel();

        $tagModel->save([
            'name' => $name,
            'slug' => strtolower(
                preg_replace('/[^a-zA-Z0-9]+/', '-', $name)
            )
        ]);

        return redirect()->to('/admin/tags')
            ->with('success', 'Tag berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $tagModel = new TagModel();

        $tagModel->delete($id);

        return redirect()->to('/admin/tags')
            ->with('success', 'Tag berhasil dihapus');
    }
}
