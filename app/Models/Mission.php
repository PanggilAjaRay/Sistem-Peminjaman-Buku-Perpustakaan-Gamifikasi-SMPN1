<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $fillable = [
        'title',
        'description',
        'condition_type',
        'condition_value',
        'reward_points',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class, 'mission_member')
            ->withPivot('status', 'completed_at')
            ->withTimestamps();
    }
}
