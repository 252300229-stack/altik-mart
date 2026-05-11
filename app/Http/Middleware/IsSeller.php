<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
{
    // Cek apakah sudah login dan apakah role-nya seller atau admin
    if (auth()->check() && (auth()->user()->role == 'seller' || auth()->user()->role == 'admin')) {
        return $next($request);
    }

    // Jika bukan penjual, tendang ke halaman home
    return redirect('/')->with('error', 'Anda tidak memiliki akses penjual.');
}
}
