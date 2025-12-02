<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name',
        'nis',
        'class',
        'birth_date',
        'email',
        'phone',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function missions()
    {
        return $this->belongsToMany(Mission::class, 'mission_member')
            ->withPivot('status', 'completed_at')
            ->withTimestamps();
    }

    public function generatePassword()
    {
        return $this->birth_date->format('Ymd');
    }
}
