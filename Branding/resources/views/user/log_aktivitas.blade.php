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
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bar2.png') }}">
    
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

<body x-data="{ 
    open: false, 
    showProfileModal: false, 
    detailModal: false, 
    activeLog: {} 
}" class="bg-gray-50 font-sans min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <header class="w-full py-4 px-6 sm:px-10 flex justify-between items-center text-white z-50 relative bg-sorex shadow-md">
        <div class="flex items-center z-50">
            <a href="{{ route('user.home') }}">
                <img src="{{ asset('img/logo5.png') }}" alt="SOREX Logo" class="h-10 md:h-12 w-auto drop-shadow-md">
            </a>
        </div>

        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-2xl"></i>
            <i x-show="open" x-cloak class="fas fa-times text-2xl"></i>
        </button>

        <nav class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider items-center">
            <a href="{{ route('user.home') }}" class="hover:text-red-200 transition flex items-center"><i class="fas fa-home mr-2"></i> Home</a>
            <a href="{{ route('user.log') }}" class="hover:text-red-200 transition flex items-center"><i class="fas fa-history mr-2"></i> Log Aktivitas</a>
            <div class="border-l border-red-300 h-6 mx-2"></div>
            
            <div class="relative group cursor-pointer mr-2" @click="showProfileModal = true">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-lg bg-white transform group-hover:scale-110 transition duration-300">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" alt="Profile" class="w-full h-full object-cover">
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-white text-sorex px-5 py-2 rounded-full font-bold hover:bg-gray-100 transition shadow-lg text-xs uppercase">Log Out</button>
            </form>
        </nav>

        {{-- Menu Mobile --}}
        <div x-show="open" x-transition x-cloak class="absolute top-0 left-0 w-full bg-red-900/95 backdrop-blur-md shadow-2xl md:hidden pt-24 pb-8 px-6 flex flex-col space-y-4 text-center z-40 border-b border-red-700">
            <a href="{{ route('user.home') }}" class="block py-3 hover:bg-white/10 rounded-xl font-bold transition">Home</a>
            <a href="{{ route('user.log') }}" class="block py-3 hover:bg-white/10 rounded-xl font-bold transition">Log Aktivitas</a>
            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="w-full py-3 bg-white text-sorex rounded-xl font-bold shadow-md">Log Out</button>
            </form>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow container mx-auto px-4 py-2 sm:py-4 max-w-7xl">
        
        {{-- HEADER RIWAYAT: Rapat ke Atas --}}
        <div class="mb-3 flex flex-col md:flex-row justify-between items-center border-b border-gray-200 pb-2">
            <div class="flex items-center w-full md:w-auto">
                <a href="{{ route('user.home') }}" class="mr-3 text-gray-600 hover:text-sorex transition text-xl">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="text-left">
                    <h1 class="text-xl font-bold text-gray-800 leading-tight">Riwayat Aktivitas</h1>
                    <p class="text-[10px] text-gray-500 font-medium">Klik data untuk detail lengkap.</p>
                </div>
            </div>
            <div class="hidden md:block">
                <span class="text-[10px] bg-gray-100 px-3 py-1 rounded-full border border-gray-300 text-gray-500 uppercase font-bold tracking-tighter">
                    Total: {{ $logs->count() }} Data
                </span>
            </div>
        </div>

        {{-- A. TAMPILAN TABLET & DESKTOP --}}
        <div class="hidden sm:block bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-gray-600">
                    <thead class="text-[10px] text-white uppercase bg-sorex tracking-wider">
                        <tr>
                            <th class="px-4 py-3 whitespace-nowrap">Tanggal Submit</th>
                            <th class="px-4 py-3">ID & Status</th>
                            <th class="px-4 py-3">Nama Toko & Sales</th>
                            <th class="px-4 py-3">Brand & Produk</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-red-50 transition duration-150">
                                <td class="px-4 py-4 whitespace-nowrap border-r border-gray-50">
                                    <div class="font-bold text-gray-800 uppercase">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-gray-400 font-medium">{{ $log->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-4 py-4 border-r border-gray-50">
                                    <div class="font-bold text-sorex">{{ $log->request_id }}</div>
                                    <span class="text-[9px] px-1.5 py-0.5 rounded {{ $log->action_type == 'INPUT BARU' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} font-extrabold uppercase">{{ $log->action_type }}</span>
                                </td>
                                <td class="px-4 py-4 border-r border-gray-50">
                                    <div class="font-bold text-gray-800 uppercase text-[11px] leading-tight">{{ $log->nama_toko }}</div>
                                    <div class="text-[10px] text-blue-600 font-semibold italic">Sales: {{ $log->nama_sales ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4 border-r border-gray-50">
                                    <div class="font-extrabold text-sorex uppercase text-[10px] mb-0.5">{{ $log->brand ?? 'SOREX' }}</div>
                                    <div class="text-[10px] text-gray-600 font-medium uppercase leading-tight">{{ $log->jenis_tools_branding }}</div>
                                </td>
                                <td class="px-4 py-4 text-center font-extrabold text-gray-800">{{ $log->qty_tools }}</td>
                                <td class="px-4 py-4 text-center">
                                    <button @click="detailModal = true; activeLog = {
                                        id: '{{ $log->request_id }}',
                                        toko: '{{ $log->nama_toko }}',
                                        sales: '{{ $log->nama_sales }}',
                                        brand: '{{ $log->brand  }}',
                                        date: '{{ $log->created_at->format('d M Y, H:i') }}',
                                        tools: '{{ $log->jenis_tools_branding }}',
                                        qty: '{{ $log->qty_tools }}',
                                        status: '{{ $log->action_type }}'
                                    }" class="text-blue-500 hover:text-blue-700 transition">
                                        <i class="fas fa-eye text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-10 text-gray-400 italic font-medium">Belum ada data tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- B. TAMPILAN MOBILE (HP) --}}
        <div class="sm:hidden flex flex-col space-y-2">
            @forelse($logs as $log)
                <div @click="detailModal = true; activeLog = {
                    id: '{{ $log->request_id }}',
                    toko: '{{ $log->nama_toko }}',
                    sales: '{{ $log->nama_sales ?? '-' }}',
                    brand: '{{ $log->brand ?? 'SOREX' }}',
                    date: '{{ $log->created_at->format('d M Y, H:i') }}',
                    tools: '{{ $log->jenis_tools_branding }}',
                    qty: '{{ $log->qty_tools }}',
                    status: '{{ $log->action_type }}'
                }" class="bg-white p-3 rounded-xl shadow-sm border border-gray-200 active:scale-95 transition flex justify-between items-center">
                    
                    <div class="flex-1 pr-3">
                        <div class="flex items-center space-x-2 mb-1.5">
                            <span class="text-[9px] font-bold text-sorex bg-red-50 px-2 py-0.5 rounded border border-red-100 uppercase">{{ $log->request_id }}</span>
                            <span class="text-[9px] font-extrabold {{ $log->action_type == 'INPUT BARU' ? 'text-green-600' : 'text-yellow-600' }} uppercase">{{ $log->action_type }}</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-[13px] uppercase truncate w-48">{{ $log->nama_toko }}</h3>
                        <div class="flex flex-col mt-1 space-y-0.5">
                            <div class="flex items-center text-[10px] text-gray-500">
                                <span class="bg-red-600 text-white px-1 rounded text-[8px] font-bold mr-1">{{ $log->brand ?? 'SOREX' }}</span>
                                <span class="truncate font-semibold italic text-blue-600">Sales: {{ $log->nama_sales ?? '-' }}</span>
                            </div>
                            <p class="text-[10px] text-gray-400 uppercase font-medium truncate">{{ $log->jenis_tools_branding }} ({{ $log->qty_tools }} Pcs)</p>
                        </div>
                    </div>

                    <div class="text-right flex flex-col items-end min-w-[90px]">
                        <span class="text-[11px] font-bold text-gray-700">{{ $log->created_at->format('d M Y') }}</span>
                        <span class="text-[9px] text-gray-400 font-medium">{{ $log->created_at->format('H:i') }} WIB</span>
                        <i class="fas fa-chevron-right text-gray-300 text-[10px] mt-2"></i>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-widest italic">Belum ada data aktivitas</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-6 text-gray-400 text-[9px] uppercase font-bold tracking-widest pb-10">
            &copy; 2026 SOREX Branding System.
        </div>

    </main>

    {{-- MODAL DETAIL (Full Responsive) --}}
    <div x-show="detailModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all" @click.away="detailModal = false">
            <div class="bg-sorex px-6 py-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-base uppercase tracking-wider">Detail Aktivitas</h3>
                <button @click="detailModal = false" class="hover:text-red-200 transition"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6">
                <div class="mb-4 border-b border-gray-100 pb-4 text-center">
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">ID Request</p>
                    <p class="text-2xl font-black text-sorex leading-none" x-text="activeLog.id"></p>
                    <span class="inline-block mt-2 px-3 py-0.5 rounded-full text-[9px] font-black uppercase border"
                          :class="activeLog.status == 'INPUT BARU' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-yellow-100 text-yellow-700 border-yellow-200'"
                          x-text="activeLog.status"></span>
                </div>
                <div class="space-y-3">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-[9px] text-gray-400 uppercase font-extrabold mb-1 tracking-tighter">Informasi Toko & Sales</p>
                        <p class="text-xs font-bold text-gray-800 uppercase" x-text="activeLog.toko"></p>
                        <p class="text-[11px] font-semibold text-blue-600 italic">Sales: <span x-text="activeLog.sales"></span></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-[9px] text-gray-400 uppercase font-extrabold mb-1 tracking-tighter">Brand</p>
                            <p class="text-xs font-bold text-sorex uppercase" x-text="activeLog.brand"></p>
                        </div>
                        <div>
                            <p class="text-[9px] text-gray-400 uppercase font-extrabold mb-1 tracking-tighter">Qty</p>
                            <p class="text-xs font-bold text-gray-800 uppercase"><span x-text="activeLog.qty"></span> Pcs</p>
                        </div>
                        <div class="col-span-2 mt-1">
                            <p class="text-[9px] text-gray-400 uppercase font-extrabold mb-1 tracking-tighter">Jenis Produk</p>
                            <p class="text-xs font-bold text-gray-800 uppercase leading-tight" x-text="activeLog.tools"></p>
                        </div>
                    </div>
                    <div class="text-center pt-2">
                        <p class="text-[9px] text-gray-400 font-bold uppercase" x-text="activeLog.date"></p>
                    </div>
                </div>
                <button @click="detailModal = false" class="w-full mt-6 bg-gray-100 text-gray-600 font-black py-3 rounded-xl hover:bg-gray-200 transition text-[11px] uppercase tracking-widest">Tutup</button>
            </div>
        </div>
    </div>

</body>
</html>