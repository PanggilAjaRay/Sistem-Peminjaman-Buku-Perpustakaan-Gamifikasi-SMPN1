<div>
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Portal Siswa</h1>
        <div class="form-group mb-0">
            <select wire:model.live="selectedMemberId" class="form-input">
                <option value="">Pilih Siswa</option>
                @foreach($members as $m)
                    <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->nis }})</option>
                @endforeach
            </select>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="card bg-green-100 text-green-700 mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="card bg-red-100 text-red-700 mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($member)
        <!-- Profile Card -->
        <div class="card mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">{{ $member->name }}</h2>
                    <p class="text-gray-600">{{ $member->nis }} • {{ $member->class }}</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-primary">{{ $totalPoints }}</div>
                    <div class="text-sm text-gray-600">Total Poin</div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-2 mb-4 overflow-x-auto">
            <button wire:click="$set('activeTab', 'profile')" 
                    class="btn {{ $activeTab === 'profile' ? 'btn-primary' : 'bg-gray-200 text-gray-700' }}">
                Profil
            </button>
            <button wire:click="$set('activeTab', 'catalog')" 
                    class="btn {{ $activeTab === 'catalog' ? 'btn-primary' : 'bg-gray-200 text-gray-700' }}">
                Katalog Buku
            </button>
            <button wire:click="$set('activeTab', 'history')" 
                    class="btn {{ $activeTab === 'history' ? 'btn-primary' : 'bg-gray-200 text-gray-700' }}">
                Riwayat Peminjaman
            </button>
            <button wire:click="$set('activeTab', 'missions')" 
                    class="btn {{ $activeTab === 'missions' ? 'btn-primary' : 'bg-gray-200 text-gray-700' }}">
                Misi
            </button>
            <button wire:click="$set('activeTab', 'leaderboard')" 
                    class="btn {{ $activeTab === 'leaderboard' ? 'btn-primary' : 'bg-gray-200 text-gray-700' }}">
                Leaderboard
            </button>
        </div>

        <!-- Tab Content -->
        @if($activeTab === 'profile')
            <div class="card">
                <h3 class="font-bold mb-4">Informasi Profil</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Nama Lengkap</label>
                        <p class="font-medium">{{ $member->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">NIS</label>
                        <p class="font-medium">{{ $member->nis }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Kelas</label>
                        <p class="font-medium">{{ $member->class }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <p class="font-medium">{{ $member->email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Telepon</label>
                        <p class="font-medium">{{ $member->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Total Poin</label>
                        <p class="font-medium text-primary text-xl">{{ $totalPoints }}</p>
                    </div>
                </div>
            </div>

        @elseif($activeTab === 'catalog')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($books as $book)
                    <div class="card">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover rounded-t-lg mb-3">
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-t-lg mb-3 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif
                        <h3 class="font-bold mb-2">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-600 mb-1">Penulis: {{ $book->author }}</p>
                        <p class="text-sm text-gray-600 mb-1">Penerbit: {{ $book->publisher }}</p>
                        <p class="text-sm text-gray-600 mb-2">Tahun: {{ $book->year }}</p>
                        <div class="flex justify-between items-center mb-3">
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-sm">
                                Stok: {{ $book->stock }}
                            </span>
                            @if($book->category)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm">
                                    {{ $book->category }}
                                </span>
                            @endif
                        </div>
                        <button wire:click="borrowBook({{ $book->id }})" 
                                class="w-full btn btn-primary text-sm">
                            📚 Pinjam Buku
                        </button>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-8">
                        Tidak ada buku tersedia.
                    </div>
                @endforelse
            </div>

        @elseif($activeTab === 'history')
            <!-- Pending Approval Card -->
            <div class="card mb-4 border-l-4 border-yellow-500">
                <h3 class="font-bold mb-4 text-yellow-700">📋 Peminjaman Menunggu Approval</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left p-3">Buku</th>
                                <th class="text-left p-3">Tgl Pinjam</th>
                                <th class="text-left p-3">Jatuh Tempo</th>
                                <th class="text-left p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingBorrowings as $transaction)
                                <tr class="border-b border-gray-100">
                                    <td class="p-3">{{ $transaction->book->title }}</td>
                                    <td class="p-3">{{ \Carbon\Carbon::parse($transaction->borrowed_at)->format('d/m/Y') }}</td>
                                    <td class="p-3">{{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-sm">Menunggu Approval</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center p-4 text-gray-500">Tidak ada peminjaman menunggu approval.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Borrowings Card -->
            <div class="card mb-4 border-l-4 border-blue-500">
                <h3 class="font-bold mb-4 text-blue-700">📚 Peminjaman Aktif</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left p-3">Buku</th>
                                <th class="text-left p-3">Tgl Pinjam</th>
                                <th class="text-left p-3">Jatuh Tempo</th>
                                <th class="text-left p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeBorrowings as $transaction)
                                <tr class="border-b border-gray-100">
                                    <td class="p-3">{{ $transaction->book->title }}</td>
                                    <td class="p-3">{{ \Carbon\Carbon::parse($transaction->borrowed_at)->format('d/m/Y') }}</td>
                                    <td class="p-3">
                                        @php
                                            $isLate = \Carbon\Carbon::parse($transaction->due_date)->isPast();
                                        @endphp
                                        <span class="{{ $isLate ? 'text-red-600 font-bold' : '' }}">
                                            {{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        @if(\Carbon\Carbon::parse($transaction->due_date)->isPast())
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-sm">Terlambat</span>
                                        @else
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm">Dipinjam</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center p-4 text-gray-500">Tidak ada peminjaman aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- History Card -->
            <div class="card border-l-4 border-gray-400">
                <h3 class="font-bold mb-4 text-gray-700">📖 Riwayat Peminjaman</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left p-3">Buku</th>
                                <th class="text-left p-3">Tgl Pinjam</th>
                                <th class="text-left p-3">Jatuh Tempo</th>
                                <th class="text-left p-3">Tgl Kembali</th>
                                <th class="text-left p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowingHistory as $transaction)
                                <tr class="border-b border-gray-100">
                                    <td class="p-3">{{ $transaction->book->title }}</td>
                                    <td class="p-3">{{ \Carbon\Carbon::parse($transaction->borrowed_at)->format('d/m/Y') }}</td>
                                    <td class="p-3">{{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}</td>
                                    <td class="p-3">{{ $transaction->returned_at ? \Carbon\Carbon::parse($transaction->returned_at)->format('d/m/Y') : '-' }}</td>
                                    <td class="p-3">
                                        @if($transaction->status === 'returned')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-sm">Dikembalikan</span>
                                        @elseif($transaction->status === 'rejected')
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-sm">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-4 text-gray-500">Belum ada riwayat peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($activeTab === 'missions')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($activeMissions as $mission)
                    <div class="card border-l-4 border-primary">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-lg">{{ $mission->title }}</h3>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full font-bold">
                                +{{ $mission->reward_points }} pts
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $mission->description }}</p>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm">
                                <strong>Kondisi:</strong> {{ ucfirst(str_replace('_', ' ', $mission->condition_type)) }} = {{ $mission->condition_value }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-8">
                        Tidak ada misi aktif saat ini.
                    </div>
                @endforelse
            </div>

        @elseif($activeTab === 'leaderboard')
            <div class="card">
                <h3 class="font-bold mb-4">🏆 Top 10 Siswa</h3>
                <div class="space-y-3">
                    @forelse($leaderboard as $index => $item)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $item['member']->id === $member->id ? 'bg-primary bg-opacity-10 border-2 border-primary' : 'bg-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="font-medium">{{ $item['member']->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $item['member']->class }}</div>
                                </div>
                            </div>
                            <div class="text-xl font-bold text-primary">
                                {{ $item['total_points'] }} pts
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">
                            Belum ada data poin.
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    @else
        <div class="card text-center text-gray-500 py-8">
            Silakan pilih siswa untuk melihat portal.
        </div>
    @endif
</div>
