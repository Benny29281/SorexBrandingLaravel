<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Tracking - SOREX</title>
    
    {{-- Library Sama Seperti Home --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; transform: translateY(-2px); }
        
        /* Style Stepper & Timeline Tracking */
        .step-active { @apply bg-red-600 text-white border-red-600; }
        .step-inactive { @apply bg-gray-100 text-gray-400 border-gray-300; }
        .v-line::before {
            content: '';
            position: absolute;
            top: 2.2rem;
            left: 1.2rem;
            height: 100%;
            width: 2px;
            background-color: #e5e7eb;
            z-index: 0;
        }
        .v-item:last-child .v-line::before { display: none; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>

{{-- Tambahkan x-data agar navbar mobile & profil berfungsi --}}
<body x-data="{ open: false, showProfileModal: false }" class="bg-gray-50 min-h-screen flex flex-col">

    {{-- =========================================
         NAVBAR (SAMA PERSIS DENGAN HOME)
         ========================================= --}}
    <header class="w-full py-4 px-6 sm:px-10 flex justify-between items-center text-white z-50 relative bg-sorex shadow-md">
        <div class="flex items-center z-50">
            {{-- Klik Logo kembali ke Home --}}
            <a href="{{ route('user.home') }}">
                <img src="{{ asset('img/logo5.png') }}" alt="SOREX Logo" class="h-10 md:h-12 w-auto drop-shadow-md" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <h1 class="text-3xl font-extrabold tracking-widest italic drop-shadow-md hidden">SOREX</h1>
            </a>
        </div>

        {{-- Hamburger Mobile --}}
        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-2xl"></i>
            <i x-show="open" x-cloak class="fas fa-times text-2xl"></i>
        </button>

        {{-- Menu Desktop --}}
        <nav class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider items-center">
            
            <a href="{{ route('user.home') }}" class="hover:text-red-200 transition flex items-center">
                <i class="fas fa-home mr-2"></i> Home
            </a>
            
            <a href="{{ route('user.log') }}" class="hover:text-red-200 transition relative group flex items-center">
                <i class="fas fa-history mr-2"></i> Log Aktivitas
            </a>
            
            <a href="{{ route('user.download.page') }}" class="text-white font-bold text-sm hover:underline flex items-center uppercase tracking-wider">
                <i class="fas fa-file-download mr-2"></i> Download Data
            </a>
            
            <div class="border-l border-red-300 h-6 mx-2"></div>
            
            {{-- PROFILE BULAT --}}
            <div class="relative group cursor-pointer mr-2" @click="showProfileModal = true">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-lg bg-red-800 transform group-hover:scale-110 transition duration-300">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" 
                         alt="Profile" class="w-full h-full object-cover">
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-white text-sorex px-5 py-2 rounded-full font-bold hover:bg-gray-100 transition shadow-lg transform hover:scale-105 text-xs">
                    Log Out <i class="fas fa-sign-out-alt ml-1"></i>
                </button>
            </form>
        </nav>

        {{-- Menu Mobile --}}
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-full"
             x-cloak
             class="absolute top-0 left-0 w-full bg-red-900/95 backdrop-blur-md shadow-2xl md:hidden pt-24 pb-8 px-6 flex flex-col space-y-4 text-center z-40 border-b border-red-700">
            
            {{-- Profile Mobile --}}
            <div class="flex flex-col items-center mb-4 border-b border-red-800 pb-4">
                <div class="relative w-16 h-16 rounded-full overflow-hidden border-2 border-white mb-2 bg-red-800 shadow-xl">
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
         MAIN CONTENT (TRACKING)
         ========================================= --}}
    <main class="flex-grow container mx-auto px-4 py-8 max-w-5xl">

        {{-- Header Page --}}
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800 border-l-4 border-sorex pl-3">Hasil Tracking</h1>
            <a href="{{ route('user.home') }}" class="text-sm font-bold text-gray-500 hover:text-sorex transition"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>

        {{-- SEARCH BAR --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-200">
            <form action="{{ route('user.request.track') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow relative">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="keyword" value="{{ $keyword ?? '' }}" 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200" 
                           placeholder="Cari ID Request (BS... / RB...) atau Nama Toko">
                </div>
                <button type="submit" class="bg-sorex text-white font-bold py-3 px-8 rounded-lg hover:bg-red-800 transition shadow-lg">
                    Cari
                </button>
            </form>
        </div>

        {{-- HASIL PENCARIAN --}}
        @if($results->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-700">Data Tidak Ditemukan</h3>
                <p class="text-gray-500 mt-2">ID Request atau Nama Toko "<b>{{ $keyword }}</b>" tidak ada.</p>
            </div>
        @else
            
            <div class="mb-4 text-gray-600 font-semibold flex items-center">
                <i class="fas fa-list-ul mr-2 text-sorex"></i> Ditemukan {{ $results->count() }} hasil:
            </div>

            @foreach($results as $item)
                {{-- ... LOGIKA PHP STEPPER SAMA SEPERTI SEBELUMNYA (TIDAK BERUBAH) ... --}}
                @php
                    $step1 = true; 
                    $step2 = !empty($item->konfirmasi_design) || !empty($item->approve_toko);
                    $step3 = !empty($item->nama_vendor) || !empty($item->tanggal_masuk_vendor);
                    $step4 = !empty($item->nomor_resi) || !empty($item->kirim_ke_ekspedisi);
                    $step5 = !empty($item->konfirmasi_penerimaan);

                    $statusText = 'Menunggu Proses';
                    $statusColor = 'bg-gray-500';
                    if($step5) { $statusText = 'SELESAI'; $statusColor = 'bg-green-600'; }
                    elseif($step4) { $statusText = 'PENGIRIMAN'; $statusColor = 'bg-purple-600'; }
                    elseif($step3) { $statusText = 'PRODUKSI'; $statusColor = 'bg-orange-500'; }
                    elseif($step2) { $statusText = 'DESIGN'; $statusColor = 'bg-blue-500'; }

                    $logs = [];
                    $addLog = function(&$arr, $value, $title, $desc) use ($item) {
                        if (!empty($value) && $value != '-' && $value != '0000-00-00') {
                            $displayData = '';
                            $sortTime = 0;
                            if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $value)) {
                                try {
                                    $dateObj = \Carbon\Carbon::parse($value);
                                    $displayData = $dateObj->format('d M Y');
                                    $sortTime = $dateObj->timestamp;
                                } catch (\Exception $e) { $displayData = $value; }
                            } else {
                                $displayData = $value; 
                                $sortTime = strtotime($item->updated_at); 
                            }
                            $arr[] = ['time' => $sortTime, 'badge' => $displayData, 'title' => $title, 'desc' => $desc];
                        }
                    };

                    $addLog($logs, $item->submission_date, 'Request Masuk', 'Data permintaan branding diterima sistem.');
                    $addLog($logs, $item->pembuatan_design, 'Mulai Design', 'Tim desain mulai memproses artwork.');
                    $addLog($logs, $item->approve_leader, 'Approve Leader', 'Desain disetujui oleh Leader.');
                    $addLog($logs, $item->approve_toko, 'Approve Toko', 'Pihak toko menyetujui desain.');
                    $addLog($logs, $item->konfirmasi_design, 'Design Final', 'Desain fix dan siap produksi.');
                    $addLog($logs, $item->tanggal_masuk_vendor, 'Masuk Vendor', 'File dikirim ke vendor: ' . ($item->nama_vendor ?? '-'));
                    $addLog($logs, $item->sj_di_terima_tasya, 'SJ Diterima', 'Surat Jalan diterima admin.');
                    $addLog($logs, $item->po_selesai_gudang_fr, 'PO Selesai', 'Barang selesai produksi.');
                    $addLog($logs, $item->packing_barang_fr, 'Packing Barang', 'Proses packing dilakukan.');
                    $addLog($logs, $item->kirim_ke_dadap, 'Kirim ke Dadap', 'Otw gudang pusat.');
                    $addLog($logs, $item->terima_di_dadap, 'Terima di Dadap', 'Barang sampai di gudang pusat.');
                    
                    if(!empty($item->kirim_ke_ekspedisi)){
                        $msg = 'Diserahkan ke ekspedisi.<br>';
                        if(!empty($item->nomor_resi)) {
                            $resi = $item->nomor_resi;
                            $msg .= '<div class="mt-2 flex items-center gap-2 bg-yellow-50 p-2 rounded-lg border border-yellow-200 w-fit">';
                            $msg .= '<i class="fas fa-barcode text-gray-500"></i>';
                            $msg .= '<span class="font-mono font-black text-gray-800 tracking-wide select-all" style="font-weight: 800;">'.$resi.'</span>';
                            $msg .= '<button onclick="copyResi(\''.$resi.'\', this)" class="ml-2 text-gray-400 hover:text-sorex transition" title="Salin"><i class="fas fa-copy"></i></button>';
                            $msg .= '</div>';
                        }
                        $addLog($logs, $item->kirim_ke_ekspedisi, 'Pengiriman', $msg);
                    }
                    
                    $addLog($logs, $item->konfirmasi_penerimaan, 'Selesai', 'Barang telah diterima toko.');
                    usort($logs, function($a, $b) { return $b['time'] - $a['time']; });
                @endphp

                {{-- CARD UTAMA --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-10 transform transition hover:-translate-y-1 duration-300">
                    
                    {{-- Header Card --}}
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">ID Request</span>
                            <h2 class="text-3xl font-extrabold text-sorex">{{ $item->request_id }}</h2>
                        </div>
                        <div class="mt-2 md:mt-0 text-right">
                            <span class="{{ $statusColor }} text-white text-xs font-bold px-3 py-1 rounded-full uppercase shadow-sm tracking-wide">{{ $statusText }}</span>
                            <div class="text-xs text-gray-500 mt-1 font-semibold">Tgl: {{ \Carbon\Carbon::parse($item->submission_date)->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Nama Toko</p>
                            <p class="font-bold text-gray-800 text-lg">{{ $item->nama_toko }}</p>
                            <p class="text-sm text-gray-500"><i class="fas fa-map-marker-alt mr-1"></i> {{ $item->area_sales }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Detail Branding</p>
                            <p class="font-bold text-gray-800">{{ $item->brand }}</p>
                            <p class="text-sm text-gray-600">{{ $item->jenis_tools_branding }} ({{ $item->qty_tools }} Pcs)</p>
                        </div>
                    </div>

                    {{-- Horizontal Stepper --}}
                    <div class="px-6 py-8 border-t border-gray-100 bg-white relative">
                        <div class="absolute top-12 left-10 right-10 h-1 bg-gray-100 -z-0 hidden md:block"></div>
                        <div class="flex flex-col md:flex-row justify-between relative z-10 gap-6 md:gap-0">
                             @foreach([
                                ['Request', 'Data Masuk', 'fa-file-alt', $step1],
                                ['Design', 'Acc Design', 'fa-paint-brush', $step2],
                                ['Vendor', 'Produksi', 'fa-industry', $step3],
                                ['Kirim', 'Ekspedisi', 'fa-truck', $step4],
                                ['Selesai', 'Diterima', 'fa-check-circle', $step5]
                            ] as $step)
                            <div class="flex md:flex-col items-center group">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 {{ $step[3] ? 'step-active' : 'step-inactive' }} bg-white z-10 transition-colors duration-300">
                                    <i class="fas {{ $step[2] }}"></i>
                                </div>
                                <div class="ml-4 md:ml-0 md:mt-3 md:text-center">
                                    <p class="text-sm font-bold {{ $step[3] ? 'text-red-600' : 'text-gray-400' }}">{{ $step[0] }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $step[1] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Vertical Timeline --}}
                    <div class="bg-gray-50 border-t border-gray-200 p-6 md:p-8">
                        <h4 class="font-bold text-gray-800 mb-6 flex items-center text-lg">
                            <i class="fas fa-history mr-2 text-sorex"></i> Detail Riwayat Status
                        </h4>

                        @if(empty($logs))
                            <div class="text-center py-4 bg-white rounded border border-gray-200">
                                <p class="text-gray-400 italic text-sm">Belum ada update status tambahan.</p>
                            </div>
                        @else
                            <div class="relative ml-2 md:ml-4">
                                @foreach($logs as $index => $log)
                                    <div class="v-item relative flex gap-4 pb-8">
                                        <div class="v-line"></div>
                                        <div class="relative z-10 mt-1">
                                            @if($index == 0)
                                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center border-2 border-red-500 shadow-lg text-red-600 transform scale-110"><i class="fas fa-dot-circle"></i></div>
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center border border-gray-300 text-gray-500"><i class="fas fa-check"></i></div>
                                            @endif
                                        </div>
                                        <div class="flex-grow bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                            <div class="flex flex-col md:flex-row justify-between items-start mb-2">
                                                <h5 class="font-bold text-gray-800 {{ $index == 0 ? 'text-lg text-red-600' : 'text-base' }}">{{ $log['title'] }}</h5>
                                                <span class="text-xs font-bold bg-gray-100 text-gray-600 px-3 py-1 rounded-full mt-1 md:mt-0 border border-gray-200">{{ $log['badge'] }}</span>
                                            </div>
                                            <div class="text-sm text-gray-600 leading-relaxed">{!! $log['desc'] !!}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <div class="text-center mt-8 text-gray-400 text-xs pb-6">
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

    {{-- Script Copy --}}
    <script>
        function copyResi(text, btn) {
            navigator.clipboard.writeText(text);
            let originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-green-500"></i>';
            setTimeout(() => { btn.innerHTML = originalContent; }, 1000);
        }
    </script>

    @include('components.keep-alive')
</body>
</html>