<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses autentikasi (Login)
     */
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Ambil data user yang baru saja login
        $user = Auth::user();

        // LOGIKA REDIRECT: Jika Admin atau Seller, arahkan ke Dashboard
        if ($user->role === 'admin' || $user->role === 'seller') {
            return redirect()->route('admin.dashboard')
                             ->with('success', 'Selamat datang di Dashboard, ' . $user->name);
        }

        // Jika Role adalah Customer (Pembeli), arahkan ke Home biasa
        return redirect()->intended('/')->with('success', 'Berhasil login!');
    }

    return back()->withErrors(['email' => 'Email atau password salah.']);
}

    /**
     * Menangani proses keluar (Logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Menampilkan halaman pendaftaran (Register)
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Menangani proses pendaftaran akun baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // UBAH 'pembeli' MENJADI 'customer' agar sinkron dengan pengecekan role lainnya
            'role' => 'customer', 
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat!');
    }

    public function editProfile()
{
    return view('auth.profile', ['user' => Auth::user()]);
}

public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user->name = $request->name;

    if ($request->hasFile('image')) {
        // Hapus foto lama jika ada
        if ($user->profile_photo_path) {
            Storage::delete('public/' . $user->profile_photo_path);
        }
        // Simpan foto baru
        $path = $request->file('image')->store('profile-photos', 'public');
        $user->profile_photo_path = $path;
    }

    $user->save();

    return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
}
}