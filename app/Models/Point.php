<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = [
        'member_id',
        'amount',
        'reason',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
