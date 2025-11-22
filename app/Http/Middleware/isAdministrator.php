<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isAdministrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user tidak autensikassi, redirect ke login
        if (!Auth::check()){
            return redirect()->route('login');
        }

        // Ambil role dari session atau relasi user
        $userRole = session('user_role');

        // Kalau role 1, return 403
        if ($userRole === 1) {
            return $next($request);
        } else {
            return back()->with('eror', 'Akses ditolak, Anda tidak punya izin mengakses halaman ini.');
        }
    }
}
