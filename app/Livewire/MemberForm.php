<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Member;

class MemberForm extends Component
{
    use WithPagination;

    public $name, $nis, $class, $birth_date, $email, $phone;
    public $memberId;
    public $isOpen = false;
    public $search = '';
    public $generatedPassword = '';
    public $showPassword = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'nis' => 'required|string|max:255|unique:members,nis',
        'class' => 'required|string|max:255',
        'birth_date' => 'required|date',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
    ];

    public function render()
    {
        $members = Member::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('nis', 'like', '%' . $this->search . '%')
            ->orWhere('class', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.member-form', [
            'members' => $members
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
        $this->name = '';
        $this->nis = '';
        $this->class = '';
        $this->birth_date = '';
        $this->email = '';
        $this->phone = '';
        $this->memberId = null;
        $this->generatedPassword = '';
        $this->showPassword = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|max:255|unique:members,nis,' . $this->memberId,
            'class' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $member = Member::updateOrCreate(['id' => $this->memberId], [
            'name' => $this->name,
            'nis' => $this->nis,
            'class' => $this->class,
            'birth_date' => $this->birth_date,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        // Create or update user account for student
        if (!$this->memberId) {
            // Only create user for new members
            $password = $member->generatePassword();
            
            \App\Models\User::create([
                'name' => $member->name,
                'nis' => $member->nis,
                'member_id' => $member->id,
                'role' => 'student',
                'password' => $password,
            ]);

            $this->generatedPassword = $password;
            $this->showPassword = true;
        }

        session()->flash('message', 
            $this->memberId ? 'Anggota berhasil diperbarui.' : 'Anggota berhasil ditambahkan.');

        if (!$this->showPassword) {
            $this->closeModal();
            $this->resetInputFields();
        }
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        $this->memberId = $id;
        $this->name = $member->name;
        $this->nis = $member->nis;
        $this->class = $member->class;
        $this->birth_date = $member->birth_date->format('Y-m-d');
        $this->email = $member->email;
        $this->phone = $member->phone;

        $this->openModal();
    }

    public function closePasswordModal()
    {
        $this->showPassword = false;
        $this->generatedPassword = '';
        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Member::find($id)->delete();
        session()->flash('message', 'Anggota berhasil dihapus.');
    }
}
