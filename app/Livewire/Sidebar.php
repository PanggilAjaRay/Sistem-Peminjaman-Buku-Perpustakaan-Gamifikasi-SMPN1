<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        return view('livewire.sidebar');
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
