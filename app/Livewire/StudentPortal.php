<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Member;
use App\Models\Book;
use App\Models\Transaction;
use App\Models\Mission;
use App\Models\Point;
use Illuminate\Support\Facades\DB;

class StudentPortal extends Component
{
    public $selectedMemberId;
    public $activeTab = 'profile'; // profile, catalog, history, missions, leaderboard

    public function mount()
    {
        // For demo purposes, select first member if exists
        $this->selectedMemberId = Member::first()?->id;
    }

    public function render()
    {
        $member = null;
        $totalPoints = 0;
        $pendingBorrowings = collect();
        $activeBorrowings = collect();
        $borrowingHistory = collect();
        $activeMissions = collect();
        $leaderboard = collect();
        $books = collect();

        if ($this->selectedMemberId) {
            $member = Member::find($this->selectedMemberId);
            
            // Calculate total points
            $totalPoints = Point::where('member_id', $this->selectedMemberId)->sum('amount');
            
            // Get pending borrowings (waiting for approval)
            $pendingBorrowings = Transaction::with('book')
                ->where('member_id', $this->selectedMemberId)
                ->where('status', 'pending')
                ->latest()
                ->get();
            
            // Get active borrowings (approved and currently borrowed)
            $activeBorrowings = Transaction::with('book')
                ->where('member_id', $this->selectedMemberId)
                ->where('status', 'approved')
                ->latest()
                ->get();
            
            // Get borrowing history (returned or rejected)
            $borrowingHistory = Transaction::with('book')
                ->where('member_id', $this->selectedMemberId)
                ->whereIn('status', ['returned', 'rejected'])
                ->latest()
                ->take(20)
                ->get();
            
            // Get active missions
            $activeMissions = Mission::where('is_active', true)->get();
            
            // Get leaderboard
            $leaderboard = DB::table('points')
                ->select('member_id', DB::raw('SUM(amount) as total_points'))
                ->groupBy('member_id')
                ->orderByDesc('total_points')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    $member = Member::find($item->member_id);
                    return [
                        'member' => $member,
                        'total_points' => $item->total_points
                    ];
                });
            
            // Get all books for catalog
            $books = Book::where('stock', '>', 0)->get();
        }

        $members = Member::all(); // For member selector

        return view('livewire.student-portal', [
            'member' => $member,
            'totalPoints' => $totalPoints,
            'pendingBorrowings' => $pendingBorrowings,
            'activeBorrowings' => $activeBorrowings,
            'borrowingHistory' => $borrowingHistory,
            'activeMissions' => $activeMissions,
            'leaderboard' => $leaderboard,
            'books' => $books,
            'members' => $members,
        ]);
    }

    public function borrowBook($bookId)
    {
        if (!$this->selectedMemberId) {
            session()->flash('error', 'Silakan pilih siswa terlebih dahulu.');
            return;
        }

        $book = Book::find($bookId);
        
        if (!$book || $book->stock <= 0) {
            session()->flash('error', 'Buku tidak tersedia untuk dipinjam.');
            return;
        }

        // Check if student already has pending or active borrowing for this book
        $existingTransaction = Transaction::where('member_id', $this->selectedMemberId)
            ->where('book_id', $bookId)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingTransaction) {
            session()->flash('error', 'Anda sudah memiliki peminjaman aktif atau menunggu approval untuk buku ini.');
            return;
        }

        // Create pending transaction (requires admin approval)
        Transaction::create([
            'member_id' => $this->selectedMemberId,
            'book_id' => $bookId,
            'borrowed_at' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'pending',
        ]);

        session()->flash('message', 'Permintaan peminjaman berhasil dikirim. Menunggu persetujuan admin.');
        
        // Switch to history tab to see the pending request
        $this->activeTab = 'history';
    }
}
