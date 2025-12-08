<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Fine;
use App\Models\Transaction;

class StudentFines extends Component
{
    public function render()
    {
        $member = auth()->user()->member;
        
        // Get all fines for this student through their transactions
        $fines = Fine::whereHas('transaction', function($query) use ($member) {
            $query->where('member_id', $member->id);
        })
        ->with(['transaction.book'])
        ->orderBy('created_at', 'desc')
        ->get();

        // Calculate totals
        $totalMoneyFines = $fines->where('status', 'unpaid')->sum('amount');
        $totalPointsDeducted = $fines->sum('points_deducted');
        $unpaidCount = $fines->where('status', 'unpaid')->count();

        return view('livewire.student-fines', [
            'fines' => $fines,
            'totalMoneyFines' => $totalMoneyFines,
            'totalPointsDeducted' => $totalPointsDeducted,
            'unpaidCount' => $unpaidCount,
        ]);
    }
}
