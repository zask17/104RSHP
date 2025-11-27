<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

        protected function loggedOut(Request $request)
    {
        return redirect('/')->with('success', 'Logout berhasil!');
    }

    public function login(Request $request)
{
    // 1. Validasi input email dan password
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // 2. Ambil data user beserta relasi roles-nya
    $user = User::with(['roles' => function ($query) {
        $query->where('role_user.status', 1); // Filter status aktif pada tabel pivot
    }])
        ->where('email', $request->input('email'))
        ->first();

    // Cek apakah user ditemukan
    if (!$user) {
        return redirect()->back()
            ->withErrors(['email' => 'Email tidak ditemukan.'])
            ->withInput();
    }

    // Cek password menggunakan Hash::check()
    if (!Hash::check($request->password, $user->password)) {
        return redirect()->back()
            ->withErrors(['password' => 'Password salah.'])
            ->withInput();
    }

    // Ambil role pertama yang aktif
    $activeRole = $user->roles->first();

    // 3. Login user ke session Laravel
    Auth::login($user);

    // 4. Simpan session user kustom
    // Menggunakan data dari relasi 'roles' yang sudah di-load
    $request->session()->put([
        'user_id' => $user->iduser,
        'user_name' => $user->nama,
        'user_email' => $user->email,
        'user_role' => $activeRole->idrole ?? 'user', // ID Role
        'user_role_name' => $activeRole->nama_role ?? 'User', // Nama Role
        'user_status' => $activeRole ? $activeRole->pivot->status : 'inactive'
    ]);

    // 5. Logika redirect berdasarkan Role ID
    $userRole = $activeRole->idrole ?? null;

    switch ($userRole) {
        case '1': // Administrator
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
        case '2': // Dokter
            return redirect()->route('dokter.dashboard')->with('success', 'Login berhasil!');
        case '3': // Perawat
            return redirect()->route('perawat.dashboard')->with('success', 'Login berhasil!');
        case '4': // Resepsionis
            return redirect()->route('resepsionis.dashboard')->with('success', 'Login berhasil!');
        default: // Pemilik (atau role lain yang tidak spesifik)
            return redirect()->route('pemilik.dashboard')->with('success', 'Login berhasil');
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('Success', 'Logout berhasil!');
    }
}