<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\Mission;

class AdminDashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_books' => Book::count(),
            'total_members' => Member::count(),
            'active_loans' => Transaction::where('status', 'borrowed')->count(),
            'total_missions' => Mission::count(),
        ];

        $recentMembers = Member::latest()->take(5)->get();
        $recentTransactions = Transaction::with(['book', 'member'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin-dashboard', compact('stats', 'recentMembers', 'recentTransactions'));
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
