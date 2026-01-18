<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Eager loading untuk mengambil data buku dan user sekaligus
        $query = Loan::with(['book', 'user'])->latest('borrow_date');

        // PERBAIKAN LOGIKA ROLE: Menggunakan helper isAdmin() dan kolom is_admin
        if (!$user->isAdmin()) { 
            // Jika Member: Hanya lihat data milik sendiri
            $query->where('user_id', $user->id);
            // Sesuaikan path view dengan folder (gunakan titik sebagai pemisah)
            $viewPath = 'dashboard.member.index'; 
        } else {
            // Jika Admin: Lihat semua data peminjaman
            $viewPath = 'admin.loans.index'; 
        }

        // Filter status (tetap dipertahankan)
        if ($request->status === 'active') {
            $query->whereNull('returned_at')->whereDate('due_date', '>=', now());
        } elseif ($request->status === 'overdue') {
            $query->whereNull('returned_at')->whereDate('due_date', '<', now());
        }

        $loans = $query->paginate(15);

        // Statistik untuk Dashboard (disesuaikan dengan ID user yang login)
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

        return view($viewPath, compact(
            'loans', 
            'activeLoansCount', 
            'completedLoansCount', 
            'overdueLoansCount'
        ));
    }

    // Pastikan fungsi store, update, dll juga menggunakan logic is_admin jika diperlukan
}