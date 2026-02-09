@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-4 h-[calc(100vh-80px)] flex flex-col">
    
    {{-- =========================================
         1. HEADER PAGE
         ========================================= --}}
    <div class="flex justify-between items-center mb-3">
        <h1 class="text-xl font-bold text-gray-800">Master Data Controller</h1>
        
        {{-- Filter (Di Pojok Kanan Atas) --}}
        <form action="{{ route('admin.master.index') }}" method="GET" class="flex items-center bg-white border border-gray-300 rounded px-2 py-1 shadow-sm">
            <span class="text-[10px] font-bold text-gray-500 mr-2 uppercase"><i class="fas fa-filter mr-1"></i>Filter:</span>
            <select name="filter_area" onchange="this.form.submit()" class="text-xs font-bold text-gray-700 bg-transparent outline-none cursor-pointer border-none focus:ring-0 py-0">
                <option value="">-- SEMUA AREA --</option>
                @foreach($listArea as $areaCode)
                    <option value="{{ $areaCode }}" {{ $filter == $areaCode ? 'selected' : '' }}>
                        AREA {{ $areaCode }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- =========================================
         2. FORM INPUT (MODEL CARD COMPACT)
         ========================================= --}}
    {{-- Menggunakan style Card (Border Merah Kiri) tapi isinya satu baris --}}
    <div class="bg-white rounded shadow-sm border-l-4 border-red-600 p-3 mb-4">
        <form action="{{ route('admin.master.store') }}" method="POST" class="flex flex-col md:flex-row gap-3 items-center">
            @csrf
            
            {{-- Judul Kecil di Kiri --}}
            <div class="md:w-auto w-full border-b md:border-b-0 md:border-r border-gray-200 pb-2 md:pb-0 md:pr-3 mr-1">
                <h2 class="text-sm font-bold text-gray-700 uppercase leading-none">Input<br><span class="text-red-600">Data Baru</span></h2>
            </div>

            {{-- 1. Pilih Tipe --}}
            <div class="w-full md:w-1/6">
                <select name="type" id="typeSelect" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-red-500 bg-gray-50" required onchange="toggleArea()">
                    <option value="SALES">Nama Sales</option>
                    <option value="SPV">Nama SPV</option>
                    <option value="TOOL">Tools Branding</option>
                </select>
            </div>

            {{-- 2. Pilih Area --}}
            <div class="w-full md:w-1/6" id="areaInputDiv">
                <select name="area_input" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-red-500 bg-gray-50">
                    <optgroup label="Pilih Area">
                        <option value="JT">JT</option>
                        <option value="DK">DK</option>
                        <option value="LP">LP</option>
                        <option value="JB">JB</option>
                        <option value="JR">JR</option>
                    </optgroup>
                </select>
            </div>

            {{-- 3. Input Nama --}}
            <div class="w-full md:flex-grow">
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-1.5 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-red-500 uppercase placeholder-gray-400" required placeholder="KETIK NAMA DISINI...">
            </div>

            {{-- 4. Tombol Simpan --}}
            <div class="w-full md:w-auto">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 px-4 rounded text-xs shadow transition w-full flex items-center justify-center">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </button>
            </div>
        </form>
    </div>

    {{-- =========================================
         3. TABEL DATA (GRID 3 KOLOM - FULL HEIGHT)
         ========================================= --}}
    <div class="flex-grow grid grid-cols-1 md:grid-cols-3 gap-4 overflow-hidden min-h-0">
        
        {{-- KOLOM SALES --}}
        <div class="bg-white rounded shadow-sm border border-gray-200 flex flex-col h-full overflow-hidden">
            {{-- Header Tabel --}}
            <div class="px-3 py-2 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div class="flex items-center text-blue-600">
                    <i class="fas fa-user-tie mr-2"></i>
                    <h3 class="font-bold text-xs uppercase">Daftar Sales</h3>
                </div>
                <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold">{{ count($sales) }}</span>
            </div>
            {{-- Sub-Header Kolom --}}
            <div class="bg-white px-3 py-1.5 border-b border-gray-100 grid grid-cols-12 gap-2 text-[10px] font-bold text-gray-400 uppercase">
                <div class="col-span-2">Area</div>
                <div class="col-span-8">Nama</div>
                <div class="col-span-2 text-right">#</div>
            </div>
            {{-- Isi Tabel --}}
            <div class="overflow-y-auto flex-grow custom-scroll bg-white">
                @forelse($sales as $item)
                <div class="px-3 py-2 border-b border-gray-50 hover:bg-blue-50 transition grid grid-cols-12 gap-2 items-center group">
                    <div class="col-span-2 font-bold text-blue-600 text-[11px]">{{ $item->area }}</div>
                    <div class="col-span-8 font-medium text-gray-700 text-[11px] truncate">{{ $item->name }}</div>
                    <div class="col-span-2 text-right">
                        <form action="{{ route('admin.master.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus Sales {{ $item->name }}?')" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-gray-300 hover:text-red-500 transition"><i class="fas fa-trash-alt text-[10px]"></i></button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-400 italic text-xs">Data kosong.</div>
                @endforelse
            </div>
        </div>

        {{-- KOLOM SPV --}}
        <div class="bg-white rounded shadow-sm border border-gray-200 flex flex-col h-full overflow-hidden">
            <div class="px-3 py-2 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div class="flex items-center text-green-600">
                    <i class="fas fa-user-secret mr-2"></i>
                    <h3 class="font-bold text-xs uppercase">Daftar SPV</h3>
                </div>
                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold">{{ count($spvs) }}</span>
            </div>
            <div class="bg-white px-3 py-1.5 border-b border-gray-100 grid grid-cols-12 gap-2 text-[10px] font-bold text-gray-400 uppercase">
                <div class="col-span-2">Area</div>
                <div class="col-span-8">Nama</div>
                <div class="col-span-2 text-right">#</div>
            </div>
            <div class="overflow-y-auto flex-grow custom-scroll bg-white">
                @forelse($spvs as $item)
                <div class="px-3 py-2 border-b border-gray-50 hover:bg-green-50 transition grid grid-cols-12 gap-2 items-center group">
                    <div class="col-span-2 font-bold text-green-600 text-[11px]">{{ $item->area }}</div>
                    <div class="col-span-8 font-medium text-gray-700 text-[11px] truncate">{{ $item->name }}</div>
                    <div class="col-span-2 text-right">
                        <form action="{{ route('admin.master.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus SPV {{ $item->name }}?')" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-gray-300 hover:text-red-500 transition"><i class="fas fa-trash-alt text-[10px]"></i></button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-400 italic text-xs">Data kosong.</div>
                @endforelse
            </div>
        </div>

        {{-- KOLOM TOOLS --}}
        <div class="bg-white rounded shadow-sm border border-gray-200 flex flex-col h-full overflow-hidden">
            <div class="px-3 py-2 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div class="flex items-center text-orange-600">
                    <i class="fas fa-tools mr-2"></i>
                    <h3 class="font-bold text-xs uppercase">Daftar Tools</h3>
                </div>
                <span class="text-[10px] bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full font-bold">{{ count($tools) }}</span>
            </div>
            <div class="bg-white px-3 py-1.5 border-b border-gray-100 grid grid-cols-12 gap-2 text-[10px] font-bold text-gray-400 uppercase">
                <div class="col-span-10">Nama Tool</div>
                <div class="col-span-2 text-right">#</div>
            </div>
            <div class="overflow-y-auto flex-grow custom-scroll bg-white">
                @forelse($tools as $item)
                <div class="px-3 py-2 border-b border-gray-50 hover:bg-orange-50 transition grid grid-cols-12 gap-2 items-center group">
                    <div class="col-span-10 font-medium text-gray-700 text-[11px] truncate">{{ $item->name }}</div>
                    <div class="col-span-2 text-right">
                        <form action="{{ route('admin.master.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus Tool {{ $item->name }}?')" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-gray-300 hover:text-red-500 transition"><i class="fas fa-trash-alt text-[10px]"></i></button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-400 italic text-xs">Data kosong.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    function toggleArea() {
        const type = document.getElementById('typeSelect').value;
        const areaDiv = document.getElementById('areaInputDiv');
        if (type === 'TOOL') {
            areaDiv.style.visibility = 'hidden'; 
        } else {
            areaDiv.style.visibility = 'visible';
        }
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        toggleArea();
    });
</script>

<style>
    /* Scrollbar Super Tipis */
    .custom-scroll::-webkit-scrollbar { width: 3px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
</style>
@endsection