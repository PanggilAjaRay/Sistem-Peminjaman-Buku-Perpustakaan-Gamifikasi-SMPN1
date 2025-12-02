<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;

class StudentHistory extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $member = auth()->user()->member;

        $transactions = collect();

        if ($member) {
            $transactions = Transaction::with('book')
                ->where('member_id', $member->id)
                ->when($this->search, function($query) {
                    $query->whereHas('book', function($q) {
                        $q->where('title', 'like', '%' . $this->search . '%')
                          ->orWhere('author', 'like', '%' . $this->search . '%');
                    });
                })
                ->latest()
                ->paginate(10);
        }

        return view('livewire.student-history', [
            'transactions' => $transactions
        ]);
    }
}
