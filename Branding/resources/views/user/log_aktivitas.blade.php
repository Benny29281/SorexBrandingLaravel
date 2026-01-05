<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - SOREX</title>
    
    {{-- Library --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        .border-sorex { border-color: #d71920; }
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ open: false, showProfileModal: false }" class="bg-gray-50 font-sans min-h-screen flex flex-col">

    {{-- =========================================
         NAVBAR (SAMA PERSIS DENGAN PAGE LAIN)
         ========================================= --}}
    <header class="w-full py-4 px-6 sm:px-10 flex justify-between items-center text-white z-50 relative bg-sorex shadow-md">
        <div class="flex items-center z-50">
            <a href="{{ route('user.home') }}">
                <img src="{{ asset('img/logo5.png') }}" alt="SOREX Logo" class="h-10 md:h-12 w-auto drop-shadow-md" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <h1 class="text-3xl font-extrabold tracking-widest italic drop-shadow-md hidden">SOREX</h1>
            </a>
        </div>

        {{-- Hamburger --}}
        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-2xl"></i>
            <i x-show="open" x-cloak class="fas fa-times text-2xl"></i>
        </button>

        {{-- Menu Desktop --}}
        <nav class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider items-center">
            <a href="{{ route('user.home') }}" class="hover:text-red-200 transition flex items-center"><i class="fas fa-home mr-2"></i> Home</a>
            <a href="{{ route('user.log') }}" class="hover:text-red-200 transition relative group flex items-center"><i class="fas fa-history mr-2"></i> Log Aktivitas</a>
            <a href="{{ route('user.download.page') }}" class="text-white font-bold text-sm hover:underline flex items-center uppercase tracking-wider"><i class="fas fa-file-download mr-2"></i> Download Data</a>
            <div class="border-l border-red-300 h-6 mx-2"></div>
            
            {{-- PROFILE BULAT --}}
            <div class="relative group cursor-pointer mr-2" @click="showProfileModal = true">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-lg bg-white transform group-hover:scale-110 transition duration-300">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" alt="Profile" class="w-full h-full object-cover">
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-white text-sorex px-5 py-2 rounded-full font-bold hover:bg-gray-100 transition shadow-lg transform hover:scale-105 text-xs">Log Out <i class="fas fa-sign-out-alt ml-1"></i></button>
            </form>
        </nav>

        {{-- Menu Mobile --}}
        <div x-show="open" x-transition x-cloak class="absolute top-0 left-0 w-full bg-red-900/95 backdrop-blur-md shadow-2xl md:hidden pt-24 pb-8 px-6 flex flex-col space-y-4 text-center z-40 border-b border-red-700">
            <div class="flex flex-col items-center mb-4 border-b border-red-800 pb-4">
                <div class="relative w-16 h-16 rounded-full overflow-hidden border-2 border-white mb-2 bg-white shadow-xl">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" class="w-full h-full object-cover">
                </div>
                <h3 class="text-white font-bold text-lg tracking-wide">{{ Auth::user()->name }}</h3>
                <p class="text-red-200 text-xs">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('user.home') }}" class="block py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition"><i class="fas fa-home mr-2"></i> Home</a>
            <a href="{{ route('user.log') }}" class="block py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition"><i class="fas fa-history mr-2"></i> Log Aktivitas</a>
            <a href="{{ route('user.download.page') }}" class="block w-full py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition text-white uppercase"><i class="fas fa-file-download mr-2"></i> Download Data</a>
            <div class="border-t border-white/20 pt-4 mt-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-white text-sorex rounded-xl font-bold shadow-md active:scale-95 transition">Log Out</button>
                </form>
            </div>
        </div>
    </header>

    {{-- =========================================
         MAIN CONTENT
         ========================================= --}}
    <main class="flex-grow container mx-auto px-4 py-8 max-w-7xl">
        
        {{-- Header Halaman --}}
        <div class="mb-6 flex flex-col md:flex-row justify-between items-center border-b border-gray-300 pb-4">
            <div class="text-center md:text-left mb-4 md:mb-0">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center justify-center md:justify-start">
                    <i class="fas fa-history text-sorex mr-3"></i> Riwayat Aktivitas
                </h1>
                <p class="text-gray-500 text-sm mt-1">Daftar riwayat input dan revisi data yang Anda lakukan.</p>
            </div>
            <a href="{{ route('user.home') }}" class="text-gray-500 hover:text-red-600 font-bold bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm transition text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- A. TAMPILAN DESKTOP (TABEL) --}}
        <div class="hidden md:block bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <span class="font-bold text-gray-700 text-sm uppercase tracking-wide">Data Log (Desktop View)</span>
                <span class="text-xs text-gray-500 bg-white px-3 py-1 rounded border shadow-sm">Total: {{ $logs->count() }} Data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-white uppercase bg-sorex">
                        <tr>
                            <th class="px-6 py-3 whitespace-nowrap">Tanggal Submit</th>
                            <th class="px-6 py-3">ID Request</th>
                            <th class="px-6 py-3">Nama Toko</th>
                            <th class="px-6 py-3">Jenis Tools</th>
                            <th class="px-6 py-3">Ukuran</th>
                            <th class="px-6 py-3 text-center">Qty</th>
                            <th class="px-6 py-3">Keterangan</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-red-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-800">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-sorex">{{ $log->request_id }}</td>
                                <td class="px-6 py-4 uppercase font-semibold text-gray-700">{{ $log->nama_toko }}</td>
                                <td class="px-6 py-4">{{ $log->jenis_tools_branding }}</td>
                                <td class="px-6 py-4">{{ $log->ukuran_tools_branding ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-bold">{{ $log->qty_tools }}</td>
                                <td class="px-6 py-4 italic text-gray-500 max-w-xs truncate" title="{{ $log->keterangan_tambahan }}">{{Str::limit($log->keterangan_tambahan ?? '-', 30)}}</td>
                                <td class="px-6 py-4">
                                    @if($log->action_type == 'INPUT BARU')
                                        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full border border-green-200 shadow-sm">INPUT</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full border border-yellow-200 shadow-sm">REVISI</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-16 text-gray-400 italic">Belum ada aktivitas tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- B. TAMPILAN MOBILE (CARD LIST) --}}
        <div class="md:hidden space-y-4">
            @forelse($logs as $log)
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-5 relative overflow-hidden">
                    {{-- Status Badge di Pojok Kanan Atas --}}
                    <div class="absolute top-0 right-0">
                        @if($log->action_type == 'INPUT BARU')
                            <div class="bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1 rounded-bl-xl border-l border-b border-green-200">INPUT BARU</div>
                        @else
                            <div class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1 rounded-bl-xl border-l border-b border-yellow-200">REVISI</div>
                        @endif
                    </div>

                    {{-- Baris 1: ID & Tanggal --}}
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-sorex font-extrabold text-lg">{{ $log->request_id }}</span>
                    </div>
                    <div class="text-xs text-gray-400 mb-3 flex items-center">
                        <i class="far fa-clock mr-1"></i> {{ $log->created_at->format('d M Y, H:i') }}
                    </div>

                    {{-- Baris 2: Nama Toko --}}
                    <div class="mb-3">
                        <p class="text-xs text-gray-400 uppercase font-bold">Nama Toko</p>
                        <p class="text-gray-800 font-bold text-base uppercase">{{ $log->nama_toko }}</p>
                    </div>

                    {{-- Baris 3: Detail Grid --}}
                    <div class="grid grid-cols-2 gap-2 text-sm border-t border-gray-100 pt-3">
                        <div>
                            <p class="text-[10px] text-gray-400">Tools</p>
                            <p class="font-medium text-gray-700">{{ $log->jenis_tools_branding }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400">Qty</p>
                            <p class="font-medium text-gray-700">{{ $log->qty_tools }} Pcs</p>
                        </div>
                    </div>
                    
                    {{-- Baris 4: Keterangan (Jika Ada) --}}
                    @if(!empty($log->keterangan_tambahan) && $log->keterangan_tambahan != '-')
                        <div class="mt-3 bg-gray-50 p-2 rounded text-xs text-gray-500 italic border border-gray-100">
                            "{{ $log->keterangan_tambahan }}"
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                    <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                    <p class="text-gray-400 italic text-sm">Belum ada aktivitas.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="text-center mt-8 text-gray-400 text-xs pb-8">
            &copy; 2025 SOREX Branding System. All Rights Reserved.
        </div>

    </main>

    {{-- MODAL PROFILE POPUP --}}
    <div x-show="showProfileModal" style="display: none;" 
         class="fixed inset-0 z-[110] flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90">
        
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 relative flex flex-col items-center text-center" @click.away="showProfileModal = false">
            <button @click="showProfileModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-red-100 shadow-xl mb-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true&size=128" alt="Profile" class="w-full h-full object-cover">
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h2>
            <div class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-bold mb-4 uppercase tracking-wider">{{ Auth::user()->regional ?? 'User' }}</div>
            <div class="w-full border-t border-gray-100 pt-4">
                <p class="text-gray-500 text-sm mb-1">Alamat Email:</p>
                <p class="text-gray-800 font-medium break-all">{{ Auth::user()->email }}</p>
            </div>
            <button @click="showProfileModal = false" class="mt-6 w-full py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">Tutup</button>
        </div>
    </div>

    @include('components.keep-alive')
</body>
</html>