<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- TAMBAHKAN BARIS INI

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Kode pengecekan Anda
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
        }

        return redirect('/login');
    }
}