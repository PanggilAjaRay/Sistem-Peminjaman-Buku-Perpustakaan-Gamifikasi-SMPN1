<div class="login-container">
    <div class="login-background">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>
    
    <div class="login-card">
        <div class="login-header">
            <div class="logo-container">
                <svg xmlns="http://www.w3.org/2000/svg" class="logo-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h1 class="login-title">Perpustakaan SMPN 1</h1>
            <p class="login-subtitle">Masuk ke akun Anda</p>
        </div>

        @if($errorMessage)
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="alert-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $errorMessage }}
            </div>
        @endif

        <form wire:submit.prevent="login" class="login-form">
            <div class="form-group">
                <label for="identifier" class="form-label">
                    <svg xmlns="http://www.w3.org/2000/svg" class="label-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Email / NIS
                </label>
                <input 
                    wire:model="identifier" 
                    type="text" 
                    id="identifier"
                    class="form-input" 
                    placeholder="Masukkan email atau NIS"
                    autofocus
                >
                @error('identifier') 
                    <span class="error-message">{{ $message }}</span> 
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">
                    <svg xmlns="http://www.w3.org/2000/svg" class="label-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Password
                </label>
                <input 
                    wire:model="password" 
                    type="password" 
                    id="password"
                    class="form-input" 
                    placeholder="Masukkan password"
                >
                @error('password') 
                    <span class="error-message">{{ $message }}</span> 
                @enderror
            </div>

            <div class="form-group-checkbox">
                <label class="checkbox-label">
                    <input wire:model="remember" type="checkbox" class="checkbox-input">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn-login">
                <span>Masuk</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </form>

        <div class="login-footer">
            <p class="footer-text">
                <strong>Admin:</strong> admin@library.com | 
                <strong>Siswa:</strong> Gunakan NIS Anda
            </p>
        </div>
    </div>
</div>
