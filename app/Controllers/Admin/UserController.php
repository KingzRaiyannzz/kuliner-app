<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

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
