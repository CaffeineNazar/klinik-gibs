<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login DAN apakah role-nya sesuai dengan yang diminta
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Jika tidak sesuai, tampilkan error 403 (Forbidden)
            abort(403, 'AKSES DITOLAK: Halaman ini khusus untuk petugas Klinik.');
        }

        return $next($request);
    }
}