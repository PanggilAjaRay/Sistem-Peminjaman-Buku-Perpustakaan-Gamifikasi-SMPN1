<?php

namespace App\Livewire;

use Livewire\Component;

class PenaltySettings extends Component
{
    public $activeTab = 'payments'; // payments or settings
    public $lateReturn = [];
    public $damagedBook = [];
    public $lostBook = [];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Load late return settings
        $lateSetting = \App\Models\PenaltySetting::where('penalty_type', 'late_return')->first();
        if ($lateSetting) {
            $this->lateReturn = [
                'id' => $lateSetting->id,
                'value_type' => $lateSetting->value_type,
                'value_per_day' => $lateSetting->value_per_day,
                'is_active' => $lateSetting->is_active,
            ];
        } else {
            $this->lateReturn = [
                'id' => null,
                'value_type' => 'points',
                'value_per_day' => 5,
                'is_active' => true,
            ];
        }

        // Load damaged book settings
        $damagedSetting = \App\Models\PenaltySetting::where('penalty_type', 'damaged_book')->first();
        if ($damagedSetting) {
            $this->damagedBook = [
                'id' => $damagedSetting->id,
                'value_type' => $damagedSetting->value_type,
                'fixed_value' => $damagedSetting->fixed_value,
                'is_active' => $damagedSetting->is_active,
            ];
        } else {
            $this->damagedBook = [
                'id' => null,
                'value_type' => 'money',
                'fixed_value' => 10000,
                'is_active' => true,
            ];
        }

        // Load lost book settings
        $lostSetting = \App\Models\PenaltySetting::where('penalty_type', 'lost_book')->first();
        if ($lostSetting) {
            $this->lostBook = [
                'id' => $lostSetting->id,
                'value_type' => $lostSetting->value_type,
                'fixed_value' => $lostSetting->fixed_value,
                'is_active' => $lostSetting->is_active,
            ];
        } else {
            $this->lostBook = [
                'id' => null,
                'value_type' => 'money',
                'fixed_value' => 50000,
                'is_active' => true,
            ];
        }
    }

    public function saveSettings()
    {
        // Validate
        $this->validate([
            'lateReturn.value_per_day' => 'required|numeric|min:0',
            'damagedBook.fixed_value' => 'required|numeric|min:0',
            'lostBook.fixed_value' => 'required|numeric|min:0',
        ]);

        // Save late return
        \App\Models\PenaltySetting::updateOrCreate(
            ['penalty_type' => 'late_return'],
            [
                'value_type' => $this->lateReturn['value_type'],
                'value_per_day' => $this->lateReturn['value_per_day'],
                'fixed_value' => null,
                'is_active' => $this->lateReturn['is_active'],
            ]
        );

        // Save damaged book
        \App\Models\PenaltySetting::updateOrCreate(
            ['penalty_type' => 'damaged_book'],
            [
                'value_type' => $this->damagedBook['value_type'],
                'value_per_day' => null,
                'fixed_value' => $this->damagedBook['fixed_value'],
                'is_active' => $this->damagedBook['is_active'],
            ]
        );

        // Save lost book
        \App\Models\PenaltySetting::updateOrCreate(
            ['penalty_type' => 'lost_book'],
            [
                'value_type' => $this->lostBook['value_type'],
                'value_per_day' => null,
                'fixed_value' => $this->lostBook['fixed_value'],
                'is_active' => $this->lostBook['is_active'],
            ]
        );

        session()->flash('message', 'Pengaturan denda berhasil disimpan.');
        $this->loadSettings();
    }

    public function markAsPaid($fineId)
    {
        $fine = \App\Models\Fine::findOrFail($fineId);
        $fine->status = 'paid';
        $fine->save();

        session()->flash('message', 'Denda berhasil ditandai sebagai lunas.');
    }

    public function render()
    {
        // Get unpaid fines with amount > 0 (monetary fines)
        $unpaidFines = \App\Models\Fine::where('status', 'unpaid')
            ->where('amount', '>', 0)
            ->with(['transaction.member', 'transaction.book'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.penalty-settings', [
            'unpaidFines' => $unpaidFines,
        ]);
    }
}

