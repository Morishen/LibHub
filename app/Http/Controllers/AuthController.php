<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Loan; // Wajib di-import
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman edit profil beserta statistik dan riwayat pinjaman.
     * Ini memperbaiki error 'Undefined variable $activeLoansCount' dan '$loans'.
     */
    public function editProfile() {
        $user = Auth::user();

        // 1. Ambil Riwayat Peminjaman untuk tabel di bawah profil
        // Ini memperbaiki error pada image_8c9c3f.png baris 68
        $loans = Loan::with('book')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5) // Ambil 5 riwayat terakhir saja untuk pratinjau
            ->get();

        // 2. Mengambil statistik pinjaman untuk widget sidebar
        $activeLoansCount = Loan::where('user_id', $user->id)
            ->whereNull('returned_at')
            ->count();
            
        $completedLoansCount = Loan::where('user_id', $user->id)
            ->whereNotNull('returned_at')
            ->count();
            
        $overdueLoansCount = Loan::where('user_id', $user->id)
            ->whereNull('returned_at')
            ->whereDate('due_date', '<', now())
            ->count();

        // Pastikan semua variabel dikirim ke view menggunakan compact()
        return view('dashboard.member.edit', compact(
            'user', 
            'loans',
            'activeLoansCount', 
            'completedLoansCount', 
            'overdueLoansCount'
        ));
    }

    /**
     * Update data profil di database.
     */
    public function updateProfile(Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:15'], 
            'address' => ['nullable', 'string'], 
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // --- FUNGSI AUTH STANDAR ---

    public function showLogin() {
        return view('auth.login'); 
    }

    public function showRegister() {
        return view('auth.register'); 
    }

    public function showForgotPassword() {
        return view('auth.forgot-password'); 
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'], 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 0, 
        ]);

        Auth::login($user);
        return redirect('/dashboard');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function handleForgotPassword(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);
        return back()->with('status', 'Instruksi reset telah dikirim ke email Anda.');
    }
}