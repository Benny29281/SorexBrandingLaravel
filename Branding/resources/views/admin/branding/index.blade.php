<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Request Branding - Sorex Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" href="bar2.png" type="image/png">

    <style>
        .bg-sidebar { background-color: #3d3d3d; }
        .bg-sidebar-active { background-color: #e02222; }
        .bg-header { background-color: #2b2b2b; }
        body { background-color: #f3f4f6; font-family: 'Segoe UI', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Scrollbar Tipis untuk Modal */
        .modal-scroll::-webkit-scrollbar { width: 6px; }
        .modal-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        .modal-scroll::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans">

    @include('layouts.sidebar_admin')

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        @include('layouts.header_admin')

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6" x-data="{ 
            activeTab: '{{ request('tab') == 'regional2' ? 'regional2' : 'regional1' }}',
            showModal: false,        
            showProcessModal: false, 
            detailItem: {},
            processItem: {}
        }">
            
            {{-- HEADER HALAMAN & COUNTER --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-700">Data Request Branding</h1>
                    <p class="text-xs text-gray-500 mt-1">Kelola semua permintaan branding dari Toko & Sales.</p>
                </div>
                
                {{-- INFO TOTAL DATA DINAMIS --}}
                <div class="bg-white px-4 py-2 rounded-md shadow-sm border border-gray-200 min-w-[160px] text-right">
                    <div class="flex items-center gap-3 justify-end">
                        <template x-if="activeTab === 'regional1'">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Total Data:</span>
                                <div class="flex items-baseline gap-1">
                                    <strong class="text-lg font-bold text-red-600">{{ $data1->total() }}</strong> 
                                    <span class="text-[10px] text-gray-400">Request</span>
                                </div>
                            </div>
                        </template>

                        <template x-if="activeTab === 'regional2'">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Total Data:</span>
                                <div class="flex items-baseline gap-1">
                                    <strong class="text-lg font-bold text-green-600">{{ $data2->total() }}</strong> 
                                    <span class="text-[10px] text-gray-400">Request</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- FORM PENCARIAN & FILTER --}}
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200 mb-6">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-search text-sm"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               class="w-full py-2 pl-9 pr-4 text-sm text-gray-700 bg-gray-50 border border-gray-300 rounded focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition" 
                               placeholder="Cari Request ID (RB...), Nama Toko, Sales, atau Area...">
                    </div>

                    <input type="hidden" name="tab" :value="activeTab">

                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded transition shadow-sm">
                        Cari
                    </button>

                    @if(request('search'))
                        <a href="{{ url()->current() }}?tab={{ request('tab', 'regional1') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-bold rounded transition flex items-center">
                            <i class="fas fa-times mr-1"></i> Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- TOMBOL TAB --}}
            <div class="flex space-x-4 border-b border-gray-300 mb-6">
                <button @click="activeTab = 'regional1'" :class="activeTab === 'regional1' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-2 px-4 border-b-2 font-medium text-sm transition focus:outline-none"><i class="fas fa-map-marker-alt mr-2"></i> Regional 1 (JT, DK, LP)</button>
                <button @click="activeTab = 'regional2'" :class="activeTab === 'regional2' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-2 px-4 border-b-2 font-medium text-sm transition focus:outline-none"><i class="fas fa-map-marker-alt mr-2"></i> Regional 2 (JB & JR)</button>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm text-sm">{{ session('success') }}</div>
            @endif

            {{-- TABEL REGIONAL 1 --}}
            <div x-show="activeTab === 'regional1'" x-transition.opacity>
                <div class="bg-white rounded-lg shadow border-t-4 border-red-500 overflow-hidden">
                    @include('admin.branding.partials.table', ['data' => $data1, 'color' => 'red', 'reg' => 'reg1'])
                    <div class="px-5 py-4 bg-gray-50 border-t">
                        {{ $data1->appends(['page_reg2' => $data2->currentPage(), 'tab' => 'regional1', 'search' => request('search')])->links() }}
                    </div>
                </div>
            </div>

            {{-- TABEL REGIONAL 2 --}}
            <div x-show="activeTab === 'regional2'" x-cloak x-transition.opacity>
                <div class="bg-white rounded-lg shadow border-t-4 border-green-500 overflow-hidden">
                    @include('admin.branding.partials.table', ['data' => $data2, 'color' => 'green', 'reg' => 'reg2'])
                    <div class="px-5 py-4 bg-gray-50 border-t">
                        {{ $data2->appends(['page_reg1' => $data1->currentPage(), 'tab' => 'regional2', 'search' => request('search')])->links() }}
                    </div>
                </div>
            </div>

            {{-- =======================================================
                 MODAL PROSES (OPTIMIZED COMPACT)
                 ======================================================= --}}
            <div x-show="showProcessModal" style="display: none;" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" @click="showProcessModal = false"><div class="absolute inset-0 bg-gray-900 opacity-80"></div></div>
                    
                    {{-- UKURAN LEBIH LEBAR (7XL) AGAR MUAT BANYAK KOLOM --}}
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-7xl w-full">
                        
                        {{-- Header Modal --}}
                        <div class="bg-yellow-500 px-6 py-3 flex justify-between items-center sticky top-0 z-10">
                            <h3 class="text-lg font-bold text-white flex items-center"><i class="fas fa-pencil-alt mr-3"></i> Proses Request: <span x-text="processItem.request_id" class="ml-2 bg-white text-yellow-600 px-2 rounded text-sm"></span></h3>
                            <button @click="showProcessModal = false" class="text-white hover:text-gray-200 text-xl"><i class="fas fa-times"></i></button>
                        </div>

                        {{-- Form Proses --}}
                        <form action="{{ route('admin.request.store_status') }}" method="POST" class="flex flex-col md:flex-row h-[75vh]"> {{-- Tinggi dikurangi dikit --}}
                            @csrf
                            <input type="hidden" name="request_id" :value="processItem.request_id">
                            <input type="hidden" name="jenis_permintaan" :value="processItem.jenis_permintaan">

                            {{-- KOLOM KIRI: DATA ASLI --}}
                            <div class="w-full md:w-5/12 bg-gray-50 p-5 overflow-y-auto modal-scroll border-r border-gray-200">
                                <h4 class="font-bold text-gray-700 text-sm mb-3 pb-2 border-b border-gray-300 flex items-center"><i class="fas fa-file-alt mr-2 text-gray-500"></i> DATA REQUEST</h4>
                                
                                <div class="bg-white p-4 rounded shadow-sm border border-gray-200 text-sm space-y-3">
                                    {{-- Grid Data --}}
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-3">
                                        <div><label class="text-[10px] text-gray-400 uppercase font-bold">Request ID</label><div class="font-bold text-gray-800" x-text="processItem.request_id"></div></div>
                                        <div><label class="text-[10px] text-gray-400 uppercase font-bold">Tanggal</label><div class="font-bold text-gray-800" x-text="processItem.submission_date"></div></div>
                                        <div><label class="text-[10px] text-gray-400 uppercase font-bold">Toko</label><div class="font-bold text-gray-800" x-text="processItem.nama_toko"></div></div>
                                        <div><label class="text-[10px] text-gray-400 uppercase font-bold">Area</label><div class="font-bold text-blue-600" x-text="processItem.area_sales"></div></div>
                                        
                                        {{-- INPUT VIA (DIPINDAHKAN KESINI) --}}
                                        <div class="col-span-2">
                                            <label class="block font-bold text-gray-700 text-[10px] uppercase mb-1">VIA REQUEST</label>
                                            <input type="text" name="via" :value="processItem.via || 'WEB'" class="w-full border rounded px-2 py-1 bg-white text-gray-800 font-bold focus:ring-1 focus:ring-yellow-500 outline-none">
                                        </div>

                                        {{-- Highlight Tools --}}
                                        <div class="col-span-2 bg-yellow-50 p-2 rounded border border-yellow-200">
                                            <label class="text-[10px] text-gray-500 uppercase font-bold">Tools Request</label>
                                            <div class="font-extrabold text-red-600 text-base" x-text="processItem.jenis_tools_branding"></div>
                                        </div>

                                        <div><label class="text-[10px] text-gray-400 uppercase font-bold">Qty</label><div class="font-bold text-gray-800" x-text="processItem.qty_tools"></div></div>
                                        
                                        {{-- UKURAN (BISA DIEDIT) --}}
                                        <div>
                                            <label class="text-[10px] text-gray-400 uppercase font-bold flex items-center gap-1">
                                                Ukuran (Edit) <i class="fas fa-pen text-[9px] text-blue-500"></i>
                                            </label>
                                            <input type="text" 
                                                   name="ukuran_fix" 
                                                   :value="processItem.ukuran_fix || processItem.ukuran_tools_branding"
                                                   class="w-full border border-gray-300 rounded px-2 py-1 text-sm font-bold text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white shadow-sm"
                                                   placeholder="Input ukuran...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: UPDATE STATUS (GRID 2 KOLOM) --}}
                            <div class="w-full md:w-7/12 p-5 overflow-y-auto modal-scroll bg-white flex flex-col">
                                <h4 class="font-bold text-gray-700 text-sm mb-3 pb-2 border-b border-yellow-400 flex items-center"><i class="fas fa-edit mr-2 text-yellow-600"></i> UPDATE STATUS BRANDING</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs flex-grow content-start">

                                {{-- Design Phase --}}
                                <div class="col-span-1 md:col-span-2 bg-red-300 p-2 rounded border border-red-300 mb-1">
                                    <label class="block font-bold text-black-600 mb-2">TAHAP DESIGN</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        {{-- Pembuatan & Konfirmasi: Bisa diedit Admin & Design --}}
                                        <div>
                                            <label class="block font-bold text-black-600 mb-2">Pembuatan Design</label>
                                            <input type="date" name="pembuatan_design" class="w-full border rounded px-2 py-1.5"
                                                {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                        </div>
                                        <div>
                                            <label class="block font-bold text-black-600 mb-1">Konfirmasi Design</label>
                                            <input type="date" name="konfirmasi_design" class="w-full border rounded px-2 py-1.5"
                                                {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                        </div>

                                        {{-- Kolom di bawah ini: HANYA Admin yang bisa edit, Design cuma bisa liat --}}
                                        {{-- <div>
                                            <label class="block text-gray-600 mb-1">Approve Leader</label>
                                            <input type="date" name="approve_leader" 
                                                class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                        </div>
                                        <div>
                                            <label class="block text-gray-600 mb-1">Approve Toko</label>
                                            <input type="date" name="approve_toko" 
                                                class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                        </div> --}}
                                    </div>
                                </div>
                                
                                <div class="border p-2 rounded border-gray-200">
                                    <label class="block font-bold text-black-600 mb-2 border-b pb-1">Approve</label>
                                <div>
                                            <label class="block font-bold text-black-600 mb-1">Approve Leader</label>
                                            <input type="date" name="approve_leader" 
                                                class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                        </div>
                                        <div>
                                            <label class="block font-bold text-black-600 mb-1">Approve Toko</label>
                                            <input type="date" name="approve_toko" 
                                                class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                        </div>
                                </div>

                                {{-- Vendor Phase --}}
                                <div class="border p-2 rounded border-gray-200">
                                    <label class="block font-bold text-black-600 mb-2 border-b pb-1">VENDOR</label>
                                    <div class="space-y-2">
                                        <div>
                                            <label class="block font-bold text-black-600 mb-1">Tgl Masuk Vendor</label>
                                            <input type="date" name="tanggal_masuk_vendor" 
                                                class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                        </div>
                                        <div>
                                            <label class="block font-bold text-black-600 mb-1">Nama Vendor</label>
                                            <input type="text" name="nama_vendor" 
                                                class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                {{-- Warehouse Phase --}}
                                <div class="border p-2 rounded border-gray-200">
                                    <label class="block font-bold text-black-600 mb-2 border-b pb-1">GUDANG</label>
                                    <div class="space-y-2">
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block font-bold text-black-600 mb-1">SJ Tasya</label>
                                                <input type="date" name="sj_di_terima_tasya" 
                                                    class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                    {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                            </div>
                                            <div>
                                                <label class="block font-bold text-black-600 mb-1">PO Selesai</label>
                                                <input type="date" name="po_selesai_gudang_fr" 
                                                    class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                    {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block font-bold text-black-600 mb-1">Packing</label>
                                                <input type="text" name="packing_barang_fr" 
                                                    class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                    {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                            </div>
                                            <div>
                                                <label class="block font-bold text-black-600 mb-1">Ke Dadap</label>
                                                <input type="date" name="kirim_ke_dadap" 
                                                    class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                    {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block font-bold text-black-600 mb-1">Trm Dadap</label>
                                            <input type="date" name="terima_di_dadap" 
                                                class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Shipping Phase --}}
                                <div class="border p-2 rounded border-gray-200">
                                    <label class="block font-bold text-black-600 mb-2 border-b pb-1">Pengiriman</label>
                                    <div class="space-y-2">
                                        <div>
                                            <label class="block font-bold text-black-600 mb-1">Kirim Ekspedisi</label>
                                            <input type="date" name="kirim_ke_ekspedisi" 
                                                class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2"></div>
                                        <div>
                                            <label class="block font-bold text-black-600 mb-1">Nomor Resi</label>
                                            <input type="text" name="nomor_resi" 
                                                class="w-full border rounded px-2 py-1.5 bg-white {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3"></div>
                                        <div>
                                            <label class="block font-bold text-black-600 mb-1">Konf. Terima</label>
                                            <input type="date" name="konfirmasi_penerimaan" 
                                                class="w-full border rounded px-2 py-1.5 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}"
                                                {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                {{-- Footer Tombol (Batal & Simpan) --}}
                                <div class="mt-4 pt-4 border-t sticky bottom-0 bg-white flex gap-3">
                                    <button type="button" @click="showProcessModal = false" class="w-1/3 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2.5 px-4 rounded transition">
                                        <i class="fas fa-times mr-1"></i> Batal
                                    </button>
                                    <button type="submit" class="w-2/3 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2.5 px-4 rounded transition shadow-md" onclick="return confirm('Apakah Anda yakin ingin menyimpan perubahan data request ini?')">
                                        <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MODAL DETAIL (VIEW ONLY) - OPTIMIZED COMPACT --}}
            <div x-show="showModal" style="display: none;" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" @click="showModal = false"><div class="absolute inset-0 bg-gray-900 opacity-75"></div></div>
                    
                    {{-- UBAH UKURAN DISINI: sm:max-w-5xl (Lebih Lebar) --}}
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl w-full">
                        
                        {{-- Header Compact --}}
                        <div class="bg-gray-800 px-6 py-3 flex justify-between items-center sticky top-0 z-10">
                            <h3 class="text-lg font-medium text-white">Detail Request: <span x-text="detailItem.request_id" class="text-yellow-400 font-bold"></span></h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-white"><i class="fas fa-times text-lg"></i></button>
                        </div>

                        <div class="bg-white p-6">
                            
                            {{-- GRID DATA 4 KOLOM (AGAR TIDAK PANJANG KE BAWAH) --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-3 text-sm mb-4 border-b border-gray-100 pb-4">
                                
                                {{-- Baris 1 --}}
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Tanggal</p><p class="font-bold text-gray-800" x-text="detailItem.submission_date"></p></div>
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Email</p><p class="font-bold text-gray-800 truncate" x-text="detailItem.email_address"></p></div>
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Toko</p><p class="font-bold text-gray-800 truncate" x-text="detailItem.nama_toko"></p></div>
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Area</p><p class="font-bold text-gray-800" x-text="detailItem.area_sales"></p></div>

                                {{-- Baris 2 --}}
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Sales</p><p class="font-bold text-gray-800" x-text="detailItem.nama_sales"></p></div>
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">SPV</p><p class="font-bold text-gray-800" x-text="detailItem.nama_spv"></p></div>
                                <div class="col-span-2"><p class="text-[10px] text-gray-400 font-bold uppercase">Alamat</p><p class="font-bold text-gray-800 truncate" x-text="detailItem.lokasi || '-'"></p></div>

                                {{-- Divider Tipis --}}
                                <div class="col-span-2 md:col-span-4 border-t border-gray-100 my-1"></div>

                                {{-- Baris 3 --}}
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Brand</p><p class="font-bold text-blue-600" x-text="detailItem.brand"></p></div>
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Tipe</p><p class="font-bold text-gray-800" x-text="detailItem.jenis_permintaan"></p></div>
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Tools</p><p class="font-extrabold text-red-600 truncate" x-text="detailItem.jenis_tools_branding"></p></div>
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Qty</p><p class="font-bold text-gray-800" x-text="detailItem.qty_tools"></p></div>

                                {{-- Baris 4 --}}
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Ukuran Asli</p><p class="font-bold text-gray-800" x-text="detailItem.ukuran_tools_branding || '-'"></p></div>
                                <div><p class="text-[10px] text-gray-400 font-bold uppercase">Kirim</p><p class="font-bold text-gray-800" x-text="detailItem.pengiriman || '-'"></p></div>
                                <div class="col-span-2 bg-gray-50 px-2 py-1 rounded border border-gray-200">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Ket. Tambahan</p>
                                    <p class="italic text-gray-700 text-xs truncate" x-text="detailItem.keterangan_tambahan || '-'"></p>
                                </div>
                            </div>

                            {{-- LAYOUT FOTO SEJAJAR (2 KOLOM) --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                {{-- KOLOM KIRI: FOTO AREA --}}
                                <div>
                                    <h4 class="text-xs text-gray-500 font-bold uppercase mb-2 flex items-center"><i class="fas fa-map-marker-alt mr-1"></i> Foto Area</h4>
                                    <div class="flex flex-wrap gap-2 p-2 bg-gray-50 rounded border border-gray-100 min-h-[80px]">
                                        <template x-for="img in (detailItem.photo_area_pemasangan ? JSON.parse(detailItem.photo_area_pemasangan) : [])">
                                            <a :href="'/storage/' + img" target="_blank" class="block w-16 h-16 rounded overflow-hidden border border-gray-300 hover:opacity-75 transition">
                                                <img :src="'/storage/' + img" class="w-full h-full object-cover" alt="Area">
                                            </a>
                                        </template>
                                        <span x-show="!detailItem.photo_area_pemasangan || JSON.parse(detailItem.photo_area_pemasangan).length === 0" class="text-xs text-gray-400 italic m-auto">Tidak ada foto.</span>
                                    </div>
                                </div>

                                {{-- KOLOM KANAN: FOTO SUGEST --}}
                                <div>
                                    <h4 class="text-xs text-gray-500 font-bold uppercase mb-2 flex items-center"><i class="fas fa-lightbulb mr-1"></i> Foto Referensi</h4>
                                    <div class="flex flex-wrap gap-2 p-2 bg-gray-50 rounded border border-gray-100 min-h-[80px]">
                                        <template x-for="img in (detailItem.photo_sugest_design ? JSON.parse(detailItem.photo_sugest_design) : [])">
                                            <a :href="'/storage/' + img" target="_blank" class="block w-16 h-16 rounded overflow-hidden border border-gray-300 hover:opacity-75 transition">
                                                <img :src="'/storage/' + img" class="w-full h-full object-cover" alt="Sugest">
                                            </a>
                                        </template>
                                        <span x-show="!detailItem.photo_sugest_design || JSON.parse(detailItem.photo_sugest_design).length === 0" class="text-xs text-gray-400 italic m-auto">Tidak ada foto.</span>
                                    </div>
                                </div>

                            </div>

                            {{-- Footer Tombol --}}
                            <div class="mt-4 text-right">
                                <button type="button" class="px-5 py-1.5 bg-gray-600 text-white text-sm font-bold rounded hover:bg-gray-700 transition" @click="showModal = false">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    @include('components.keep-alive')
</body>
</html>