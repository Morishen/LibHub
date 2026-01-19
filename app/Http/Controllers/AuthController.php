<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Loan; 
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan daftar pengguna khusus untuk Admin.
     */
    public function indexUsers() {
        // Menggunakan properti is_admin secara langsung jika isAdmin() tidak didefinisikan di Model
        if (!Auth::user()->is_admin) {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $users = User::where('is_admin', 0)->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * FITUR BARU: Menghapus Anggota (Hanya Admin).
     */
    public function destroyUser($id) {
        if (!Auth::user()->is_admin) {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $user = User::findOrFail($id);

        // Proteksi: Cek apakah user masih memiliki pinjaman aktif yang belum dikembalikan
        $activeLoans = Loan::where('user_id', $user->id)->whereNull('returned_at')->count();

        if ($activeLoans > 0) {
            return back()->with('error', 'Anggota tidak bisa dihapus karena masih memiliki pinjaman aktif!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Anggota berhasil dihapus dari sistem.');
    }

    /**
     * Menampilkan halaman profil (Hanya Lihat).
     */
    public function showProfile() {
        $user = Auth::user();

        if ($user->is_admin) {
            $activeLoansCount = 0;
            $completedLoansCount = 0;
            $overdueLoansCount = 0;
        } else {
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
        }

        return view('dashboard.member.profile', compact(
            'user', 
            'activeLoansCount', 
            'completedLoansCount', 
            'overdueLoansCount'
        ));
    }

    /**
     * Menampilkan halaman FORM edit profil.
     */
    public function editProfile() {
        $user = Auth::user();

        if ($user->is_admin) {
            $loans = collect();
            $activeLoansCount = 0;
            $completedLoansCount = 0;
            $overdueLoansCount = 0;
        } else {
            $loans = Loan::with('book')
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

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
        }

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
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:15'], 
            'address' => ['nullable', 'string'], 
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    // --- FUNGSI AUTH STANDAR ---

    public function showLogin() {
        return view('auth.login'); 
    }

    public function showRegister() {
        return view('auth.register'); 
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            
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

    public function showForgotPassword() {
        return view('auth.forgot-password'); 
    }

    public function handleForgotPassword(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);
        return back()->with('status', 'Instruksi reset telah dikirim ke email Anda.');
    }
}