<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * DASHBOARD MEMBER
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $loans = Loan::with(['book'])
            ->where('user_id', $user->id)
            ->latest('borrow_date')
            ->paginate(5);
        
        $stats = $this->getStats($user->id); 

        return view('dashboard.member.index', array_merge([
            'loans' => $loans,
        ], $stats));
    }

    /**
     * DASHBOARD ADMIN
     */
    public function adminDashboard()
    {
        $loans = Loan::with(['book', 'user'])
            ->latest('borrow_date')
            ->paginate(5); 
        
        $stats = $this->getAdminStats(); 

        return view('admin.dashboard', array_merge([
            'loans' => $loans,
        ], $stats));
    }

    /**
     * INDEX MEMBER (Riwayat Peminjaman Saya)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Loan::with(['book'])->where('user_id', $user->id)->latest('borrow_date');

        $this->applyFilter($query, $request);

        $loans = $query->paginate(15);
        $stats = $this->getStats($user->id);

        return view('loans.index', array_merge([
            'loans' => $loans,
        ], $stats));
    }

    /**
     * INDEX ADMIN (Kelola Semua Peminjaman)
     */
    public function adminIndex(Request $request)
    {
        $query = Loan::with(['book', 'user'])->latest('borrow_date');

        // Tambahkan fitur Search untuk Admin
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                })->orWhereHas('book', function($b) use ($search) {
                    $b->where('title', 'like', "%$search%");
                });
            });
        }

        $this->applyFilter($query, $request);

        $loans = $query->paginate(15);
        $stats = $this->getAdminStats();

        return view('admin.loans.index', array_merge([
            'loans' => $loans,
        ], $stats));
    }

    /**
     * Method Helper: Filter Status
     */
    private function applyFilter($query, $request)
    {
        if ($request->status === 'active') {
            $query->whereNull('returned_at')->whereDate('due_date', '>=', now());
        } elseif ($request->status === 'overdue') {
            $query->whereNull('returned_at')->whereDate('due_date', '<', now());
        } elseif ($request->status === 'returned') {
            $query->whereNotNull('returned_at');
        }
    }

    /**
     * Method Helper: Statistik
     */
    private function getStats($userId)
    {
        return [
            'activeLoansCount' => Loan::where('user_id', $userId)
                ->whereNull('returned_at')->whereDate('due_date', '>=', now())->count(),
            'completedLoansCount' => Loan::where('user_id', $userId)
                ->whereNotNull('returned_at')->count(),
            'overdueLoansCount' => Loan::where('user_id', $userId)
                ->whereNull('returned_at')->whereDate('due_date', '<', now())->count(),
        ];
    }

    private function getAdminStats()
    {
        return [
            'activeLoansCount' => Loan::whereNull('returned_at')->whereDate('due_date', '>=', now())->count(),
            'completedLoansCount' => Loan::whereNotNull('returned_at')->count(),
            'overdueLoansCount' => Loan::whereNull('returned_at')->whereDate('due_date', '<', now())->count(),
            'totalBooksCount' => Book::count(),
            'totalUsersCount' => User::where('is_admin', 0)->count(),
        ];
    }

    /**
     * PROSES PINJAM (STORE)
     */
    public function store(Request $request)
    {
        $request->validate(['book_id' => 'required|exists:books,id']);
        $book = Book::findOrFail($request->book_id);

        if ($book->available_copies <= 0) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        Loan::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
        ]);

        $book->decrement('available_copies');

        return redirect()->route('dashboard')->with('success', 'Buku berhasil dipinjam!');
    }

    /**
     * PROSES KEMBALI (Hanya Admin)
     */
    public function returnBook(Loan $loan)
    {
        if ($loan->returned_at) {
            return back()->with('info', 'Buku ini sudah dikembalikan.');
        }

        $loan->update([
            'returned_at' => now(),
        ]);

        // Kembalikan stok buku
        $loan->book->increment('available_copies');

        return redirect()->back()->with('success', 'Buku telah berhasil dikembalikan.');
    }

    /**
     * PERPANJANG PINJAMAN (Member)
     */
    public function extend(Loan $loan)
    {
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($loan->returned_at) {
            return back()->with('error', 'Buku yang sudah kembali tidak bisa diperpanjang.');
        }

        // Tambah 7 hari dari due_date lama
        $newDueDate = Carbon::parse($loan->due_date)->addDays(7);

        $loan->update([
            'due_date' => $newDueDate
        ]);

        return redirect()->back()->with('success', 'Masa peminjaman diperpanjang hingga ' . $newDueDate->format('d M Y'));
    }
}