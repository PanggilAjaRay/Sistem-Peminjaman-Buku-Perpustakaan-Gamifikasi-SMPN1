<div>
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">🏆 Leaderboard</h1>
        <p class="text-gray-500 mt-1 md:mt-2 text-base md:text-lg">Peringkat Siswa Berdasarkan Poin</p>
    </div>

    <!-- My Points Card -->
    @if($member)
        <div class="mb-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 md:p-6 rounded-2xl shadow-lg shadow-blue-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl text-blue-100 font-bold mb-2">{{ $member->name }}</h2>
                    <p class="text-blue-100">{{ $member->nis }} • {{ $member->class }}</p>
                </div>
                <div class="text-right">
                    <div class="text-5xl font-bold mb-1">{{ $myPoints }}</div>
                    <div class="text-lg text-blue-100">Total Poin</div>
                    @if($myRank)
                            <span class="text-sm">Peringkat: </span>
                            <span class="text-2xl font-bold">#{{ $myRank }}</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Leaderboard -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6">
        <h2 class="text-lg font-bold mb-4 text-gray-900">🏅 Peringkat Semua Siswa</h2>
        <div class="space-y-3">
            @forelse($leaderboard as $index => $item)
                <div class="flex items-center justify-between p-4 rounded-lg transition-all {{ $item['member']->id === $member->id ? 'bg-gradient-to-r from-blue-50 to-purple-50 border-2 border-blue-500 shadow-md' : 'bg-gray-50 hover:bg-gray-100' }}">
                    <div class="flex items-center gap-4">
                        <!-- Rank Badge -->
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg
                            {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : '' }}
                            {{ $index === 1 ? 'bg-gray-300 text-gray-800' : '' }}
                            {{ $index === 2 ? 'bg-orange-400 text-orange-900' : '' }}
                            {{ $index > 2 ? 'bg-blue-500 text-white' : '' }}
                        ">
                            @if($index === 0)
                                🥇
                            @elseif($index === 1)
                                🥈
                            @elseif($index === 2)
                                🥉
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        
                        <!-- Student Info -->
                        <div>
                            <div class="font-bold text-lg {{ $item['member']->id === $member->id ? 'text-blue-700' : 'text-gray-900' }}">
                                {{ $item['member']->name }}
                                @if($item['member']->id === $member->id)
                                    <span class="text-sm bg-blue-500 text-white px-2 py-1 rounded-full ml-2">Anda</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600">{{ $item['member']->class }} • NIS: {{ $item['member']->nis }}</div>
                        </div>
                    </div>
                    
                    <!-- Points -->
                    <div class="text-right">
                        <div class="text-2xl font-bold {{ $item['member']->id === $member->id ? 'text-blue-700' : 'text-gray-900' }}">
                            {{ $item['total_points'] }}
                        </div>
                        <div class="text-sm text-gray-600">poin</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <p class="text-lg font-medium">Belum ada data poin</p>
                    <p class="text-sm">Mulai pinjam buku dan selesaikan misi untuk mendapatkan poin!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
