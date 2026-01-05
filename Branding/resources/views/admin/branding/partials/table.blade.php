<div class="overflow-x-auto w-full border border-gray-300 rounded-lg shadow-sm bg-white">
    <table class="min-w-full leading-normal font-sans">
        {{-- HEADER --}}
        <thead>
            <tr class="bg-gray-900 text-white uppercase text-xs font-bold tracking-wider">
                <th class="py-3 px-6 text-left border-b border-gray-700">Tanggal</th>
                <th class="py-3 px-6 text-left border-b border-gray-700">ID</th>
                <th class="py-3 px-6 text-left border-b border-gray-700">Toko</th>
                <th class="py-3 px-6 text-center border-b border-gray-700">Area</th>
                <th class="py-3 px-6 text-left border-b border-gray-700">Brand</th>
                <th class="py-3 px-6 text-left border-b border-gray-700">Tools</th>
                <th class="py-3 px-6 text-center border-b border-gray-700">Qty</th>
                <th class="py-3 px-6 text-center border-b border-gray-700">Actions</th>
            </tr>
        </thead>
        
        {{-- BODY (TEKS HITAM) --}}
        <tbody class="text-gray-900 text-sm font-medium">
            @forelse($data as $item)
                @php
                    // LOGIKA WARNA ID (BS = Merah, RB = Hijau)
                    $idColorClass = 'text-gray-700'; 
                    if (str_contains($item->request_id, 'BS')) {
                        $idColorClass = 'text-red-600'; 
                    } elseif (str_contains($item->request_id, 'RB')) {
                        $idColorClass = 'text-green-600';
                    }

                    // LOGIKA WARNA AREA (Background Saja, Teks Hitam)
                    $areaBgClass = 'bg-gray-100'; // Default
                    $area = strtoupper($item->area_sales);
                    
                    if ($area == 'JT') { $areaBgClass = 'bg-red-200'; }
                    elseif ($area == 'DK' || $area == 'LP') { $areaBgClass = 'bg-yellow-200'; }
                    elseif ($area == 'JB') { $areaBgClass = 'bg-blue-200'; }
                    elseif ($area == 'JR') { $areaBgClass = 'bg-green-200'; }
                @endphp

                <tr class="border-b border-gray-200 hover:bg-gray-50 transition cursor-pointer group"
                    @click="showModal = true; detailItem = {{ json_encode($item) }}">
                    
                    {{-- TANGGAL --}}
                    <td class="py-3 px-6 whitespace-nowrap text-black font-semibold">
                        {{ \Carbon\Carbon::parse($item->submission_date)->format('d M Y') }}
                    </td>

                    {{-- ID REQUEST (Warna Merah/Hijau Tebal) --}}
                    <td class="py-3 px-6 font-bold {{ $idColorClass }}">
                        {{ $item->request_id }}
                    </td>

                    {{-- TOKO --}}
                    <td class="py-3 px-6 font-bold uppercase text-black">
                        {{ $item->nama_toko }}
                    </td>

                    {{-- AREA (Background Warna, Teks Hitam) --}}
                    <td class="py-3 px-6 text-center">
                        <span class="{{ $areaBgClass }} text-black py-1 px-3 rounded text-xs font-bold block w-full border border-gray-300">
                            {{ $item->area_sales }}
                        </span>
                    </td>

                    {{-- BRAND --}}
                    <td class="py-3 px-6 text-black">{{ $item->brand }}</td>

                    {{-- TOOLS --}}
                    <td class="py-3 px-6 text-black">{{ $item->jenis_tools_branding }}</td>

                    {{-- QTY --}}
                    <td class="py-3 px-6 text-center font-bold text-black">{{ $item->qty_tools }}</td>
                    
                    {{-- AKSI --}}
                    <td class="py-3 px-6 text-center flex justify-center gap-2" @click.stop>
                        
                        {{-- TOMBOL PROSES (Kuning) --}}
                        <button type="button" 
                                @click="showProcessModal = true; processItem = {{ json_encode($item) }}"
                                class="w-8 h-8 rounded bg-yellow-500 hover:bg-yellow-600 text-white flex items-center justify-center shadow transition transform hover:scale-105" 
                                title="Proses / Update Status">
                            <i class="fas fa-pencil-alt"></i>
                        </button>

                        {{-- TOMBOL DELETE (Merah) --}}
                        <form action="{{ route('admin.request.destroy', ['region' => $reg, 'id' => $item->id]) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded bg-red-600 hover:bg-red-700 text-white flex items-center justify-center shadow transition transform hover:scale-105" title="Hapus Data">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="py-10 text-center text-gray-500">
                        <i class="fas fa-folder-open text-4xl mb-3 block text-gray-300"></i>
                        <span class="font-bold">Belum ada data request.</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>