<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Riwayat Peminjaman</h1>
        <p class="text-gray-500 mt-2 text-lg">Pantau status dan riwayat buku yang Anda pinjam.</p>
    </div>

    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
        <!-- Search -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model.live="search" type="text" placeholder="Cari buku atau penulis..." 
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Buku</th>
                        <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Tgl Pinjam</th>
                        <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Tgl Kembali</th>
                        <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Status</th>
                        <th class="pb-4 font-semibold text-gray-500 text-sm uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $transaction)
                        <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="py-4 pr-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-sm">
                                        {{ substr($transaction->book->title, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $transaction->book->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->book->author }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 text-sm text-gray-600 font-medium">{{ $transaction->borrowed_at->format('d M Y') }}</td>
                            <td class="py-4 text-sm text-gray-600 font-medium">{{ $transaction->due_date->format('d M Y') }}</td>
                            <td class="py-4 text-sm text-gray-600 font-medium">
                                {{ $transaction->returned_at ? $transaction->returned_at->format('d M Y') : '-' }}
                            </td>
                            <td class="py-4">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'label' => 'Menunggu'],
                                        'approved' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'label' => 'Dipinjam'],
                                        'returned' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'label' => 'Dikembalikan'],
                                        'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                                        'lost' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Hilang'],
                                    ];
                                    
                                    $config = $statusConfig[$transaction->status] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'label' => ucfirst($transaction->status)];

                                    if ($transaction->status == 'approved' && $transaction->due_date < now()) {
                                        $config = ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'label' => 'Terlambat'];
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                            <td class="py-4 text-sm">
                                @if($transaction->status == 'rejected')
                                    <span class="text-red-600 bg-red-50 px-2 py-1 rounded-md text-xs">{{ $transaction->rejection_reason }}</span>
                                @elseif($transaction->status == 'returned' && $transaction->fine_amount > 0)
                                    <span class="text-red-600 font-medium">Denda: Rp {{ number_format($transaction->fine_amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-50 rounded-full p-4 mb-3">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada riwayat peminjaman.</p>
                                    <p class="text-gray-400 text-sm mt-1">Buku yang Anda pinjam akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
