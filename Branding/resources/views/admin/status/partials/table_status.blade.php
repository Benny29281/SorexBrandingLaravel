{{-- 1. WRAPPER UTAMA: MENGATUR SCROLL BAR --}}
<div class="overflow-auto max-h-[80vh] w-full border rounded-lg shadow relative">
    
    <table class="min-w-max w-full table-auto text-xs border-collapse font-sans">
        <thead>
            <tr class="bg-gray-900 text-white uppercase font-bold tracking-wider text-left">
                
                {{-- A. HEADER POJOK KIRI (REQUEST ID) --}}
                <th class="py-3 px-4 sticky top-0 left-0 z-50 bg-gray-900 border-r border-b border-gray-600 shadow-lg" 
                    style="min-width: 100px;">
                    REQUEST ID
                </th>

                {{-- B. HEADER LAINNYA --}}
                <th class="py-3 px-4 sticky top-0 z-40 bg-gray-900 border-b border-gray-600" style="min-width: 100px;">TGL REQUEST</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-gray-900 border-b border-gray-600" style="min-width: 120px;">SALES</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-gray-900 border-b border-gray-600" style="min-width: 150px;">TOKO</th>
                <th class="py-3 px-4 text-center sticky top-0 z-40 bg-gray-900 border-b border-gray-600">AREA</th>
                
                {{-- INFO TAMBAHAN --}}
                <th class="py-3 px-4 text-center sticky top-0 z-40 bg-gray-800 border-b border-gray-600">TIPE (B/P)</th>
                <th class="py-3 px-4 text-center sticky top-0 z-40 bg-gray-800 border-b border-gray-600">VIA</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-gray-800 border-b border-gray-600" style="min-width: 150px;">PERMINTAAN</th>
                <th class="py-3 px-4 text-center sticky top-0 z-40 bg-gray-800 border-b border-gray-600">QTY</th>

                {{-- DESIGN --}}
                <th class="py-3 px-4 sticky top-0 z-40 bg-blue-900 border-l border-b border-blue-700">PEMBUATAN DESIGN</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-blue-900 border-b border-blue-700">APPROVE LEADER</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-blue-900 border-b border-blue-700">APPROVE TOKO</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-blue-900 border-b border-blue-700">KONFIRMASI DESIGN</th>

                {{-- VENDOR --}}
                <th class="py-3 px-4 sticky top-0 z-40 bg-yellow-700 border-l border-b border-yellow-600">MASUK VENDOR</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-yellow-700 border-b border-yellow-600" style="min-width: 150px;">NAMA VENDOR</th>

                {{-- LOGISTIK --}}
                <th class="py-3 px-4 sticky top-0 z-40 bg-green-900 border-l border-b border-green-700">SJ DI TERIMA TASYA</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-green-900 border-b border-green-700">PO SELESAI & KE GUDANG FR</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-green-900 border-b border-green-700">PACKING DI GUDANG FR</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-green-900 border-b border-green-700">KIRIM KE DADAP</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-green-900 border-b border-green-700">TERIMA DI DADAP</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-green-900 border-b border-green-700">KIRIM KE EKSPEDISI</th>

                {{-- KONFIRMASI --}}
                <th class="py-3 px-4 sticky top-0 z-40 bg-purple-900 border-l border-b border-purple-700">NOMOR RESI</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-purple-900 border-b border-purple-700">KONF. PENERIMAAN</th>
            </tr>
        </thead>
        <tbody class="text-black bg-white font-medium"> 
            {{-- ^^^ REVISI: text-black agar semua tulisan hitam --}}
            
            @forelse($data as $item)
                @php 
                    $parent = $item->parent_data; 
                    
                    // Logic Warna Area
                    $areaColorClass = 'bg-gray-100 text-gray-800'; // Default
                    if($parent) {
                        $area = strtoupper($parent->area_sales);
                        if ($area == 'JT') { $areaColorClass = 'bg-red-100 text-red-700 border border-red-200'; }
                        elseif ($area == 'DK') { $areaColorClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200'; } // Kuning agak gelap biar kebaca
                        elseif ($area == 'LP') { $areaColorClass = 'bg-green-100 text-green-700 border border-green-200'; }
                        elseif ($area == 'JB') { $areaColorClass = 'bg-blue-100 text-blue-700 border border-blue-200'; }
                        elseif ($area == 'JR') { $areaColorClass = 'bg-green-100 text-green-700 border border-green-200'; }
                    }

                    $payload = $item->toArray();
                    $payload['parent_data'] = $parent ? $parent->toArray() : null;
                @endphp
                
                <tr class="border-b border-gray-300 hover:bg-gray-50 transition cursor-pointer"
                    @click="showModal = true; processItem = {{ json_encode($payload) }}">
                    
                    {{-- C. KOLOM UTAMA (STICKY LEFT) --}}
                    {{-- REQUEST ID (Warna sesuai Tab: Merah/Hijau) --}}
                    <td class="py-3 px-4 font-bold text-{{ $color }}-600 sticky left-0 z-30 bg-gray-50 border-r border-gray-300 shadow-sm">
                        {{ $item->request_id }}
                    </td>

                    {{-- D. ISI DATA BIASA --}}
                    <td class="py-3 px-4 whitespace-nowrap">{{ $parent ? \Carbon\Carbon::parse($parent->submission_date)->format('d M Y') : '-' }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">{{ $parent ? $parent->nama_sales : '-' }}</td>
                    <td class="py-3 px-4 whitespace-nowrap font-bold uppercase">{{ $parent ? $parent->nama_toko : 'DATA DIHAPUS' }}</td>
                    
                    {{-- KOLOM AREA (WARNA-WARNI) --}}
                    <td class="py-3 px-4 text-center">
                        @if($parent)
                            <span class="{{ $areaColorClass }} py-1 px-3 rounded-md text-xs font-extrabold shadow-sm block w-full">
                                {{ $parent->area_sales }}
                            </span>
                        @endif
                    </td>

                    <td class="py-3 px-4 text-center font-bold">
                        {{ $parent ? $parent->jenis_permintaan : '-' }}
                    </td>
                    <td class="py-3 px-4 text-center">{{ $item->via }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">{{ $parent ? $parent->jenis_tools_branding : '-' }}</td>
                    <td class="py-3 px-4 text-center font-bold">{{ $parent ? $parent->qty_tools : 0 }}</td>

                    {{-- DESIGN (Background Biru Muda Tipis) --}}
                    <td class="py-3 px-4 text-center border-l border-gray-200 bg-blue-50">{{ $item->pembuatan_design ? \Carbon\Carbon::parse($item->pembuatan_design)->format('d M Y') : '-' }}</td>
                    <td class="py-3 px-4 text-center bg-blue-50">{{ $item->approve_leader ? \Carbon\Carbon::parse($item->approve_leader)->format('d M Y') : '-' }}</td>
                    <td class="py-3 px-4 text-center bg-blue-50">{{ $item->approve_toko ? \Carbon\Carbon::parse($item->approve_toko)->format('d M Y') : '-' }}</td>
                    <td class="py-3 px-4 text-center bg-blue-50">{{ $item->konfirmasi_design ? \Carbon\Carbon::parse($item->konfirmasi_design)->format('d M Y') : '-' }}</td>

                    {{-- VENDOR (Background Kuning Muda Tipis) --}}
                    <td class="py-3 px-4 text-center border-l border-gray-200 bg-yellow-50">{{ $item->tanggal_masuk_vendor ? \Carbon\Carbon::parse($item->tanggal_masuk_vendor)->format('d M Y') : '-' }}</td>
                    <td class="py-3 px-4 whitespace-nowrap bg-yellow-50 font-semibold">{{ $item->nama_vendor ?? '-' }}</td>

                    {{-- LOGISTIK (Background Hijau Muda Tipis) --}}
                    <td class="py-3 px-4 text-center border-l border-gray-200 bg-green-50">{{ $item->sj_di_terima_tasya ? \Carbon\Carbon::parse($item->sj_di_terima_tasya)->format('d M Y') : '-' }}</td>
                    <td class="py-3 px-4 text-center bg-green-50">{{ $item->po_selesai_gudang_fr ? \Carbon\Carbon::parse($item->po_selesai_gudang_fr)->format('d M Y') : '-' }}</td>
                    <td class="py-3 px-4 text-center bg-green-50">{{ $item->packing_barang_fr ?? '-' }}</td>
                    
                    <td class="py-3 px-4 text-center bg-green-50">
                        {{ $item->kirim_ke_dadap ? \Carbon\Carbon::parse($item->kirim_ke_dadap)->format('d M Y') : '-' }}
                    </td>
                    <td class="py-3 px-4 text-center bg-green-50">
                        {{ $item->terima_di_dadap ? \Carbon\Carbon::parse($item->terima_di_dadap)->format('d M Y') : '-' }}
                    </td>

                    <td class="py-3 px-4 text-center bg-green-50">{{ $item->kirim_ke_ekspedisi ? \Carbon\Carbon::parse($item->kirim_ke_ekspedisi)->format('d M Y') : '-' }}</td>

                    {{-- KONFIRMASI (Background Ungu Muda Tipis) --}}
                    <td class="py-3 px-4 text-center border-l border-gray-200 bg-purple-50 font-mono font-bold text-gray-900">{{ $item->nomor_resi ?? '-' }}</td>
                    <td class="py-3 px-4 text-center bg-purple-50">
                        @if($item->konfirmasi_penerimaan)
                            <span class="text-black font-bold flex items-center justify-center gap-1">
                                <i class="fas fa-check text-green-600"></i>
                                {{ \Carbon\Carbon::parse($item->konfirmasi_penerimaan)->format('d M Y') }}
                            </span>
                        @else
                            <span class="text-gray-400 italic">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="23" class="py-10 text-center text-gray-500 bg-gray-50">
                        <i class="fas fa-folder-open text-4xl mb-3 block text-gray-400"></i>
                        <span class="font-bold">Belum ada data status branding di regional ini.</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>