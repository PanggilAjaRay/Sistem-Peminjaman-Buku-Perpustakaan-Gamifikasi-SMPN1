<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Sirkulasi Buku</h1>
        <p class="text-gray-500 mt-2 text-lg">Kelola peminjaman dan pengembalian buku perpustakaan.</p>
    </div>

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

    <!-- Tabs -->
    <div class="flex gap-2 mb-6 p-1 bg-gray-100 rounded-xl overflow-x-auto">
        <button wire:click="$set('activeTab', 'borrow')" 
                class="px-6 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeTab === 'borrow' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
            Peminjaman
        </button>
        <button wire:click="$set('activeTab', 'pending')" 
                class="px-6 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeTab === 'pending' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
            Menunggu Approval
        </button>
        <button wire:click="$set('activeTab', 'active')" 
                class="px-6 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeTab === 'active' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
            Peminjaman Aktif
        </button>
        <button wire:click="$set('activeTab', 'late')" 
                class="px-6 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeTab === 'late' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
            Peminjaman Terlambat
        </button>
        <button wire:click="$set('activeTab', 'history')" 
                class="px-6 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeTab === 'history' ? 'bg-white text-blue-700 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
            Riwayat
        </button>
    </div>

    @if($activeTab === 'borrow')
        <!-- Borrow Form -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Form Peminjaman Buku</h2>
            <form wire:submit.prevent="borrow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Anggota</label>
                        <div class="relative">
                            @if($selectedMemberName)
                                <div class="flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900">
                                    <span class="flex-1">{{ $selectedMemberName }}</span>
                                    <button type="button" wire:click="clearMemberSearch" class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <input wire:model.live="memberSearch" 
                                       type="text" 
                                       placeholder="Cari nama atau NIS anggota..." 
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150">
                                
                                @if($showMemberDropdown && count($members) > 0)
                                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-auto">
                                        @foreach($members as $member)
                                            <button type="button" 
                                                    wire:click="selectMember({{ $member->id }}, '{{ $member->name }} ({{ $member->nis }})')" 
                                                    class="w-full text-left px-4 py-3 hover:bg-blue-50 transition-colors border-b border-gray-100 last:border-b-0">
                                                <div class="font-medium text-gray-900">{{ $member->name }}</div>
                                                <div class="text-sm text-gray-500">NIS: {{ $member->nis }}</div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                        @error('member_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Buku</label>
                        <div class="relative">
                            @if($selectedBookName)
                                <div class="flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900">
                                    <span class="flex-1">{{ $selectedBookName }}</span>
                                    <button type="button" wire:click="clearBookSearch" class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <input wire:model.live="bookSearch" 
                                       type="text" 
                                       placeholder="Cari judul buku..." 
                                       class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150">
                                
                                @if($showBookDropdown && count($books) > 0)
                                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-auto">
                                         @foreach($books as $book)
                                            <button type="button" 
                                                    wire:click="selectBook({{ $book->id }})" 
                                                    class="w-full text-left px-4 py-3 hover:bg-blue-50 transition-colors border-b border-gray-100 last:border-b-0">
                                                <div class="font-medium text-gray-900">{{ $book->title }}</div>
                                                <div class="text-sm text-gray-500">Stok: {{ $book->stock }} | {{ $book->author }}</div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                        @error('book_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pinjam</label>
                        <input wire:model="borrowed_at" type="date" class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150">
                        @error('borrowed_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Jatuh Tempo</label>
                        <input wire:model="due_date" type="date" class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150">
                        @error('due_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 shadow-sm">
                        Catat Peminjaman
                    </button>
                </div>
            </form>
        </div>
    @elseif($activeTab === 'pending')
        <!-- Pending Approval List -->
        <div class="mb-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live="search" type="text" placeholder="Cari nama atau NIS anggota..." 
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150 sm:text-sm">
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Daftar Peminjaman Menunggu Approval</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Anggota</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Buku</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Tgl Pinjam</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Jatuh Tempo</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($pendingTransactions as $transaction)
                            <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="py-4 pr-4">
                                    <div class="font-semibold text-gray-900">{{ $transaction->member->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->member->nis }}</div>
                                </td>
                                <td class="py-4 text-sm text-gray-600">{{ $transaction->book->title }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($transaction->borrowed_at)->format('d M Y') }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($transaction->due_date)->format('d M Y') }}</td>
                                <td class="py-4">
                                    <div class="flex gap-2">
                                        <button wire:click="approveRequest({{ $transaction->id }})" 
                                                class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Setujui
                                        </button>
                                        <button wire:click="openRejectionModal({{ $transaction->id }})" 
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-500">Tidak ada peminjaman menunggu approval.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($activeTab === 'active')
        <!-- Active Borrowings List -->
        <div class="mb-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live="search" type="text" placeholder="Cari nama atau NIS anggota..." 
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150 sm:text-sm">
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Daftar Peminjaman Aktif</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Anggota</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Buku</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Tgl Pinjam</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Jatuh Tempo</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Status</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($activeTransactions as $transaction)
                            <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="py-4 pr-4">
                                    <div class="font-semibold text-gray-900">{{ $transaction->member->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->member->nis }}</div>
                                </td>
                                <td class="py-4 text-sm text-gray-600">{{ $transaction->book->title }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($transaction->borrowed_at)->format('d M Y') }}</td>
                                <td class="py-4 text-sm text-gray-600">
                                    @php
                                        $isLate = \Carbon\Carbon::parse($transaction->due_date)->isPast();
                                    @endphp
                                    <span class="{{ $isLate ? 'text-red-600 font-bold' : '' }}">
                                        {{ \Carbon\Carbon::parse($transaction->due_date)->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="py-4">
                                    @if(\Carbon\Carbon::parse($transaction->due_date)->isPast())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">Terlambat</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Aktif</span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <div class="flex gap-2">
                                        <button wire:click="returnBook({{ $transaction->id }}, 'normal')" 
                                                class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors">
                                            Normal
                                        </button>
                                        <span class="text-gray-300">•</span>
                                        <button wire:click="returnBook({{ $transaction->id }}, 'damaged')" 
                                                class="text-sm font-medium text-red-600 hover:text-red-700 transition-colors">
                                            Rusak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500">Tidak ada peminjaman aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($activeTab === 'late')
        <!-- Late Borrowings List -->
        <div class="mb-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live="search" type="text" placeholder="Cari nama atau NIS anggota..." 
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150 sm:text-sm">
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Daftar Peminjaman Terlambat</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Anggota</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Buku</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Tgl Pinjam</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Jatuh Tempo</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Keterlambatan</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($lateTransactions as $transaction)
                            <tr class="group hover:bg-red-50/30 transition-colors duration-200">
                                <td class="py-4 pr-4">
                                    <div class="font-semibold text-gray-900">{{ $transaction->member->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->member->nis }}</div>
                                </td>
                                <td class="py-4 text-sm text-gray-600">{{ $transaction->book->title }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($transaction->borrowed_at)->format('d M Y') }}</td>
                                <td class="py-4">
                                    <span class="text-sm text-red-600 font-bold">
                                        {{ \Carbon\Carbon::parse($transaction->due_date)->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="py-4">
                                    @php
                                        $daysLate = \Carbon\Carbon::parse($transaction->due_date)->diffInDays(now());
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                        {{ $daysLate }} hari
                                    </span>
                                </td>
                                <td class="py-4">
                                    <div class="flex gap-2">
                                        <button wire:click="returnBook({{ $transaction->id }}, 'normal')" 
                                                class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors">
                                            Normal
                                        </button>
                                        <span class="text-gray-300">•</span>
                                        <button wire:click="returnBook({{ $transaction->id }}, 'damaged')" 
                                                class="text-sm font-medium text-red-600 hover:text-red-700 transition-colors">
                                            Rusak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-500">Tidak ada peminjaman terlambat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($activeTab === 'history')
        <!-- History List -->
        <div class="mb-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live="search" type="text" placeholder="Cari nama atau NIS anggota..." 
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150 sm:text-sm">
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Riwayat Peminjaman</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Anggota</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Buku</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Tgl Pinjam</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Jatuh Tempo</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Tgl Kembali</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Status</th>
                            <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($historyTransactions as $transaction)
                            <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                                <td class="py-4 pr-4">
                                    <div class="font-semibold text-gray-900">{{ $transaction->member->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->member->nis }}</div>
                                </td>
                                <td class="py-4 text-sm text-gray-600">{{ $transaction->book->title }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($transaction->borrowed_at)->format('d M Y') }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($transaction->due_date)->format('d M Y') }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ $transaction->returned_at ? \Carbon\Carbon::parse($transaction->returned_at)->format('d M Y') : '-' }}</td>
                                <td class="py-4">
                                    @if($transaction->status === 'returned')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Dikembalikan</span>
                                    @elseif($transaction->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">Ditolak</span>
                                    @endif
                                </td>
                                <td class="py-4 text-sm text-gray-600">
                                    @if($transaction->status === 'rejected' && $transaction->rejection_reason)
                                        <span class="text-red-600 bg-red-50 px-2 py-1 rounded-md text-xs">{{ $transaction->rejection_reason }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-500">Tidak ada riwayat peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Rejection Modal -->
    @if($showRejectionModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-in">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Peminjaman</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan</label>
                        <textarea wire:model="rejectionReason" 
                                  class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150" 
                                  rows="4" 
                                  placeholder="Masukkan alasan penolakan..."></textarea>
                        @error('rejectionReason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button wire:click="closeRejectionModal" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Batal
                        </button>
                        <button wire:click="rejectRequest" 
                                class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                            Tolak Peminjaman
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
