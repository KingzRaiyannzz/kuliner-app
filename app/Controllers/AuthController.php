<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ----------------------------------------------------------------
    // GET /register
    // ----------------------------------------------------------------
    public function registerForm()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/places');
        }

        return view('auth/register', [
            'title'  => 'Daftar Akun',
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ]);
    }

    // ----------------------------------------------------------------
    // POST /register
    // ----------------------------------------------------------------
    public function register()
    {
        $rules = [
            'name'             => 'required|min_length[2]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'email'            => ['is_unique' => 'Email ini sudah terdaftar.'],
            'password'         => ['min_length' => 'Password minimal 8 karakter.'],
            'password_confirm' => ['matches'    => 'Konfirmasi password tidak cocok.'],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('old', $this->request->getPost());
            return redirect()->back();
        }

        $this->userModel->insert([
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        $user = $this->userModel->where('email', $this->request->getPost('email'))->first();
        $this->setSession($user);

        return redirect()->to('/places')->with('success', 'Selamat datang, ' . $user['name'] . '! 🎉');
    }

    // ----------------------------------------------------------------
    // GET /login
    // ----------------------------------------------------------------
    public function loginForm()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/places');
        }

        return view('auth/login', [
            'title'  => 'Masuk',
            'errors' => session()->getFlashdata('errors') ?? [],
            'old'    => session()->getFlashdata('old') ?? [],
        ]);
    }

    // ----------------------------------------------------------------
    // POST /login
    // ----------------------------------------------------------------
    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            session()->setFlashdata('old', $this->request->getPost());
            return redirect()->back();
        }

        $email = $this->request->getPost('email');
        $pass  = $this->request->getPost('password');
        $user  = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($pass, $user['password_hash'])) {
            session()->setFlashdata('errors', ['login' => 'Email atau password salah.']);
            session()->setFlashdata('old', ['email' => $email]);
            return redirect()->back();
        }

        $this->setSession($user);

        $redirect = session()->getFlashdata('redirect_url') ?? '/places';
        return redirect()->to($redirect)->with('success', 'Selamat datang kembali, ' . $user['name'] . '!');
    }

    // ----------------------------------------------------------------
    // GET /logout
    // ----------------------------------------------------------------
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Kamu berhasil keluar.');
    }

    // ----------------------------------------------------------------
    // Helper: simpan data user ke session
    // ----------------------------------------------------------------
    private function setSession(array $user): void
    {
        session()->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'user_avatar' => $user['avatar'],
            'isLoggedIn' => true,
        ]);
    }
}
