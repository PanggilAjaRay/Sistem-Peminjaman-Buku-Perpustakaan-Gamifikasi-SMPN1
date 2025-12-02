<div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Admin</h1>
        <p class="text-gray-500 mt-2">Selamat datang, <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span> 👋</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Books -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg shadow-indigo-500/30">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Koleksi</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Buku</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_books'] }}</p>
            </div>
        </div>

        <!-- Total Members -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl shadow-lg shadow-pink-500/30">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-pink-600 bg-pink-50 px-2.5 py-1 rounded-lg">Siswa</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Anggota</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_members'] }}</p>
            </div>
        </div>

        <!-- Active Loans -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg shadow-blue-500/30">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Aktif</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Peminjaman Aktif</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['active_loans'] }}</p>
            </div>
        </div>

        <!-- Total Missions -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg shadow-yellow-500/30">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-2.5 py-1 rounded-lg">Misi</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Misi</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_missions'] }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Akses Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('books') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100 hover:border-indigo-200 hover:shadow-md hover:scale-105 transition-all duration-200">
                <div class="p-3 bg-indigo-100 rounded-xl mb-3 group-hover:bg-indigo-200 transition-colors duration-200">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">Kelola Buku</span>
            </a>

            <a href="{{ route('members') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100 hover:border-pink-200 hover:shadow-md hover:scale-105 transition-all duration-200">
                <div class="p-3 bg-pink-100 rounded-xl mb-3 group-hover:bg-pink-200 transition-colors duration-200">
                    <svg class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-pink-600 transition-colors duration-200">Kelola Anggota</span>
            </a>

            <a href="{{ route('missions') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100 hover:border-yellow-200 hover:shadow-md hover:scale-105 transition-all duration-200">
                <div class="p-3 bg-yellow-100 rounded-xl mb-3 group-hover:bg-yellow-200 transition-colors duration-200">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-yellow-600 transition-colors duration-200">Kelola Misi</span>
            </a>

            <a href="{{ route('circulation') }}" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-100 hover:border-blue-200 hover:shadow-md hover:scale-105 transition-all duration-200">
                <div class="p-3 bg-blue-100 rounded-xl mb-3 group-hover:bg-blue-200 transition-colors duration-200">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition-colors duration-200">Sirkulasi</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Members -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900">Anggota Terbaru</h2>
                <a href="{{ route('members') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentMembers as $member)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-pink-500/30">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $member->name }}</p>
                            <p class="text-sm text-gray-500">NIS: {{ $member->nis }} • Kelas: {{ $member->class }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada anggota terdaftar</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900">Transaksi Terbaru</h2>
                <a href="{{ route('circulation') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-150">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $transaction->book->title ?? 'Buku tidak ditemukan' }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-sm text-gray-500 truncate">{{ $transaction->member->name ?? 'Anggota tidak ditemukan' }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $transaction->status === 'approved' ? 'bg-green-50 text-green-700' : '' }}
                                    {{ $transaction->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : '' }}
                                    {{ $transaction->status === 'returned' ? 'bg-blue-50 text-blue-700' : '' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Belum ada transaksi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
