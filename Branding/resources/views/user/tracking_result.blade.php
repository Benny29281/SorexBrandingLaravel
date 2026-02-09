<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Tracking - SOREX</title>
    
    {{-- Library --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; }
        
        .v-line {
            position: absolute;
            top: 2rem;
            left: 0.95rem;
            bottom: -1rem;
            width: 2px;
            background-color: #e5e7eb;
            z-index: 0;
        }
        .v-item:last-child .v-line { display: none; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ open: false, showProfileModal: false }" class="bg-gray-50 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <header class="w-full py-3 px-4 sm:px-10 flex justify-between items-center text-white z-50 relative bg-sorex shadow-md">
        <div class="flex items-center z-50">
            <a href="{{ route('user.home') }}">
                <img src="{{ asset('img/logo5.png') }}" alt="SOREX Logo" class="h-8 md:h-12 w-auto drop-shadow-md" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-widest italic drop-shadow-md hidden">SOREX</h1>
            </a>
        </div>

        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-xl"></i>
            <i x-show="open" x-cloak class="fas fa-times text-xl"></i>
        </button>

        <nav class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider items-center">
            <a href="{{ route('user.home') }}" class="hover:text-red-200 transition flex items-center"><i class="fas fa-home mr-2"></i> Home</a>
            <a href="{{ route('user.log') }}" class="hover:text-red-200 transition relative group flex items-center"><i class="fas fa-history mr-2"></i> Log Aktivitas</a>
            <div class="border-l border-red-300 h-6 mx-2"></div>
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
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow container mx-auto px-3 py-6 max-w-4xl">

        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 border-l-4 border-sorex pl-3">Hasil Tracking</h1>
            <a href="{{ route('user.home') }}" class="text-xs md:text-sm font-bold text-gray-500 hover:text-sorex transition"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-200">
            <form action="{{ route('user.request.track') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="flex-grow relative">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="keyword" value="{{ $keyword ?? '' }}" 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200 text-sm" 
                           placeholder="Cari ID (BS...) atau Nama Toko">
                </div>
                <button type="submit" class="bg-sorex text-white font-bold py-2.5 px-6 rounded-lg hover:bg-red-800 transition shadow-md text-sm">
                    Cari
                </button>
            </form>
        </div>

        @if($results->isEmpty())
            <div class="text-center py-10 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-search text-2xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700">Tidak Ditemukan</h3>
                <p class="text-sm text-gray-500 mt-1">ID atau Toko "<b>{{ $keyword }}</b>" tidak ada.</p>
            </div>
        @else
            @foreach($results as $item)
    @php
        // 1. Logika Stepper Atas
        $step1 = true; 
        $step2 = !empty($item->pembuatan_design) || !empty($item->konfirmasi_design) || !empty($item->approve_leader);
        $step3 = !empty($item->tanggal_masuk_vendor) || !empty($item->po_selesai_gudang_fr);
        $step4 = !empty($item->kirim_ke_ekspedisi) || !empty($item->nomor_resi);
        $step5 = !empty($item->konfirmasi_penerimaan);

        // 2. Variabel Label Status Kanan Atas
        $statusText = 'Proses'; 
        $statusColor = 'bg-gray-500';
        if($step5) { $statusText = 'SELESAI'; $statusColor = 'bg-green-600'; }
        elseif($step4) { $statusText = 'KIRIM'; $statusColor = 'bg-purple-600'; }
        elseif($step3) { $statusText = 'PRODUKSI'; $statusColor = 'bg-orange-500'; }
        elseif($step2) { $statusText = 'DESIGN'; $statusColor = 'bg-blue-500'; }

        // 3. Fungsi AddLog dengan Revisi Jam (d M Y | H:i)
        $logs = [];
        $addLog = function(&$arr, $value, $title, $desc, $priority) {
            if (!empty($value) && !in_array($value, ['-', '0000-00-00', '0000-00-00 00:00:00'])) {
                try {
                    $dateObj = \Carbon\Carbon::parse($value);
                    $arr[] = [
                        'priority' => $priority,
                        'badge' => $dateObj->format('d M Y | H:i'), 
                        'title' => $title, 
                        'desc' => $desc
                    ];
                } catch (\Exception $e) { }
            }
        };

        $addLog($logs, $item->submission_date, 'Request', 'Diterima sistem.', 1);
        $addLog($logs, $item->pembuatan_design, 'Mulai Design', 'Proses artwork dimulai.', 2);
        $addLog($logs, $item->konfirmasi_design, 'Design Final', 'Desain telah diselesaikan.', 3);
        $addLog($logs, $item->approve_leader, 'Acc Leader', 'Disetujui oleh Leader.', 4);
        $addLog($logs, $item->approve_toko, 'Acc Toko', 'Disetujui oleh pihak Toko.', 5);
        $addLog($logs, $item->tanggal_masuk_vendor, 'Vendor', 'Masuk ke: ' . ($item->nama_vendor ?? '-'), 6);
        $addLog($logs, $item->sj_di_terima_tasya, 'SJ Diterima Tasya', 'Surat Jalan telah diterima admin.', 8);
        $addLog($logs, $item->po_selesai_gudang_fr, 'PO Selesai', 'Produksi barang selesai.', 9);
        $addLog($logs, $item->packing_barang_fr, 'Packing di FR', 'Barang sedang dipacking.', 10);
        $addLog($logs, $item->kirim_ke_dadap, 'Kirim ke Dadap', 'Barang dikirim ke gudang pusat.', 11);
        $addLog($logs, $item->terima_di_dadap, 'Diterima di Dadap', 'Barang sampai di gudang pusat.', 12);
        
        $msgKirim = 'Barang diserahkan ke ekspedisi.';
        if(!empty($item->nomor_resi)) {
            $resi = $item->nomor_resi;
            $msgKirim .= '<div class="mt-2 flex items-center gap-2 bg-yellow-50 p-2 rounded-lg border border-yellow-200 w-fit">';
            $msgKirim .= '<i class="fas fa-barcode text-gray-500 text-xs"></i>';
            $msgKirim .= '<span class="font-mono font-bold text-gray-800 tracking-wide select-all text-[11px]">'.$resi.'</span>';
            $msgKirim .= '<button onclick="copyResi(\''.$resi.'\', this)" class="ml-2 text-gray-400 hover:text-red-600 transition" title="Salin">';
            $msgKirim .= '<i class="fas fa-copy text-xs"></i>';
            $msgKirim .= '</button>';
            $msgKirim .= '</div>';
        }
        $addLog($logs, $item->kirim_ke_ekspedisi, 'Kirim Ekspedisi', $msgKirim, 13);
        $addLog($logs, $item->konfirmasi_penerimaan, 'Selesai', 'Barang telah diterima oleh toko.', 15);

        usort($logs, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });
    @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-lg text-sorex">{{ $item->request_id }}</span>
                            <span class="text-[10px] bg-white border border-gray-200 px-2 py-0.5 rounded text-gray-500">{{ \Carbon\Carbon::parse($item->submission_date)->format('d M Y') }}</span>
                        </div>
                        <span class="{{ $statusColor }} text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm uppercase">{{ $statusText }}</span>
                    </div>

                    <div class="px-4 py-3 grid grid-cols-2 gap-2 text-sm border-b border-gray-100">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Toko & Sales</p>
                            <p class="font-bold text-gray-800 leading-tight uppercase">{{ $item->nama_toko }}</p>
                            <p class="text-[11px] text-red-600 font-semibold uppercase">{{ $item->nama_sales ?? '-' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $item->area_sales }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Branding & Dimensi</p>
                            <p class="font-medium text-gray-700 leading-tight uppercase">{{ $item->jenis_tools_branding }}</p>
                            <p class="text-[11px] text-gray-800 font-bold">{{ $item->ukuran_fix ?? $item->ukuran_tools_branding }}</p>
                            <p class="text-[10px] text-gray-500">Qty: {{ $item->qty_tools }}</p>
                        </div>
                    </div>

                    <div class="px-4 py-3 overflow-x-auto no-scrollbar border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center gap-3 min-w-max">
                            @foreach([
                                ['Request', 'fa-file', $step1],
                                ['Design', 'fa-paint-brush', $step2],
                                ['Produk', 'fa-industry', $step3],
                                ['Kirim', 'fa-truck', $step4],
                                ['Done', 'fa-check', $step5]
                            ] as $index => $step)
                                <div class="flex flex-col items-center gap-1 opacity-{{ $step[2] ? '100' : '40' }}">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs border {{ $step[2] ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-400 border-gray-300' }}">
                                        <i class="fas {{ $step[1] }}"></i>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase {{ $step[2] ? 'text-red-600' : 'text-gray-400' }}">{{ $step[0] }}</span>
                                </div>
                                @if($index < 4) 
                                    <div class="h-0.5 w-6 {{ $step[2] && (!empty($results[$index+1]) ? true : false) ? 'bg-red-300' : 'bg-gray-200' }} mb-3"></div> 
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="p-5 bg-white">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-5 flex items-center">
                            <i class="fas fa-history mr-2 text-sorex"></i> Update Status Item Ini
                        </h4>
                        
                        @if(empty($logs))
                            <p class="text-xs text-gray-400 italic text-center">Belum ada riwayat update.</p>
                        @else
                            <div class="relative ml-2">
                                @foreach($logs as $index => $log)
                                    <div class="v-item relative flex gap-5 pb-6 last:pb-0">
                                        <div class="v-line" style="left: 0.7rem; top: 1.5rem; bottom: -1rem;"></div>
                                        
                                        <div class="relative z-10">
                                            @if($index == 0)
                                                <div class="w-6 h-6 rounded-full bg-red-600 flex items-center justify-center shadow-lg ring-4 ring-red-100 animate-pulse">
                                                    <i class="fas fa-check text-[10px] text-white"></i>
                                                </div>
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-white border-2 border-gray-200 flex items-center justify-center">
                                                    <div class="w-2 h-2 rounded-full bg-gray-200"></div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-grow">
                                            <div class="flex justify-between items-start mb-1 gap-2">
                                                <span class="text-sm font-bold {{ $index == 0 ? 'text-red-600' : 'text-gray-700' }}">
                                                    {{ $log['title'] }}
                                                </span>
                                                <span class="text-[9px] bg-gray-100 text-gray-500 px-2 py-1 rounded font-mono font-bold whitespace-nowrap border border-gray-200">
                                                    {{ $log['badge'] }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] {{ $index == 0 ? 'text-gray-700 font-medium' : 'text-gray-500' }} leading-snug">
                                                {!! $log['desc'] !!}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <div class="text-center mt-6 text-gray-400 text-xs pb-6">
            &copy; 2026 SOREX Branding System.
        </div>
    </main>

    {{-- MODAL PROFILE --}}
    <div x-show="showProfileModal" style="display: none;" 
         class="fixed inset-0 z-[110] flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4"
         x-transition>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 relative flex flex-col items-center text-center" @click.away="showProfileModal = false">
            <button @click="showProfileModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-red-100 shadow-xl mb-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" alt="Profile" class="w-full h-full object-cover">
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

    <script>
        function copyResi(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                let originalContent = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check text-green-500 text-xs"></i>';
                setTimeout(() => { 
                    btn.innerHTML = originalContent; 
                }, 1500);
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }
    </script>
</body>
</html>