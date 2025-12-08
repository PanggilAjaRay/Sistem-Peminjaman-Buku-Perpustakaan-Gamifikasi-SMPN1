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

        // Get leaderboard data (top 10 students by points)
        $leaderboard = \Illuminate\Support\Facades\DB::table('points')
            ->select('member_id', \Illuminate\Support\Facades\DB::raw('SUM(amount) as total_points'))
            ->groupBy('member_id')
            ->orderByDesc('total_points')
            ->take(10)
            ->get()
            ->map(function($item) {
                $member = Member::find($item->member_id);
                return [
                    'member' => $member,
                    'total_points' => $item->total_points
                ];
            });

        return view('livewire.admin-dashboard', compact('stats', 'recentMembers', 'recentTransactions', 'leaderboard'));
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
