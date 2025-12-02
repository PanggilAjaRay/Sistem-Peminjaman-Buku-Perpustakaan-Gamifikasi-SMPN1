<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Member;
use App\Models\Point;
use Illuminate\Support\Facades\DB;

class StudentLeaderboard extends Component
{
    public function render()
    {
        $member = auth()->user()->member;
        
        // Get current student's total points
        $myPoints = 0;
        $myRank = null;
        
        if ($member) {
            $myPoints = Point::where('member_id', $member->id)->sum('amount');
            
            // Calculate rank
            $rankedMembers = DB::table('points')
                ->select('member_id', DB::raw('SUM(amount) as total_points'))
                ->groupBy('member_id')
                ->orderByDesc('total_points')
                ->get();
            
            $myRank = $rankedMembers->search(function($item) use ($member) {
                return $item->member_id === $member->id;
            });
            
            if ($myRank !== false) {
                $myRank = $myRank + 1; // Convert to 1-based index
            }
        }
        
        // Get leaderboard (all students with points)
        $leaderboard = DB::table('points')
            ->select('member_id', DB::raw('SUM(amount) as total_points'))
            ->groupBy('member_id')
            ->orderByDesc('total_points')
            ->get()
            ->map(function($item) {
                $member = Member::find($item->member_id);
                return [
                    'member' => $member,
                    'total_points' => $item->total_points
                ];
            });

        return view('livewire.student-leaderboard', [
            'member' => $member,
            'myPoints' => $myPoints,
            'myRank' => $myRank,
            'leaderboard' => $leaderboard,
        ]);
    }
}
