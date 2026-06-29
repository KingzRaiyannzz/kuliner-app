<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        return view('admin/users/index', [
            'users' => $userModel->findAll()
        ]);
    }
}
