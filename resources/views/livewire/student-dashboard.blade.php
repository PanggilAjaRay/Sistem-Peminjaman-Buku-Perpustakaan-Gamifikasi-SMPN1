<div>
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">Dashboard Siswa</h1>
        <p class="text-gray-500 mt-1 md:mt-2 text-base md:text-lg">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-green-700 font-medium">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 {{ $overdueLoans->count() > 0 ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Keterlambatan Card (only shown if there are overdue loans) -->
        @if($overdueLoans->count() > 0)
        <a href="{{ route('student.fines') }}" class="block bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl shadow-sm border border-red-200 p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-red-700">Keterlambatan</h3>
                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
            <div class="space-y-3">
                @foreach($overdueLoans->take(2) as $loan)
                    <div class="bg-white rounded-xl p-4 border border-red-200">
                        <div class="flex items-start mb-2">
                            <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 text-sm truncate">{{ $loan->book->title }}</h4>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $loan->book->author }}</p>
                            </div>
                        </div>
                        <div class="text-xs text-red-600 space-y-0.5">
                            <p><b>Jatuh tempo: {{ $loan->due_date->format('d M Y') }}</b></p>
                            <p class="font-semibold">Terlambat {{ abs(ceil($loan->due_date->diffInDays(now(), false))) }} hari</p>
                        </div>
                    </div>
                @endforeach
                @if($overdueLoans->count() > 2)
                    <p class="text-xs text-red-600 text-center font-medium">+{{ $overdueLoans->count() - 2 }} buku lainnya terlambat</p>
                @endif
                <div class="mt-3 pt-3 border-t border-red-200">
                    <p class="text-xs text-red-700 font-medium flex items-center justify-center">
                        Klik untuk lihat riwayat denda
                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </p>
                </div>
            </div>
        </a>
        @endif

        <!-- Peminjaman Aktif Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Peminjaman Aktif</h3>
            @if($activeLoans->count() > 0)
                <div class="space-y-3">
                    @foreach($activeLoans->take(2) as $loan)
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <div class="flex items-start mb-2">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 text-sm truncate">{{ $loan->book->title }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $loan->book->author }}</p>
                                </div>
                            </div>
                            <div class="text-xs text-gray-600 space-y-0.5">
                                <p>Dipinjam: {{ $loan->borrowed_at->format('d M Y') }}</p>
                                <p><b>Jatuh tempo: {{ $loan->due_date->format('d M Y') }}</b></p>
                            </div>
                        </div>
                    @endforeach
                    @if($pendingLoans->count() > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center text-yellow-600">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium">{{ $pendingLoans->count() }} Menunggu Approval</span>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">Tidak ada peminjaman aktif</p>
                    @if($pendingLoans->count() > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center justify-center text-yellow-600">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium">{{ $pendingLoans->count() }} Menunggu Approval</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Point Saya Card -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-sm border border-purple-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-2">
                
            </div>
            <div class="text-center">
                <p class="text-xl font-medium text-purple-700 mb-2">Point Saya</p>
                <p class="text-5xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-1">{{ number_format($totalPoints) }}</p>
                <span class="text-sm text-purple-600">Poin</span>
                <p class="text-xs text-purple-600 mt-3">Total poin yang terkumpul</p>
            </div>
        </div>

        <!-- Misi Saya Card -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl shadow-sm border border-green-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Misi Saya</h3>
            @if($missions->count() > 0)
                <div class="space-y-3">
                    @foreach($missions->take(1) as $mission)
                        <div class="bg-white rounded-xl p-4 border border-green-200">
                            <div class="flex items-start mb-2">
                                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 text-sm truncate">{{ $mission->title }}</h4>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $mission->description }}</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Reward: {{ $mission->reward_points }} Poin
                                </span>
                            </div>
                        </div>
                    @endforeach
                    @if($missions->count() > 1)
                        <p class="text-xs text-green-600 text-center">+{{ $missions->count() - 1 }} misi lainnya</p>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">Tidak ada misi tersedia</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pending Loans Details -->
    @if($pendingLoans->count() > 0)
        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-6 mb-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-yellow-800">Permintaan Peminjaman (Menunggu Approval)</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pendingLoans as $loan)
                    <div class="bg-white rounded-xl p-4 border border-yellow-200 hover:shadow-md transition-shadow duration-200">
                        <div class="flex items-start mb-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm truncate">{{ $loan->book->title }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $loan->book->author }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 mb-2">Diajukan: {{ $loan->borrowed_at->format('d M Y') }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Menunggu Persetujuan
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif


    <!-- Book Catalog -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Katalog Buku</h2>
        </div>
        
        <!-- Search and Filter -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
            <!-- Search -->
            <div class="relative w-full md:w-[70%]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live="search" type="text" placeholder="Cari buku berdasarkan judul atau penulis..." 
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150 sm:text-sm">
            </div>
            
            <!-- Category Filter -->
            <div class="relative w-full md:w-[30%]">
                <select wire:model.live="categoryFilter"
                    class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150 sm:text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($books as $book)
                <div class="group bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg hover:border-blue-200 transition-all duration-200">
                    <!-- Book Cover -->
                    <div class="w-full h-48 overflow-hidden bg-gray-50">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Book Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1">{{ $book->title }}</h3>
                        <p class="text-xs text-gray-500 mb-1">{{ $book->author }}</p>
                        <p class="text-xs text-gray-400 mb-2">{{ $book->publisher }}</p>
                        
                        <!-- Category Badge -->
                        @if($book->category)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200 mb-3">
                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $book->category->name }}
                            </span>
                        @endif
                        
                        <!-- Stock Badge -->
                        <div class="mb-3">
                            @if($book->stock > 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Tersedia: {{ $book->stock }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Tidak tersedia
                                </span>
                            @endif
                        </div>

                        <!-- Borrow Button with better UX -->
                        <button 
                            wire:click="borrowBook({{ $book->id }})" 
                            wire:loading.attr="disabled"
                            wire:target="borrowBook({{ $book->id }})"
                            @if($book->stock <= 0) disabled @endif
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ $book->stock > 0 ? 'bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                            <span wire:loading.remove wire:target="borrowBook({{ $book->id }})">
                                Pinjam Buku
                            </span>
                            <span wire:loading wire:target="borrowBook({{ $book->id }})" class="inline-flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">Tidak ada buku ditemukan</p>
                    <p class="text-gray-400 text-sm mt-1">Coba kata kunci pencarian yang berbeda</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    </div>

    
