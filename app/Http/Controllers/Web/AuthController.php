<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Menampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Proses Login dan Pengecekan Role
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Jika Email & Password Benar
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // AMBIL DATA USER YANG SEDANG LOGIN
            $user = Auth::user();

            // LOGIKA PENGARAHAN BERDASARKAN ROLE
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Super Admin!');
            } 
            elseif ($user->role === 'organizer') {
                return redirect()->route('organizer.dashboard')->with('success', 'Selamat datang, Komunitas Penggerak!');
            } 
            else {
                // Jika role-nya 'user' (Relawan biasa) → tampilkan beranda lama
                return redirect()->route('home')->with('success', 'Berhasil masuk! Mari mulai beraksi.');
            }
        }

        // Jika Gagal Login
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 3. Menampilkan Halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // 4. Proses Register Akun Baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Pastikan form ada input password_confirmation
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default pendaftar baru adalah relawan biasa (user)
            'exp_points' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Berhasil mendaftar! Selamat datang di AksiAlam.');
    }

    // 5. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Setalah logout, pastikan dikembalikan ke Landing Page (sekarang Home)
        return redirect()->route('home');
    }
}