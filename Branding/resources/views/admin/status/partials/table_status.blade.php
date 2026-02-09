{{-- 1. WRAPPER UTAMA: MENGATUR SCROLL BAR --}}
<div class="overflow-auto max-h-[80vh] w-full border rounded-lg shadow relative bg-white"> 
    
    <table class="min-w-max w-full table-auto text-xs border-collapse font-sans">
        <thead>
            <tr class="bg-gray-900 text-white uppercase font-bold tracking-wider text-left">
                {{-- A. HEADER POJOK KIRI (REQUEST ID) --}}
                <th class="py-3 px-4 sticky top-0 left-0 z-50 bg-gray-900 border-r border-b border-gray-600 shadow-lg" style="min-width: 100px;">
                    REQUEST ID
                </th>

                {{-- B. HEADER LAINNYA --}}
                <th class="py-3 px-4 sticky top-0 z-40 bg-gray-900 border-b border-gray-600" style="min-width: 100px;">TGL REQUEST</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-gray-900 border-b border-gray-600" style="min-width: 120px;">SALES</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-gray-900 border-b border-gray-600" style="min-width: 150px;">TOKO</th>
                <th class="py-3 px-4 text-center sticky top-0 z-40 bg-gray-900 border-b border-gray-600">AREA</th>
                
                <th class="py-3 px-4 text-center sticky top-0 z-40 bg-gray-800 border-b border-gray-600">TIPE (B/P)</th>
                <th class="py-3 px-4 text-center sticky top-0 z-40 bg-gray-800 border-b border-gray-600">VIA</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-gray-800 border-b border-gray-600" style="min-width: 150px;">PERMINTAAN</th>
                <th class="py-3 px-4 sticky top-0 z-40 bg-gray-800 border-b border-gray-600" style="min-width: 100px;">UKURAN</th>
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
            
            @forelse($data as $item)
                @php 
                    $parent = $item->parent_data; 
                    
                    // Logic Warna Area
                    $areaColorClass = 'bg-gray-100 text-gray-800';
                    if($parent) {
                        $area = strtoupper($parent->area_sales);
                        if ($area == 'JT') { $areaColorClass = 'bg-red-100 text-red-700 border border-red-200'; }
                        elseif ($area == 'DK') { $areaColorClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200'; }
                        elseif ($area == 'LP') { $areaColorClass = 'bg-green-100 text-green-700 border border-green-200'; }
                        elseif ($area == 'JB') { $areaColorClass = 'bg-blue-100 text-blue-700 border border-blue-200'; }
                        elseif ($area == 'JR') { $areaColorClass = 'bg-green-100 text-green-700 border border-green-200'; }
                    }

                    // Helper untuk menampilkan Tanggal + Jam
                    $renderDateTime = function($val) {
                        if (!$val) return '<span class="text-gray-400 italic">-</span>';
                        $dt = \Carbon\Carbon::parse($val);
                        return '<div>' . $dt->format('d M Y') . '</div>' . 
                               '<div class="text-[10px] text-blue-600 font-bold">' . $dt->format('H:i') . '</div>';
                    };

                    $payload = $item->toArray();
                    $payload['parent_data'] = $parent ? $parent->toArray() : null;
                @endphp
                
                <tr class="border-b border-gray-300 hover:bg-gray-50 transition cursor-pointer"
                    @click="setProcessItem({{ json_encode($payload) }})">
                    
                    {{-- REQUEST ID --}}
                    <td class="py-3 px-4 font-bold text-{{ $color }}-600 sticky left-0 z-30 bg-gray-50 border-r border-gray-300 shadow-sm">
                        {{ $item->request_id }}
                    </td>

                    {{-- ISI DATA --}}
                    <td class="py-3 px-4 whitespace-nowrap">{{ $parent ? \Carbon\Carbon::parse($parent->submission_date)->format('d M Y') : '-' }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">{{ $parent ? $parent->nama_sales : '-' }}</td>
                    <td class="py-3 px-4 whitespace-nowrap font-bold uppercase">{{ $parent ? $parent->nama_toko : 'DATA DIHAPUS' }}</td>
                    
                    {{-- AREA --}}
                    <td class="py-3 px-4 text-center">
                        @if($parent)
                            <span class="{{ $areaColorClass }} py-1 px-3 rounded-md text-xs font-extrabold shadow-sm block w-full">
                                {{ $parent->area_sales }}
                            </span>
                        @endif
                    </td>

                    <td class="py-3 px-4 text-center font-bold">{{ $parent ? $parent->jenis_permintaan : '-' }}</td>
                    <td class="py-3 px-4 text-center">{{ $item->via }}</td>
                    <td class="py-3 px-4 whitespace-nowrap">{{ $parent ? $parent->jenis_tools_branding : '-' }}</td>
                    
                    <td class="py-3 px-4 whitespace-nowrap font-medium text-gray-700">
                        @if(!empty($item->ukuran_fix))
                            <span class="text-blue-600 font-bold" title="Ukuran Updated">{{ $item->ukuran_fix }}</span>
                            @if($parent && $parent->ukuran_tools_branding != $item->ukuran_fix)
                                <span class="text-[9px] text-gray-400 block line-through">{{ $parent->ukuran_tools_branding }}</span>
                            @endif
                        @else
                            {{ $parent ? $parent->ukuran_tools_branding : '-' }}
                        @endif
                    </td>

                    <td class="py-3 px-4 text-center font-bold">{{ $parent ? $parent->qty_tools : 0 }}</td>

                    {{-- DESIGN (DENGAN JAM) --}}
                    <td class="py-3 px-4 text-center border-l border-gray-200 bg-blue-50">{!! $renderDateTime($item->pembuatan_design) !!}</td>
                    <td class="py-3 px-4 text-center bg-blue-50">{!! $renderDateTime($item->approve_leader) !!}</td>
                    <td class="py-3 px-4 text-center bg-blue-50">{!! $renderDateTime($item->approve_toko) !!}</td>
                    <td class="py-3 px-4 text-center bg-blue-50">{!! $renderDateTime($item->konfirmasi_design) !!}</td>

                    {{-- VENDOR (DENGAN JAM) --}}
                    <td class="py-3 px-4 text-center border-l border-gray-200 bg-yellow-50">{!! $renderDateTime($item->tanggal_masuk_vendor) !!}</td>
                    <td class="py-3 px-4 whitespace-nowrap bg-yellow-50 font-semibold">{{ $item->nama_vendor ?? '-' }}</td>

                    {{-- LOGISTIK (DENGAN JAM) --}}
                    <td class="py-3 px-4 text-center border-l border-gray-200 bg-green-50">{!! $renderDateTime($item->sj_di_terima_tasya) !!}</td>
                    <td class="py-3 px-4 text-center bg-green-50">{!! $renderDateTime($item->po_selesai_gudang_fr) !!}</td>
                    <td class="py-3 px-4 text-center bg-green-50 font-bold">{{ $item->packing_barang_fr ?? '-' }}</td>
                    <td class="py-3 px-4 text-center bg-green-50">{!! $renderDateTime($item->kirim_ke_dadap) !!}</td>
                    <td class="py-3 px-4 text-center bg-green-50">{!! $renderDateTime($item->terima_di_dadap) !!}</td>
                    <td class="py-3 px-4 text-center bg-green-50">{!! $renderDateTime($item->kirim_ke_ekspedisi) !!}</td>

                    {{-- KONFIRMASI --}}
                    <td class="py-3 px-4 text-center border-l border-gray-200 bg-purple-50 font-mono font-bold text-gray-900">{{ $item->nomor_resi ?? '-' }}</td>
                    <td class="py-3 px-4 text-center bg-purple-50 font-bold uppercase">{!! $renderDateTime($item->konfirmasi_penerimaan) !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="24" class="py-10 text-center text-gray-500 bg-gray-50">
                        <i class="fas fa-folder-open text-4xl mb-3 block text-gray-400"></i>
                        <span class="font-bold">Belum ada data status branding di regional ini.</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>