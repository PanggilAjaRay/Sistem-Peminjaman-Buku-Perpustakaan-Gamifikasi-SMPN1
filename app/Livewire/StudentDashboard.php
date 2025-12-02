<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\Mission;
use App\Models\Transaction;
use App\Models\Member;
use App\Models\Point;
use Carbon\Carbon;

class StudentDashboard extends Component
{
    public $search = '';

    public function render()
    {
        $member = auth()->user()->member;
        
        $books = Book::where('title', 'like', '%' . $this->search . '%')
            ->orWhere('author', 'like', '%' . $this->search . '%')
            ->paginate(12);

        $missions = $member ? $member->missions()->get() : collect();
        
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

        return view('livewire.student-dashboard', compact('books', 'missions', 'activeLoans', 'pendingLoans'));
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
