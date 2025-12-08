<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Book;
use App\Models\Member;
use App\Models\Mission;
use App\Models\Point;
use App\Models\Fine;
use App\Models\PenaltySetting;
use Carbon\Carbon;

class CirculationForm extends Component
{
    public $member_id, $book_id, $borrowed_at, $due_date;
    public $transaction_id, $return_condition = 'normal';
    public $activeTab = 'borrow'; // borrow, pending, active, late, history
    public $search = '';
    public $rejectionReason = '';
    public $showRejectionModal = false;
    public $selectedTransactionId = null;
    
    // Search properties
    public $memberSearch = '';
    public $bookSearch = '';
    public $selectedMemberName = '';
    public $selectedBookName = '';
    public $showMemberDropdown = false;
    public $showBookDropdown = false;

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
        // Filter members based on search
        $members = Member::query()
            ->when($this->memberSearch, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->memberSearch . '%')
                      ->orWhere('nis', 'like', '%' . $this->memberSearch . '%');
                });
            })
            ->limit(10)
            ->get();
        
        // Filter books based on search
        $books = Book::where('stock', '>', 0)
            ->when($this->bookSearch, function($query) {
                $query->where('title', 'like', '%' . $this->bookSearch . '%');
            })
            ->limit(10)
            ->get();
        
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
        
        // Active transactions (approved and not yet late)
        $activeTransactions = Transaction::with(['member', 'book'])
            ->where('status', 'approved')
            ->where('due_date', '>=', now())
            ->when($this->search, function($query) {
                $query->whereHas('member', function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();
        
        // Late transactions (approved but past due date)
        $lateTransactions = Transaction::with(['member', 'book'])
            ->where('status', 'approved')
            ->where('due_date', '<', now())
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
            'lateTransactions' => $lateTransactions,
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
        
        // Handle lost books differently - don't change status to returned
        if ($condition === 'lost') {
            $transaction->status = 'lost';
        } else {
            $transaction->status = 'returned';
        }
        
        $transaction->save();

        // Increase book stock only if not lost
        if ($condition !== 'lost') {
            $book = Book::find($transaction->book_id);
            $book->increment('stock');
        }

        // Apply penalties based on condition
        $member = Member::find($transaction->member_id);
        
        if ($condition === 'late' || Carbon::parse($transaction->due_date)->lt(now())) {
            // Calculate days late
            $daysLate = Carbon::parse($transaction->due_date)->diffInDays(now(), false);
            
            if ($daysLate > 0) {
                $penalty = PenaltySetting::calculateLatePenalty($daysLate);
                
                if ($penalty['type'] === 'points' && $penalty['points'] > 0) {
                    // Round values for display
                    $daysLateRounded = round($daysLate);
                    $pointsRounded = round($penalty['points']);
                    
                    // Deduct points (negative point entry)
                    Point::create([
                        'member_id' => $transaction->member_id,
                        'amount' => -$penalty['points'],
                        'reason' => "Denda keterlambatan {$daysLateRounded} hari",
                    ]);
                    
                    // Record fine with point deduction
                    Fine::create([
                        'transaction_id' => $transaction->id,
                        'penalty_type' => 'late_return',
                        'amount' => 0,
                        'points_deducted' => $penalty['points'],
                        'description' => "Keterlambatan {$daysLateRounded} hari - Pengurangan {$pointsRounded} point",
                        'status' => 'paid', // Points already deducted
                    ]);
                } elseif ($penalty['type'] === 'money' && $penalty['amount'] > 0) {
                    // Round values for display
                    $daysLateRounded = round($daysLate);
                    
                    // Create monetary fine
                    Fine::create([
                        'transaction_id' => $transaction->id,
                        'penalty_type' => 'late_return',
                        'amount' => $penalty['amount'],
                        'points_deducted' => 0,
                        'description' => "Keterlambatan {$daysLateRounded} hari - Denda Rp " . number_format($penalty['amount'], 0, ',', '.'),
                        'status' => 'unpaid',
                    ]);
                }
            }
        } 
        
        if ($condition === 'damaged') {
            $penalty = PenaltySetting::calculateDamagedPenalty();
            
            if ($penalty['type'] === 'points' && $penalty['points'] > 0) {
                // Round values for display
                $pointsRounded = round($penalty['points']);
                
                // Deduct points
                Point::create([
                    'member_id' => $transaction->member_id,
                    'amount' => -$penalty['points'],
                    'reason' => "Denda buku rusak",
                ]);
                
                Fine::create([
                    'transaction_id' => $transaction->id,
                    'penalty_type' => 'damaged_book',
                    'amount' => 0,
                    'points_deducted' => $penalty['points'],
                    'description' => "Buku rusak - Pengurangan {$pointsRounded} point",
                    'status' => 'paid',
                ]);
            } elseif ($penalty['type'] === 'money' && $penalty['amount'] > 0) {
                Fine::create([
                    'transaction_id' => $transaction->id,
                    'penalty_type' => 'damaged_book',
                    'amount' => $penalty['amount'],
                    'points_deducted' => 0,
                    'description' => "Buku rusak - Denda Rp " . number_format($penalty['amount'], 0, ',', '.'),
                    'status' => 'unpaid',
                ]);
            }
        }
        
        if ($condition === 'lost') {
            $penalty = PenaltySetting::calculateLostPenalty();
            
            if ($penalty['type'] === 'points' && $penalty['points'] > 0) {
                // Round values for display
                $pointsRounded = round($penalty['points']);
                
                // Deduct points
                Point::create([
                    'member_id' => $transaction->member_id,
                    'amount' => -$penalty['points'],
                    'reason' => "Denda buku hilang",
                ]);
                
                Fine::create([
                    'transaction_id' => $transaction->id,
                    'penalty_type' => 'lost_book',
                    'amount' => 0,
                    'points_deducted' => $penalty['points'],
                    'description' => "Buku hilang - Pengurangan {$pointsRounded} point",
                    'status' => 'paid',
                ]);
            } elseif ($penalty['type'] === 'money' && $penalty['amount'] > 0) {
                Fine::create([
                    'transaction_id' => $transaction->id,
                    'penalty_type' => 'lost_book',
                    'amount' => $penalty['amount'],
                    'points_deducted' => 0,
                    'description' => "Buku hilang - Denda Rp " . number_format($penalty['amount'], 0, ',', '.'),
                    'status' => 'unpaid',
                ]);
            }
        }

        $conditionText = [
            'normal' => 'normal',
            'late' => 'terlambat',
            'damaged' => 'rusak',
            'lost' => 'hilang',
        ];

        session()->flash('message', 'Pengembalian berhasil dicatat dengan kondisi: ' . ($conditionText[$condition] ?? 'normal') . '.');
    }

    public function selectMember($memberId, $memberName)
    {
        $this->member_id = $memberId;
        $this->selectedMemberName = $memberName;
        $this->memberSearch = '';
        $this->showMemberDropdown = false;
    }
    
    public function selectBook($bookId)
    {
        $book = Book::find($bookId);
        if ($book) {
            $this->book_id = $bookId;
            $this->selectedBookName = $book->title . ' (Stok: ' . $book->stock . ')';
            $this->bookSearch = '';
            $this->showBookDropdown = false;
        }
    }
    
    public function clearMemberSearch()
    {
        $this->member_id = '';
        $this->selectedMemberName = '';
        $this->memberSearch = '';
        $this->showMemberDropdown = false;
    }
    
    public function clearBookSearch()
    {
        $this->book_id = '';
        $this->selectedBookName = '';
        $this->bookSearch = '';
        $this->showBookDropdown = false;
    }
    
    public function updatedMemberSearch()
    {
        $this->showMemberDropdown = !empty($this->memberSearch);
    }
    
    public function updatedBookSearch()
    {
        $this->showBookDropdown = !empty($this->bookSearch);
    }

    private function resetInputFields()
    {
        $this->member_id = '';
        $this->book_id = '';
        $this->borrowed_at = now()->format('Y-m-d');
        $this->due_date = now()->addDays(7)->format('Y-m-d');
        $this->selectedMemberName = '';
        $this->selectedBookName = '';
        $this->memberSearch = '';
        $this->bookSearch = '';
        $this->showMemberDropdown = false;
        $this->showBookDropdown = false;
    }
}
