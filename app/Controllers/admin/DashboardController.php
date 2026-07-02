<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        // Untuk membuktikan berhasil, kita tampilkan teks ini dulu
        return "Selamat! Halaman Dashboard Admin berhasil diakses.";
        
        // Catatan: Nanti kalau file HTML/View dashboard-mu sudah kamu buat, 
        // kamu bisa menghapus tulisan di atas dan menggantinya dengan:
        // return view('admin/dashboard');
    }
}