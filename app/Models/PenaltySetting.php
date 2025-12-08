<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenaltySetting extends Model
{
    protected $fillable = [
        'penalty_type',
        'value_type',
        'value_per_day',
        'fixed_value',
        'is_active',
    ];

    protected $casts = [
        'value_per_day' => 'decimal:2',
        'fixed_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get penalty setting by type
     */
    public static function getByType($type)
    {
        return self::where('penalty_type', $type)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Calculate penalty for late return
     */
    public static function calculateLatePenalty($daysLate)
    {
        $setting = self::getByType('late_return');
        
        if (!$setting || $daysLate <= 0) {
            return ['type' => null, 'amount' => 0, 'points' => 0];
        }

        if ($setting->value_type === 'points') {
            return [
                'type' => 'points',
                'amount' => 0,
                'points' => $daysLate * $setting->value_per_day,
            ];
        } else {
            return [
                'type' => 'money',
                'amount' => $daysLate * $setting->value_per_day,
                'points' => 0,
            ];
        }
    }

    /**
     * Calculate penalty for damaged book
     */
    public static function calculateDamagedPenalty()
    {
        $setting = self::getByType('damaged_book');
        
        if (!$setting) {
            return ['type' => 'money', 'amount' => 0, 'points' => 0];
        }

        return [
            'type' => $setting->value_type,
            'amount' => $setting->value_type === 'money' ? $setting->fixed_value : 0,
            'points' => $setting->value_type === 'points' ? $setting->fixed_value : 0,
        ];
    }

    /**
     * Calculate penalty for lost book
     */
    public static function calculateLostPenalty()
    {
        $setting = self::getByType('lost_book');
        
        if (!$setting) {
            return ['type' => 'money', 'amount' => 0, 'points' => 0];
        }

        return [
            'type' => $setting->value_type,
            'amount' => $setting->value_type === 'money' ? $setting->fixed_value : 0,
            'points' => $setting->value_type === 'points' ? $setting->fixed_value : 0,
        ];
    }
}
