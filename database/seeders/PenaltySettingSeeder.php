<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenaltySetting;

class PenaltySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default penalty settings
        $penalties = [
            [
                'penalty_type' => 'late_return',
                'value_type' => 'points',
                'value_per_day' => 5,
                'fixed_value' => null,
                'is_active' => true,
            ],
            [
                'penalty_type' => 'damaged_book',
                'value_type' => 'money',
                'value_per_day' => null,
                'fixed_value' => 10000,
                'is_active' => true,
            ],
            [
                'penalty_type' => 'lost_book',
                'value_type' => 'money',
                'value_per_day' => null,
                'fixed_value' => 50000,
                'is_active' => true,
            ],
        ];

        foreach ($penalties as $penalty) {
            PenaltySetting::updateOrCreate(
                ['penalty_type' => $penalty['penalty_type']],
                $penalty
            );
        }
    }
}
