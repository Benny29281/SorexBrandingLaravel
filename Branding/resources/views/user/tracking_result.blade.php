<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Tracking - SOREX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        
        /* Style Horizontal Stepper (Atas) */
        .step-active { @apply bg-red-600 text-white border-red-600; }
        .step-inactive { @apply bg-gray-100 text-gray-400 border-gray-300; }
        
        /* Style Vertical Timeline (Bawah) */
        .v-line::before {
            content: '';
            position: absolute;
            top: 2.2rem;
            left: 1.2rem; /* Posisi garis vertikal */
            height: 100%;
            width: 2px;
            background-color: #e5e7eb;
            z-index: 0;
        }
        /* Hilangkan garis di item terakhir agar rapi */
        .v-item:last-child .v-line::before { display: none; } 
    </style>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-md border-b-4 border-sorex sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('user.home') }}" class="flex items-center text-sorex font-bold text-lg hover:text-red-800 transition">
                        <i class="fas fa-chevron-left mr-2"></i> KEMBALI
                    </a>
                </div>
                <div class="font-bold text-gray-700">TRACKING SYSTEM</div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow container mx-auto px-4 py-8 max-w-5xl">

        {{-- SEARCH BAR --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-200">
            <form action="{{ route('user.request.track') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow relative">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="keyword" value="{{ $keyword ?? '' }}" 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-200" 
                           placeholder="Cari ID Request (BS... / RB...) atau Nama Toko">
                </div>
                <button type="submit" class="bg-sorex text-white font-bold py-3 px-8 rounded-lg hover:bg-red-800 transition">
                    Cari
                </button>
            </form>
        </div>

        {{-- HASIL PENCARIAN --}}
        @if($results->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700">Data Tidak Ditemukan</h3>
                <p class="text-gray-500 mt-2">ID Request atau Nama Toko "<b>{{ $keyword }}</b>" tidak ada.</p>
            </div>
        @else
            
            <div class="mb-4 text-gray-600 font-semibold">
                Ditemukan {{ $results->count() }} hasil:
            </div>

            @foreach($results as $item)
                
                @php
                    // --- 1. LOGIKA STEP HORIZONTAL (Visual Atas) ---
                    $step1 = true; // Request Masuk
                    $step2 = !empty($item->konfirmasi_design) || !empty($item->approve_toko); // Design
                    $step3 = !empty($item->nama_vendor) || !empty($item->tanggal_masuk_vendor); // Vendor
                    $step4 = !empty($item->nomor_resi) || !empty($item->kirim_ke_ekspedisi); // Kirim
                    $step5 = !empty($item->konfirmasi_penerimaan); // Selesai

                    // Teks Status Kanan Atas
                    $statusText = 'Menunggu Proses';
                    $statusColor = 'bg-gray-500';
                    if($step5) { $statusText = 'SELESAI'; $statusColor = 'bg-green-600'; }
                    elseif($step4) { $statusText = 'PENGIRIMAN'; $statusColor = 'bg-purple-600'; }
                    elseif($step3) { $statusText = 'PRODUKSI'; $statusColor = 'bg-orange-500'; }
                    elseif($step2) { $statusText = 'DESIGN'; $statusColor = 'bg-blue-500'; }

                    // --- 2. LOGIKA HISTORY ENGINE ---
                    $logs = [];

                    $addLog = function(&$arr, $value, $title, $desc) use ($item) {
                        if (!empty($value) && $value != '-' && $value != '0000-00-00') {
                            $displayData = '';
                            $sortTime = 0;
                            // Cek Format Tanggal (YYYY-MM-DD)
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
                            $arr[] = [
                                'time'  => $sortTime,
                                'badge' => $displayData, // Ini yang tampil di kotak kecil kanan
                                'title' => $title,
                                'desc'  => $desc
                            ];
                        }
                    };

                    // Panggil fungsi (Urut Kronologis)
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
                    
                    // === MODIFIKASI KHUSUS NOMOR RESI (BOLD & COPY) ===
                    if(!empty($item->kirim_ke_ekspedisi)){
                        $msg = 'Diserahkan ke ekspedisi.<br>';
                        if(!empty($item->nomor_resi)) {
                            $resi = $item->nomor_resi;
                            // HTML untuk Resi Bold & Tombol Copy
                            $msg .= '<div class="mt-2 flex items-center gap-2 bg-yellow-50 p-2 rounded-lg border border-yellow-200 w-fit">';
                            $msg .= '<i class="fas fa-barcode text-gray-500"></i>';
                            // class select-all membuat teks otomatis ter-blok saat diklik
                            $msg .= '<span class="font-mono font-black text-gray-800 tracking-wide select-all" style="font-weight: 800;">'.$resi.'</span>';
                            // Tombol Copy Simple
                            $msg .= '<button onclick="copyResi(\''.$resi.'\', this)" class="ml-2 text-gray-400 hover:text-sorex transition" title="Salin"><i class="fas fa-copy"></i></button>';
                            $msg .= '</div>';
                        }
                        $addLog($logs, $item->kirim_ke_ekspedisi, 'Pengiriman', $msg);
                    }
                    
                    $addLog($logs, $item->konfirmasi_penerimaan, 'Selesai', 'Barang telah diterima toko.');

                    // Urutkan Array (Terbaru Paling Atas)
                    usort($logs, function($a, $b) { return $b['time'] - $a['time']; });
                @endphp

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-10 transform transition hover:-translate-y-1 duration-300">
                    
                    {{-- HEADER & INFO UTAMA --}}
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

                    {{-- BAGIAN 1: HORIZONTAL STEPPER --}}
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

                    {{-- BAGIAN 2: VERTICAL TIMELINE (RINCIAN BAWAH) --}}
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
                                        {{-- Garis Penghubung Vertikal --}}
                                        <div class="v-line"></div>
                                        
                                        {{-- Bullet Point --}}
                                        <div class="relative z-10 mt-1">
                                            @if($index == 0)
                                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center border-2 border-red-500 shadow-lg text-red-600 transform scale-110">
                                                    <i class="fas fa-dot-circle"></i>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center border border-gray-300 text-gray-500">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Teks Konten --}}
                                        <div class="flex-grow bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                            <div class="flex flex-col md:flex-row justify-between items-start mb-2">
                                                <h5 class="font-bold text-gray-800 {{ $index == 0 ? 'text-lg text-red-600' : 'text-base' }}">
                                                    {{ $log['title'] }}
                                                </h5>
                                                <span class="text-xs font-bold bg-gray-100 text-gray-600 px-3 py-1 rounded-full mt-1 md:mt-0 border border-gray-200">
                                                    {{ $log['badge'] }}
                                                </span>
                                            </div>
                                            
                                            {{-- PERHATIKAN: Gunakan {!! !!} agar HTML Resi Bold & Tombol muncul --}}
                                            <div class="text-sm text-gray-600 leading-relaxed">
                                                {!! $log['desc'] !!}
                                            </div>
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

    {{-- SCRIPT SEDERHANA COPY (TANPA ALERT) --}}
    <script>
        function copyResi(text, btn) {
            // Salin ke Clipboard
            navigator.clipboard.writeText(text);
            
            // Ubah Ikon jadi Centang Hijau sebentar (Visual Feedback)
            let originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-green-500"></i>';
            
            setTimeout(() => {
                btn.innerHTML = originalContent;
            }, 1000);
        }
    </script>
</body>
</html>