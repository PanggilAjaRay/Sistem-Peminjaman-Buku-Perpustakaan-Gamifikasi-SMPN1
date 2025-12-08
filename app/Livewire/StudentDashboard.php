<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\Category;
use App\Models\Mission;
use App\Models\Transaction;
use App\Models\Member;
use App\Models\Point;
use Carbon\Carbon;

class StudentDashboard extends Component
{
    public $search = '';
    public $categoryFilter = '';

    public function render()
    {
        $member = auth()->user()->member;
        
        // Build books query with category filter
        $booksQuery = Book::with('category')
            ->where(function($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('author', 'like', '%' . $this->search . '%');
            });
        
        // Apply category filter if selected
        if ($this->categoryFilter) {
            $booksQuery->where('category_id', $this->categoryFilter);
        }
        
        $books = $booksQuery->paginate(12);
        
        // Get all categories for filter dropdown
        $categories = Category::orderBy('name', 'asc')->get();

        // Get all available missions (only active ones)
        $missions = Mission::where('is_active', true)->orderBy('created_at', 'desc')->get();
        
        // Get active loans (approved)
        $activeLoans = $member ? Transaction::where('member_id', $member->id)
            ->where('status', 'approved')
            ->with('book')
            ->get() : collect();

        // Get pending loans
        $pendingLoans = $member ? Transaction::where('member_id', $member->id)
            ->where('status', 'pending')
            ->with('book')
            ->get() : collect();

        // Get overdue loans (loans where due_date <= today)
        $overdueLoans = $member ? Transaction::where('member_id', $member->id)
            ->where('status', 'approved')
            ->whereDate('due_date', '<=', Carbon::now()->toDateString())
            ->with('book')
            ->get() : collect();

        // Get total points for the student
        $totalPoints = $member ? Point::where('member_id', $member->id)->sum('amount') : 0;
        
        // Count active missions
        $missionsCount = $missions->count();

        return view('livewire.student-dashboard', compact('books', 'categories', 'missions', 'activeLoans', 'pendingLoans', 'overdueLoans', 'totalPoints', 'missionsCount'));
    }

    public function borrowBook($bookId)
    {
        $member = auth()->user()->member;
        
        if (!$member) {
            session()->flash('error', 'Member tidak ditemukan.');
            return;
        }

        $book = Book::find($bookId);
        
        if (!$book || $book->stock <= 0) {
            session()->flash('error', 'Stok buku tidak tersedia.');
            return;
        }

        // Check if student already has pending or active borrowing for this book
        $existingTransaction = Transaction::where('member_id', $member->id)
            ->where('book_id', $bookId)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingTransaction) {
            session()->flash('error', 'Anda sudah memiliki peminjaman aktif atau menunggu approval untuk buku ini.');
            return;
        }

        // Create transaction with pending status
        $borrowedAt = now();
        $dueDate = now()->addDays(7);

        Transaction::create([
            'member_id' => $member->id,
            'book_id' => $bookId,
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'status' => 'pending',
        ]);

        // NOTE: Stock is NOT decremented here. It will be decremented when admin approves.
        // NOTE: Missions are NOT checked here. They will be checked when admin approves.

        session()->flash('message', 'Permintaan peminjaman berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
