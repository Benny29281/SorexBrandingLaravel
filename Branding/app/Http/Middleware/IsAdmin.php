<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Cek 1: Apakah user sudah login?
        // Cek 2: Apakah regional user adalah 'Admin'? (Atau sesuaikan dengan logika Anda)
        if (Auth::check() && Auth::user()->regional === 'Admin') {
            return $next($request); // Silakan masuk
        }

        // Jika bukan admin, tendang ke halaman home atau login
        return redirect('/')->with('error', 'Anda tidak memiliki akses Admin!');
    }
}