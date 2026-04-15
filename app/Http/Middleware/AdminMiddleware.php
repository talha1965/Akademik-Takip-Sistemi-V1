<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Giren kişi giriş yapmış mı VE rolü "admin" mi diye kontrol et
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request); // İzin ver, geçsin
        }

        // Değilse, acımadan 403 (Yasak) hatası ver
        abort(403, 'Bu alana sadece Sistem Yöneticileri (Admin) girebilir!');
    }
}