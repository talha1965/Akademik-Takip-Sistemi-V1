<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

class AdminController extends Controller
{
    // Admin Paneli Ana Sayfası
    public function index()
    {
        // 1. Üst Kısım İçin İstatistikleri Topla
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_courses'  => Course::count(),
            'total_users'    => User::count(),
        ];

        // 2. Alt Tablo İçin Tüm Kullanıcıları Getir (En son kayıt olan en üstte)
        $users = User::orderBy('created_at', 'desc')->get();

        // Verileri görünüme gönder
        return view('admin-panel', compact('stats', 'users'));
    }
}