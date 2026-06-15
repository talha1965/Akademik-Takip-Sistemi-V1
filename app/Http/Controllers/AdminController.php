<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_courses'  => Course::count(),
            'total_users'    => User::count(),
        ];

        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin-panel', compact('stats', 'users'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'new_role' => 'required|in:student,teacher,admin',
        ]);

        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendi rolünüzü değiştiremezsiniz.');
        }

        $user->update(['role' => $request->new_role]);

        return back()->with('success', "{$user->name} kullanıcısının rolü güncellendi.");
    }
}