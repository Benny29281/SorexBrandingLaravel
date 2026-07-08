<div class="overflow-x-auto w-full border border-gray-300 rounded-lg shadow-sm bg-white">
    <table class="min-w-full leading-normal font-sans">
        {{-- HEADER --}}
        <thead>
            <tr class="bg-gray-900 text-white uppercase text-xs font-bold tracking-wider">
                <th class="py-3 px-6 text-left border-b border-gray-700">Tanggal</th>
                <th class="py-3 px-6 text-left border-b border-gray-700">ID</th>
                <th class="py-3 px-6 text-left border-b border-gray-700">Nama Sales</th>
                <th class="py-3 px-6 text-center border-b border-gray-700">Nama Toko</th>
                <th class="py-3 px-6 text-center border-b border-gray-700">Area</th>
                <th class="py-3 px-6 text-left border-b border-gray-700">Brand</th>
                <th class="py-3 px-6 text-left border-b border-gray-700">Permintaan</th>
                <th class="py-3 px-6 text-center border-b border-gray-700">Actions</th>
            </tr>
        </thead>
        
        <tbody class="text-gray-900 text-sm font-medium">
            @forelse($data as $item)
                @php
                    // LOGIKA WARNA ID
                    // LOGIKA WARNA ID berdasarkan siapa yang terakhir update
                    // Ganti angka ID sesuai user asli di database kamu
                    $userColorMap = [
                        26 => 'bg-blue-200 text-blue-800',    // Design User A
                        27 => 'bg-green-200 text-green-800',  // Design User B
                        1 => 'bg-pink-200 text-pink-800',    // Admin
                        5 => 'bg-yellow-200 text-yellow-800', // tambah user lain sesuai kebutuhan
                    ];

                    $idColorClass = isset($item->updated_by) && isset($userColorMap[$item->updated_by])
                        ? $userColorMap[$item->updated_by]
                        : 'bg-gray-100 text-red-500'; // belum pernah diupdate

                    // LOGIKA WARNA AREA
                    $areaBgClass = 'bg-gray-100';
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
                        <div class="flex items-center gap-2">
                            {{ \Carbon\Carbon::parse($item->submission_date)->format('d M Y') }}

                            @if(\Carbon\Carbon::parse($item->submission_date)->isToday())
                                <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded animate-pulse shadow-sm">
                                    NEW
                                </span>
                            @endif
                        </div>
                    </td>

                    {{-- ID REQUEST --}}
                    {{-- ID REQUEST --}}
                    <td class="py-3 px-6">
                        <span class="px-2.5 py-1 rounded font-bold text-xs {{ $idColorClass }}">
                            {{ $item->request_id }}
                        </span>
                    </td>

                    {{-- NAME SALES (FIXED DIV TAG) --}}
                    <td class="py-3 px-6 font-bold uppercase text-black">
                        <div class="flex items-center">
                            <i class="fas fa-user-tie mr-1 text-gray-500"></i>: {{ $item->nama_sales ?? '-' }}
                        </div>
                    </td>

                    {{-- TOKO --}}
                    <td class="py-3 px-6 font-bold uppercase text-black text-center">
                        {{ $item->nama_toko }}
                    </td>

                    {{-- AREA --}}
                    <td class="py-3 px-6 text-center">
                        <span class="{{ $areaBgClass }} text-black py-1 px-3 rounded text-xs font-bold block w-full border border-gray-300">
                            {{ $item->area_sales }}
                        </span>
                    </td>

                    {{-- BRAND --}}
                    <td class="py-3 px-6 text-black">{{ $item->brand }}</td>

                    {{-- PERMINTAAN (TOOLS + UKURAN + LOG TIME) --}}
                    <td class="py-3 px-6 bg-green-50/30">
                         <div class="text-sm font-extrabold text-gray-800 uppercase">
                            {{ $item->jenis_tools_branding }}
                        </div>

                        {{-- Tampilkan jam jika sudah ada update status --}}
                        @if($item->updated_at)
                            <div class="text-[9px] text-blue-500 mt-1 font-bold italic">
                                <i class="fas fa-clock mr-1"></i>Updated: {{ \Carbon\Carbon::parse($item->updated_at)->format('H:i') }}
                            </div>
                        @endif
                    </td>
                    
                    {{-- AKSI --}}
                    <td class="py-3 px-6 text-center flex justify-center gap-2" @click.stop>
                        <button type="button" 
                                @click="showProcessModal = true; processItem = {{ json_encode($item) }}"
                                class="w-8 h-8 rounded bg-yellow-500 hover:bg-yellow-600 text-white flex items-center justify-center shadow transition transform hover:scale-105" 
                                title="Proses / Update Status">
                            <i class="fas fa-pencil-alt"></i>
                        </button>

                        @if(auth()->user()->regional !== 'Design')
                            <form action="{{ route('admin.request.destroy', ['region' => $reg, 'id' => $item->id]) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded bg-red-600 hover:bg-red-700 text-white flex items-center justify-center shadow transition transform hover:scale-105" title="Hapus Data">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
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