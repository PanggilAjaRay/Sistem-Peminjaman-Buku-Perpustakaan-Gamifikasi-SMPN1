<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Riwayat Denda</h1>
        <p class="text-gray-500 mt-2 text-lg">Daftar denda dan penalti yang terkait dengan peminjaman Anda</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Unpaid Fines -->
        <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-2xl shadow-sm border border-red-200 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center mb-2">
                <div class="p-2 bg-red-100 rounded-lg mr-3">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-red-700">Denda Belum Dibayar</h3>
            </div>
            <p class="text-3xl font-bold text-red-700">Rp {{ number_format($totalMoneyFines, 0, ',', '.') }}</p>
            <p class="text-xs text-red-600 mt-1">{{ $unpaidCount }} denda</p>
        </div>

        <!-- Total Points Deducted -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-sm border border-purple-200 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center mb-2">
                <div class="p-2 bg-purple-100 rounded-lg mr-3">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-purple-700">Total Point Dikurangi</h3>
            </div>
            <p class="text-3xl font-bold text-purple-700">{{ number_format($totalPointsDeducted) }}</p>
            <p class="text-xs text-purple-600 mt-1">Akumulasi pengurangan point</p>
        </div>

        <!-- Total Fines -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-sm border border-blue-200 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center mb-2">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-blue-700">Total Riwayat Denda</h3>
            </div>
            <p class="text-3xl font-bold text-blue-700">{{ $fines->count() }}</p>
            <p class="text-xs text-blue-600 mt-1">Semua catatan denda</p>
        </div>
    </div>

    <!-- Fines Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Daftar Denda</h2>
        
        @if($fines->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Tanggal</th>
                            <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Buku</th>
                            <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Jenis Denda</th>
                            <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Detail</th>
                            <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Biaya</th>
                            <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Point</th>
                            <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($fines as $fine)
                            <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                                <!-- Date -->
                                <td class="py-4 text-sm text-gray-600">
                                    {{ $fine->created_at->format('d M Y') }}
                                </td>
                                
                                <!-- Book -->
                                <td class="py-4">
                                    <div class="font-medium text-gray-900 text-sm">{{ $fine->transaction->book->title ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $fine->transaction->book->author ?? '-' }}</div>
                                </td>
                                
                                <!-- Penalty Type -->
                                <td class="py-4">
                                    @php
                                        $typeConfig = [
                                            'late_return' => ['label' => 'Keterlambatan', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-200'],
                                            'damaged_book' => ['label' => 'Buku Rusak', 'bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200'],
                                            'lost_book' => ['label' => 'Buku Hilang', 'bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                                        ];
                                        $config = $typeConfig[$fine->penalty_type] ?? ['label' => 'Lainnya', 'bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                
                                <!-- Description -->
                                <td class="py-4 text-sm text-gray-600 max-w-xs">
                                    <p class="line-clamp-2">{{ $fine->description ?? '-' }}</p>
                                </td>
                                
                                <!-- Money Amount -->
                                <td class="py-4">
                                    @if($fine->amount > 0)
                                        <span class="text-sm font-semibold text-red-600">
                                            Rp {{ number_format($fine->amount, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                
                                <!-- Points Deducted -->
                                <td class="py-4">
                                    @if($fine->points_deducted > 0)
                                        <span class="text-sm font-semibold text-purple-600">
                                            -{{ number_format($fine->points_deducted) }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                
                                <!-- Status -->
                                <td class="py-4">
                                    @if($fine->status === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-500 font-medium text-lg">Tidak ada riwayat denda</p>
                <p class="text-gray-400 text-sm mt-2">Anda belum memiliki catatan denda apapun.</p>
            </div>
        @endif
    </div>
</div>
