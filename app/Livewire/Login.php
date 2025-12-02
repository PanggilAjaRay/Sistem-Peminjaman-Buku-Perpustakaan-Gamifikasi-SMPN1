<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Login extends Component
{
    public $identifier = ''; // Can be email or NIS
    public $password = '';
    public $remember = false;
    public $errorMessage = '';

    protected $rules = [
        'identifier' => 'required|string',
        'password' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.login')->layout('components.layouts.guest');
    }

    public function login()
    {
        $this->validate();

        $this->errorMessage = '';

        // Determine if identifier is email or NIS
        $isEmail = filter_var($this->identifier, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            // Admin login with email
            $credentials = [
                'email' => $this->identifier,
                'password' => $this->password,
            ];
        } else {
            // Student login with NIS
            $credentials = [
                'nis' => $this->identifier,
                'password' => $this->password,
            ];
        }

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            // Redirect based on role
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('student.dashboard');
            }
        }

        $this->errorMessage = 'Email/NIS atau password salah.';
    }

    public function mount()
    {
        // Redirect if already authenticated
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('student.dashboard');
            }
        }
    }
}
