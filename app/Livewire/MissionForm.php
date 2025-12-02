<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Mission;

class MissionForm extends Component
{
    use WithPagination;

    public $title, $description, $condition_type, $condition_value, $reward_points, $is_active = true;
    public $missionId;
    public $isOpen = false;
    public $search = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'condition_type' => 'required|string',
        'condition_value' => 'required|string',
        'reward_points' => 'required|integer|min:1',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        $missions = Mission::where('title', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.mission-form', [
            'missions' => $missions
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->description = '';
        $this->condition_type = '';
        $this->condition_value = '';
        $this->reward_points = '';
        $this->is_active = true;
        $this->missionId = null;
    }

    public function store()
    {
        $this->validate();

        Mission::updateOrCreate(['id' => $this->missionId], [
            'title' => $this->title,
            'description' => $this->description,
            'condition_type' => $this->condition_type,
            'condition_value' => $this->condition_value,
            'reward_points' => $this->reward_points,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 
            $this->missionId ? 'Misi berhasil diperbarui.' : 'Misi berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $mission = Mission::findOrFail($id);
        $this->missionId = $id;
        $this->title = $mission->title;
        $this->description = $mission->description;
        $this->condition_type = $mission->condition_type;
        $this->condition_value = $mission->condition_value;
        $this->reward_points = $mission->reward_points;
        $this->is_active = $mission->is_active;

        $this->openModal();
    }

    public function delete($id)
    {
        Mission::find($id)->delete();
        session()->flash('message', 'Misi berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $mission = Mission::find($id);
        $mission->is_active = !$mission->is_active;
        $mission->save();
        session()->flash('message', 'Status misi berhasil diubah.');
    }
}
