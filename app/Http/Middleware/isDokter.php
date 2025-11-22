<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isDokter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) { // Perbaikan: Gunakan !Auth::check()
        return redirect()->route('login');
    }

    $userRole = session('user_role');

    // Cek jika role user adalah '2' (Dokter)
    if ($userRole == '2') {
        return $next($request);
    } else {
        return back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
    }
}
