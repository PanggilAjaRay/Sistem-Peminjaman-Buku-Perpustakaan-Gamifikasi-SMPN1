<div>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Dashboard Admin</h1>
            <p class="text-gray-500 mt-2 text-lg">Selamat datang, Administrator</p>
        </div>
        <div class="text-sm text-gray-500 font-medium">{{ now()->format('l, d F Y') }}</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Books -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-500 mb-2">Total Buku</h3>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\Book::count() }}</div>
            <div class="flex items-center text-sm text-green-600 font-medium">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Buku Tersedia
            </div>
        </div>

        <!-- Total Members -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-pink-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-500 mb-2">Total Anggota</h3>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\Member::count() }}</div>
            <p class="text-sm text-gray-500 font-medium">Siswa Terdaftar</p>
        </div>

        <!-- Active Loans -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-500 mb-2">Peminjaman Aktif</h3>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\Transaction::where('status', 'approved')->count() }}</div>
            <p class="text-sm text-gray-500 font-medium">Sedang Dipinjam</p>
        </div>

        <!-- Total Missions -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-500 mb-2">Total Misi</h3>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\Mission::count() }}</div>
            <p class="text-sm text-gray-500 font-medium">Tantangan Berjalan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-4">
                @php
                    $recentActivities = \App\Models\Transaction::with(['book', 'member'])
                        ->latest()
                        ->limit(5)
                        ->get();
                @endphp
                @forelse($recentActivities as $activity)
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $activity->member->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $activity->book->title }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                            {{ $activity->status === 'approved' ? 'bg-blue-50 text-blue-700' : '' }}
                            {{ $activity->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : '' }}
                            {{ $activity->status === 'returned' ? 'bg-green-50 text-green-700' : '' }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium text-sm">Belum ada aktivitas terbaru</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Leaderboard Preview -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Top Siswa (Leaderboard)</h3>
            <div class="space-y-4">
                @php
                    $topStudents = \App\Models\Member::withSum('missions', 'points')
                        ->orderByDesc('missions_sum_points')
                        ->limit(5)
                        ->get();
                @endphp
                @forelse($topStudents as $index => $student)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex-shrink-0 w-8 h-8 {{ $index === 0 ? 'bg-gradient-to-br from-yellow-400 to-yellow-500' : ($index === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400' : ($index === 2 ? 'bg-gradient-to-br from-amber-600 to-amber-700' : 'bg-gray-200')) )} rounded-lg flex items-center justify-center mr-3">
                            <span class="text-sm font-bold {{ $index < 3 ? 'text-white' : 'text-gray-600' }}">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">{{ $student->name }}</p>
                            <p class="text-xs text-gray-500">{{ $student->class }}</p>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="text-sm font-bold text-gray-900">{{ $student->missions_sum_points ?? 0 }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0V5.625a2.25 2.25 0 11-4.5 0v9.75M12 12v.008v-.008z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium text-sm">Belum ada data poin</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
