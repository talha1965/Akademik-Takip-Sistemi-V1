<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Giren kişi giriş yapmış mı VE rolü "teacher" mı diye kontrol et
        if (auth()->check() && auth()->user()->role === 'teacher') {
            return $next($request); // İzin ver, geçsin
        }

        // Değilse, onu 403 (Yasak) hatasıyla veya direkt ana sayfaya at
        abort(403, 'Bu alana girmeye yetkiniz yok! Sadece Akademisyenler girebilir.');
    }
}