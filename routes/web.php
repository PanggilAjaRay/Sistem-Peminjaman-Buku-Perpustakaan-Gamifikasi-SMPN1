<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Login;
use App\Livewire\AdminDashboard;
use App\Livewire\StudentDashboard;
use App\Livewire\StudentLeaderboard;
use App\Livewire\BookForm;
use App\Livewire\MemberForm;
use App\Livewire\MissionForm;
use App\Livewire\CirculationForm;

// Redirect root based on authentication
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    }
    return redirect()->route('login');
});

// Guest routes
Route::get('/login', Login::class)->name('login');

// Logout route
Route::post('/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Admin routes (protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/books', BookForm::class)->name('books');
    Route::get('/members', MemberForm::class)->name('members');
    Route::get('/missions', MissionForm::class)->name('missions');
    Route::get('/circulation', CirculationForm::class)->name('circulation');
});

// Student routes (protected by auth and student middleware)
Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/student', StudentDashboard::class)->name('student.dashboard');
    Route::get('/student/history', \App\Livewire\StudentHistory::class)->name('student.history');
    Route::get('/student/leaderboard', StudentLeaderboard::class)->name('student.leaderboard');
});
