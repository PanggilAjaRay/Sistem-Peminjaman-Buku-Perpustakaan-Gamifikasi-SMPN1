<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Book;
use App\Models\Member;
use App\Models\Mission;
use App\Models\Point;
use App\Models\Fine;
use Carbon\Carbon;

class CirculationForm extends Component
{
    public $member_id, $book_id, $borrowed_at, $due_date;
    public $transaction_id, $return_condition = 'normal';
    public $activeTab = 'borrow'; // borrow, pending, return, history
    public $search = '';
    public $rejectionReason = '';
    public $showRejectionModal = false;
    public $selectedTransactionId = null;

    protected $rules = [
        'member_id' => 'required|exists:members,id',
        'book_id' => 'required|exists:books,id',
        'borrowed_at' => 'required|date',
        'due_date' => 'required|date|after:borrowed_at',
    ];

    public function mount()
    {
        $this->borrowed_at = now()->format('Y-m-d');
        $this->due_date = now()->addDays(7)->format('Y-m-d');
    }

    public function render()
    {
        $members = Member::all();
        $books = Book::where('stock', '>', 0)->get();
        
        // Pending transactions waiting for approval
        $pendingTransactions = Transaction::with(['member', 'book'])
            ->where('status', 'pending')
            ->when($this->search, function($query) {
                $query->whereHas('member', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();
        
        // Active transactions (approved and currently borrowed)
        $activeTransactions = Transaction::with(['member', 'book'])
            ->where('status', 'approved')
            ->when($this->search, function($query) {
                $query->whereHas('member', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();
        
        // History transactions (returned or rejected)
        $historyTransactions = Transaction::with(['member', 'book'])
            ->whereIn('status', ['returned', 'rejected'])
            ->when($this->search, function($query) {
                $query->whereHas('member', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();

        return view('livewire.circulation-form', [
            'members' => $members,
            'books' => $books,
            'pendingTransactions' => $pendingTransactions,
            'activeTransactions' => $activeTransactions,
            'historyTransactions' => $historyTransactions,
        ]);
    }

    public function borrow()
    {
        $this->validate();

        $book = Book::find($this->book_id);
        
        if ($book->stock <= 0) {
            session()->flash('error', 'Stok buku tidak tersedia.');
            return;
        }

        // Admin creates borrowing directly as approved
        $transaction = Transaction::create([
            'member_id' => $this->member_id,
            'book_id' => $this->book_id,
            'borrowed_at' => $this->borrowed_at,
            'due_date' => $this->due_date,
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Decrease book stock
        $book->decrement('stock');

        // Check missions and award points
        $this->checkMissions($this->member_id, $this->borrowed_at);

        session()->flash('message', 'Peminjaman berhasil dicatat.');
        $this->resetInputFields();
    }

    public function approveRequest($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        
        // Check if book still has stock
        $book = Book::find($transaction->book_id);
        if ($book->stock <= 0) {
            session()->flash('error', 'Stok buku tidak tersedia untuk disetujui.');
            return;
        }

        // Update transaction status to approved
        $transaction->status = 'approved';
        $transaction->approved_at = now();
        $transaction->approved_by = auth()->id();
        $transaction->save();

        // Decrease book stock
        $book->decrement('stock');

        // Check missions and award points
        $this->checkMissions($transaction->member_id, $transaction->borrowed_at);

        session()->flash('message', 'Peminjaman berhasil disetujui.');
    }

    public function openRejectionModal($transactionId)
    {
        $this->selectedTransactionId = $transactionId;
        $this->showRejectionModal = true;
        $this->rejectionReason = '';
    }

    public function closeRejectionModal()
    {
        $this->showRejectionModal = false;
        $this->selectedTransactionId = null;
        $this->rejectionReason = '';
    }

    public function rejectRequest()
    {
        if (empty($this->rejectionReason)) {
            session()->flash('error', 'Alasan penolakan harus diisi.');
            return;
        }

        $transaction = Transaction::findOrFail($this->selectedTransactionId);
        
        // Update transaction status to rejected
        $transaction->status = 'rejected';
        $transaction->approved_at = now();
        $transaction->approved_by = auth()->id();
        $transaction->rejection_reason = $this->rejectionReason;
        $transaction->save();

        session()->flash('message', 'Peminjaman berhasil ditolak.');
        $this->closeRejectionModal();
    }


    private function checkMissions($memberId, $borrowedAt)
    {
        $member = Member::find($memberId);
        $dayOfWeek = Carbon::parse($borrowedAt)->format('l'); // Monday, Tuesday, etc.
        
        // Get count of books borrowed today
        $borrowCountToday = Transaction::where('member_id', $memberId)
            ->whereDate('borrowed_at', $borrowedAt)
            ->count();

        // Check active missions
        $missions = Mission::where('is_active', true)->get();

        foreach ($missions as $mission) {
            $conditionMet = false;

            if ($mission->condition_type === 'borrow_day' && $mission->condition_value === $dayOfWeek) {
                $conditionMet = true;
            } elseif ($mission->condition_type === 'borrow_count' && $borrowCountToday >= (int)$mission->condition_value) {
                $conditionMet = true;
            }

            if ($conditionMet) {
                // Award points
                Point::create([
                    'member_id' => $memberId,
                    'amount' => $mission->reward_points,
                    'reason' => 'Menyelesaikan misi: ' . $mission->title,
                ]);
            }
        }
    }

    public function returnBook($transactionId, $condition = 'normal')
    {
        $transaction = Transaction::findOrFail($transactionId);
        $transaction->returned_at = now();
        $transaction->status = 'returned';
        $transaction->save();

        // Increase book stock
        $book = Book::find($transaction->book_id);
        $book->increment('stock');

        // Calculate fine if late or damaged
        if ($condition === 'late') {
            $daysLate = Carbon::parse($transaction->due_date)->diffInDays(now(), false);
            if ($daysLate > 0) {
                $fineAmount = $daysLate * 1000; // Rp 1,000 per day
                Fine::create([
                    'transaction_id' => $transaction->id,
                    'amount' => $fineAmount,
                    'status' => 'unpaid',
                ]);
            }
        } elseif ($condition === 'damaged') {
            Fine::create([
                'transaction_id' => $transaction->id,
                'amount' => 10000, // Rp 10,000 for damaged book
                'status' => 'unpaid',
            ]);
        }

        session()->flash('message', 'Pengembalian berhasil dicatat.');
    }

    private function resetInputFields()
    {
        $this->member_id = '';
        $this->book_id = '';
        $this->borrowed_at = now()->format('Y-m-d');
        $this->due_date = now()->addDays(7)->format('Y-m-d');
    }
}
