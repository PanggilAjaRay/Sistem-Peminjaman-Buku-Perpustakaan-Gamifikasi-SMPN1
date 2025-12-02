<div>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Manajemen Buku</h1>
            <p class="text-gray-500 mt-2">Kelola koleksi buku perpustakaan</p>
        </div>
        <button wire:click="create()" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Buku
        </button>
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

    <!-- Search -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live="search" type="text" placeholder="Cari buku berdasarkan judul atau penulis..." 
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150 sm:text-sm">
        </div>
    </div>

    <!-- Books Table -->
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Cover</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Penulis</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Penerbit</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Tahun</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($books as $book)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                            <td class="p-4">
                                @if($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="w-12 h-16 object-cover rounded-lg shadow-sm">
                                @else
                                    <div class="w-12 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-gray-900">{{ $book->title }}</p>
                            </td>
                            <td class="p-4 text-sm text-gray-600">{{ $book->author }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $book->publisher }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $book->year }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $book->stock > 0 ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                    {{ $book->stock }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-600">{{ $book->category ?? '-' }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <button wire:click="edit({{ $book->id }})" 
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-colors duration-150">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <button wire:click="delete({{ $book->id }})" 
                                        onclick="return confirm('Yakin ingin menghapus buku ini?')" 
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-colors duration-150">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Tidak ada data buku</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $books->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $bookId ? 'Edit Buku' : 'Tambah Buku' }}</h2>
                            <p class="text-sm text-gray-500 mt-1">{{ $bookId ? 'Perbarui informasi buku' : 'Tambahkan buku baru ke koleksi' }}</p>
                        </div>
                        <button wire:click="closeModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="store" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                        <input wire:model="title" type="text" 
                            class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150"
                            placeholder="Masukkan judul buku">
                        @error('title') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                            <input wire:model="author" type="text" 
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150"
                                placeholder="Nama penulis">
                            @error('author') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                            <input wire:model="publisher" type="text" 
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150"
                                placeholder="Nama penerbit">
                            @error('publisher') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                            <input wire:model="year" type="number" 
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150"
                                placeholder="2024">
                            @error('year') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                            <input wire:model="stock" type="number" 
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150"
                                placeholder="0">
                            @error('stock') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                        <input wire:model="cover_image" type="file" accept="image/*" 
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer cursor-pointer">
                        @error('cover_image') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                        
                        @if ($cover_image)
                            <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                <img src="{{ $cover_image->temporaryUrl() }}" class="w-32 h-40 object-cover rounded-lg shadow-md">
                            </div>
                        @elseif ($old_cover_image)
                            <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                                <p class="text-sm font-medium text-gray-700 mb-2">Cover saat ini:</p>
                                <img src="{{ asset('storage/' . $old_cover_image) }}" class="w-32 h-40 object-cover rounded-lg shadow-md">
                            </div>
                        @endif
                        
                        <div wire:loading wire:target="cover_image" class="mt-2 flex items-center text-sm text-blue-600">
                            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading...
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <input wire:model="category" type="text" 
                            class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition duration-150"
                            placeholder="Contoh: Fiksi, Non-Fiksi, Sains">
                        @error('category') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="closeModal()" 
                            class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500/20 transition-colors duration-150">
                            Batal
                        </button>
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-150 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $bookId ? 'Update Buku' : 'Simpan Buku' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
