<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 ml-24">
    <div class="p-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pengaturan Denda</h1>
            <p class="mt-2 text-gray-600">Kelola pembayaran dan pengaturan denda perpustakaan</p>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('message') }}
            </div>
        @endif

        <!-- Tabs -->
        <div class="flex gap-2 mb-6 p-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
            <button wire:click="$set('activeTab', 'payments')" 
                    class="px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeTab === 'payments' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Pembayaran Denda
                </div>
            </button>
            <button wire:click="$set('activeTab', 'settings')" 
                    class="px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 whitespace-nowrap {{ $activeTab === 'settings' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan Denda
                </div>
            </button>
        </div>

        <!-- Tab Content -->
        @if($activeTab === 'payments')
            <!-- Payment Tab Content -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Daftar Denda Belum Dibayar</h2>
                    <span class="px-3 py-1 bg-red-50 text-red-700 rounded-lg text-sm font-semibold">
                        {{ $unpaidFines->count() }} Denda
                    </span>
                </div>

                @if($unpaidFines->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Tanggal</th>
                                    <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Siswa</th>
                                    <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Buku</th>
                                    <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Jenis</th>
                                    <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Jumlah</th>
                                    <th class="pb-4 font-semibold text-gray-700 text-sm uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($unpaidFines as $fine)
                                    <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                                        <!-- Date -->
                                        <td class="py-4 text-sm text-gray-600">
                                            {{ $fine->created_at->format('d M Y') }}
                                        </td>
                                        
                                        <!-- Student -->
                                        <td class="py-4">
                                            <div class="font-medium text-gray-900 text-sm">{{ $fine->transaction->member->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">NIS: {{ $fine->transaction->member->nis ?? '-' }}</div>
                                        </td>
                                        
                                        <!-- Book -->
                                        <td class="py-4">
                                            <div class="font-medium text-gray-900 text-sm">{{ $fine->transaction->book->title ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $fine->transaction->book->author ?? '-' }}</div>
                                        </td>
                                        
                                        <!-- Type -->
                                        <td class="py-4">
                                            @php
                                                $typeConfig = [
                                                    'late_return' => ['label' => 'Keterlambatan', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700'],
                                                    'damaged_book' => ['label' => 'Buku Rusak', 'bg' => 'bg-orange-50', 'text' => 'text-orange-700'],
                                                    'lost_book' => ['label' => 'Buku Hilang', 'bg' => 'bg-red-50', 'text' => 'text-red-700'],
                                                ];
                                                $config = $typeConfig[$fine->penalty_type] ?? ['label' => 'Lainnya', 'bg' => 'bg-gray-50', 'text' => 'text-gray-700'];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                                {{ $config['label'] }}
                                            </span>
                                        </td>
                                        
                                        <!-- Amount -->
                                        <td class="py-4">
                                            <span class="text-lg font-bold text-red-600">
                                                Rp {{ number_format($fine->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        
                                        <!-- Action -->
                                        <td class="py-4">
                                            <button wire:click="markAsPaid({{ $fine->id }})" 
                                                    onclick="return confirm('Konfirmasi bahwa denda ini sudah dibayar?')"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Tandai Lunas
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium text-lg">Tidak Ada Denda Belum Dibayar</p>
                        <p class="text-gray-400 text-sm mt-2">Semua denda biaya sudah lunas!</p>
                    </div>
                @endif
            </div>
        @else
            <!-- Settings Tab Content (existing content) -->
            <form wire:submit.prevent="saveSettings">
                <div class="grid gap-6">
                    <!-- Late Return Penalty -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                            <div class="flex items-center text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-lg font-semibold">Denda Keterlambatan</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Denda</label>
                                    <select wire:model="lateReturn.value_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="points">Pengurangan Point</option>
                                        <option value="money">Biaya (Rupiah)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nilai per Hari 
                                        @if($lateReturn['value_type'] ?? 'points' == 'points')
                                            (Point)
                                        @else
                                            (Rupiah)
                                        @endif
                                    </label>
                                    <input type="number" wire:model="lateReturn.value_per_day" 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           step="0.01" min="0" required>
                                    @error('lateReturn.value_per_day') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <input type="checkbox" wire:model="lateReturn.is_active" 
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label class="ml-2 text-sm font-medium text-gray-700">Aktifkan denda keterlambatan</label>
                            </div>
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <strong>Info:</strong> Denda akan dikenakan per hari keterlambatan pengembalian buku.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Damaged Book Penalty -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                            <div class="flex items-center text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <h3 class="text-lg font-semibold">Denda Buku Rusak</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Denda</label>
                                    <select wire:model="damagedBook.value_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="points">Pengurangan Point</option>
                                        <option value="money">Biaya (Rupiah)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nilai Denda 
                                        @if($damagedBook['value_type'] ?? 'money' == 'points')
                                            (Point)
                                        @else
                                            (Rupiah)
                                        @endif
                                    </label>
                                    <input type="number" wire:model="damagedBook.fixed_value" 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           step="0.01" min="0" required>
                                    @error('damagedBook.fixed_value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <input type="checkbox" wire:model="damagedBook.is_active" 
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label class="ml-2 text-sm font-medium text-gray-700">Aktifkan denda buku rusak</label>
                            </div>
                            <div class="mt-4 p-4 bg-orange-50 rounded-lg">
                                <p class="text-sm text-orange-800">
                                    <strong>Info:</strong> Denda tetap yang dikenakan ketika buku dikembalikan dalam kondisi rusak.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Lost Book Penalty -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-500 to-pink-500 px-6 py-4">
                            <div class="flex items-center text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <h3 class="text-lg font-semibold">Denda Buku Hilang</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Denda</label>
                                    <select wire:model="lostBook.value_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="points">Pengurangan Point</option>
                                        <option value="money">Biaya (Rupiah)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nilai Denda 
                                        @if($lostBook['value_type'] ?? 'money' == 'points')
                                            (Point)
                                        @else
                                            (Rupiah)
                                        @endif
                                    </label>
                                    <input type="number" wire:model="lostBook.fixed_value" 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           step="0.01" min="0" required>
                                    @error('lostBook.fixed_value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mt-4 flex items-center">
                                <input type="checkbox" wire:model="lostBook.is_active" 
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label class="ml-2 text-sm font-medium text-gray-700">Aktifkan denda buku hilang</label>
                            </div>
                            <div class="mt-4 p-4 bg-red-50 rounded-lg">
                                <p class="text-sm text-red-800">
                                    <strong>Info:</strong> Denda tetap yang dikenakan ketika buku dinyatakan hilang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg shadow-blue-500/30 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
