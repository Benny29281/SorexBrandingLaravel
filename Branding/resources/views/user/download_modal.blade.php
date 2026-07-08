{{-- =======================================================
         MODAL POPUP DOWNLOAD (DENGAN FILTER REGIONAL)
         ======================================================= --}}
    <div x-show="showModalDownload" style="display: none;" 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative transform transition-all scale-100" @click.away="showModalDownload = false">
            
            {{-- Header Modal --}}
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-file-excel text-green-600 text-lg"></i>
                    </div>
                    Download Data
                </h2>
                <button @click="showModalDownload = false" class="text-gray-400 hover:text-red-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Form Download --}}
            <form action="{{ route('user.download.status') }}" method="GET">
                <div class="space-y-4">
                    
                    {{-- 1. INPUT PILIHAN AREA (INI YANG BARU) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Area</label>
                        <div class="relative">
                            <select name="area" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 appearance-none focus:ring-2 focus:ring-green-500 focus:outline-none transition font-medium text-gray-700 cursor-pointer">
                                
                                <option value="ALL">Semua Area Saya</option>

                                {{-- Pilihan Dinamis Berdasarkan Regional User --}}
                                @if(strtoupper(Auth::user()->regional) == 'reg1' || strtoupper(Auth::user()->regional) == 'regional 1')
                                    <option value="JT">JT</option>
                                    <option value="DK">DK</option>
                                    <option value="LP">LP</option>
                                @else
                                    <option value="JB">JB</option>
                                    <option value="JR">JR</option>
                                @endif

                            </select>
                            {{-- Panah Dropdown --}}
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    {{-- 2. INPUT TANGGAL --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="start_date" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="end_date" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:outline-none transition">
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-8 flex gap-3">
                    <button type="button" @click="showModalDownload = false" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 shadow-lg shadow-green-200 transition flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i> Download
                    </button>
                </div>
            </form>
        </div>
    </div>