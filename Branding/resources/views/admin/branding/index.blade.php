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
        
        .modal-scroll::-webkit-scrollbar { width: 8px; }
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
            
            {{-- HEADER HALAMAN & COUNTER (UKURAN DIPERKECIL) --}}
            <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-700">Data Request Branding</h1>
                    <p class="text-xs text-gray-500 mt-1">Kelola semua permintaan branding dari Toko & Sales.</p>
                </div>
                
                {{-- INFO TOTAL DATA DINAMIS (REVISI UKURAN) --}}
                <div class="bg-white px-4 py-2 rounded-md shadow-sm border border-gray-200 min-w-[160px] text-right">
                    
                    <div class="flex items-center gap-3">
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

            {{-- ================= FORM PENCARIAN & FILTER ================= --}}
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

            {{-- MODAL PROSES (UPDATE) --}}
            <div x-show="showProcessModal" style="display: none;" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" @click="showProcessModal = false"><div class="absolute inset-0 bg-gray-900 opacity-80"></div></div>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl w-full">
                        <div class="bg-yellow-500 px-6 py-4 flex justify-between items-center">
                            <h3 class="text-xl font-bold text-white flex items-center"><i class="fas fa-pencil-alt mr-3"></i> Proses Request: <span x-text="processItem.request_id" class="ml-2 bg-white text-yellow-600 px-2 rounded text-sm"></span></h3>
                            <button @click="showProcessModal = false" class="text-white hover:text-gray-200 text-xl"><i class="fas fa-times"></i></button>
                        </div>
                        <form action="{{ route('admin.request.store_status') }}" method="POST" class="flex flex-col md:flex-row h-[80vh]">
                            @csrf
                            <input type="hidden" name="request_id" :value="processItem.request_id">
                            <input type="hidden" name="jenis_permintaan" :value="processItem.jenis_permintaan">

                            {{-- KOLOM KIRI --}}
                            <div class="w-full md:w-1/2 bg-gray-50 p-6 overflow-y-auto modal-scroll border-r border-gray-200">
                                <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b-2 border-gray-300 flex items-center"><i class="fas fa-file-alt mr-2 text-gray-500"></i> DATA ASLI REQUEST</h4>
                                <div class="space-y-4">
                                    <div class="bg-white p-4 rounded shadow-sm border border-gray-200">
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div><label class="text-xs text-gray-400 uppercase font-bold">Request ID</label><div class="font-bold text-gray-800" x-text="processItem.request_id"></div></div>
                                            <div><label class="text-xs text-gray-400 uppercase font-bold">Tanggal</label><div class="font-bold text-gray-800" x-text="processItem.submission_date"></div></div>
                                            <div><label class="text-xs text-gray-400 uppercase font-bold">Toko</label><div class="font-bold text-gray-800" x-text="processItem.nama_toko"></div></div>
                                            <div><label class="text-xs text-gray-400 uppercase font-bold">Area</label><div class="font-bold text-blue-600" x-text="processItem.area_sales"></div></div>
                                            <div><label class="text-xs text-gray-400 uppercase font-bold">Sales</label><div class="font-bold text-gray-800" x-text="processItem.nama_sales"></div></div>
                                            <div><label class="text-xs text-gray-400 uppercase font-bold">Brand</label><div class="font-bold text-gray-800" x-text="processItem.brand"></div></div>
                                            <div class="col-span-2"><label class="text-xs text-gray-400 uppercase font-bold">Tools</label><div class="font-bold text-red-600" x-text="processItem.jenis_tools_branding"></div></div>
                                            <div><label class="text-xs text-gray-400 uppercase font-bold">Qty</label><div class="font-bold text-gray-800" x-text="processItem.qty_tools"></div></div>
                                            <div><label class="text-xs text-gray-400 uppercase font-bold">Ukuran</label><div class="font-bold text-gray-800" x-text="processItem.ukuran_tools_branding || '-'"></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="w-full md:w-1/2 p-6 overflow-y-auto modal-scroll bg-white">
                                <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b-2 border-yellow-400 flex items-center"><i class="fas fa-edit mr-2 text-yellow-600"></i> UPDATE STATUS BRANDING</h4>
                                <div class="grid grid-cols-1 gap-4 text-sm">
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded"><label class="block font-bold text-gray-700 text-xs mb-1">VIA</label><input type="text" name="via" value="WEB" readonly class="w-full border rounded px-2 py-1 bg-gray-200 text-gray-600 cursor-not-allowed"></div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Pembuatan Design</label><input type="date" name="pembuatan_design" class="w-full border rounded px-2 py-1"></div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div><label class="block font-bold text-gray-700 text-xs">Approve Leader</label><input type="date" name="approve_leader" class="w-full border rounded px-2 py-1"></div>
                                        <div><label class="block font-bold text-gray-700 text-xs">Approve Toko</label><input type="date" name="approve_toko" class="w-full border rounded px-2 py-1"></div>
                                    </div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Konfirmasi Design</label><input type="date" name="konfirmasi_design" class="w-full border rounded px-2 py-1"></div>
                                    <hr class="border-gray-200 my-2">
                                    <div><label class="block font-bold text-gray-700 text-xs">Tgl Masuk Vendor</label><input type="date" name="tanggal_masuk_vendor" class="w-full border rounded px-2 py-1"></div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Nama Vendor</label><input type="text" name="nama_vendor" class="w-full border rounded px-2 py-1"></div>
                                    <hr class="border-gray-200 my-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div><label class="block font-bold text-gray-700 text-xs">SJ Diterima Tasya</label><input type="date" name="sj_di_terima_tasya" class="w-full border rounded px-2 py-1"></div>
                                        <div><label class="block font-bold text-gray-700 text-xs">PO Selesai Gudang FR</label><input type="date" name="po_selesai_gudang_fr" class="w-full border rounded px-2 py-1"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div><label class="block font-bold text-gray-700 text-xs">Packing Barang FR</label><input type="text" name="packing_barang_fr" class="w-full border rounded px-2 py-1"></div>
                                        <div><label class="block font-bold text-gray-700 text-xs">Kirim ke Dadap</label><input type="date" name="kirim_ke_dadap" class="w-full border rounded px-2 py-1"></div>
                                    </div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Barang Diterima Dadap</label><input type="date" name="terima_di_dadap" class="w-full border rounded px-2 py-1"></div>
                                    <hr class="border-gray-200 my-2">
                                    <div><label class="block font-bold text-gray-700 text-xs">Kirim ke Ekspedisi</label><input type="date" name="kirim_ke_ekspedisi" class="w-full border rounded px-2 py-1"></div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Nomor Resi</label><input type="text" name="nomor_resi" class="w-full border rounded px-2 py-1 bg-green-50"></div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Konf. Penerimaan Barang</label><input type="date" name="konfirmasi_penerimaan" class="w-full border rounded px-2 py-1"></div>
                                </div>
                                <div class="mt-6 pt-4 border-t sticky bottom-0 bg-white">
                                    <button type="submit" class="bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-600 transition" onclick="return confirm('Simpan Data Baru?')"><i class="fas fa-save mr-2"></i> SIMPAN STATUS</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MODAL DETAIL --}}
            <div x-show="showModal" style="display: none;" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" @click="showModal = false"><div class="absolute inset-0 bg-gray-900 opacity-75"></div></div>
                    
                    {{-- Container Modal --}}
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full">
                        
                        {{-- Header Modal --}}
                        <div class="bg-gray-800 px-6 py-4 flex justify-between items-center sticky top-0 z-10">
                            <h3 class="text-xl font-medium text-white">Detail Request: <span x-text="detailItem.request_id" class="text-yellow-400 font-bold"></span></h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
                        </div>

                        {{-- Isi Modal --}}
                        <div class="bg-white modal-scroll max-h-[85vh] overflow-y-auto relative p-6">
                            
                            {{-- Grid Data Teks --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm mb-6">
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Tanggal</p><p class="font-semibold text-gray-800" x-text="detailItem.submission_date"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Email</p><p class="font-semibold text-gray-800" x-text="detailItem.email_address"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Toko</p><p class="font-semibold text-gray-800" x-text="detailItem.nama_toko"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Area</p><p class="font-semibold text-gray-800" x-text="detailItem.area_sales"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Sales</p><p class="font-semibold text-gray-800" x-text="detailItem.nama_sales"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">SPV</p><p class="font-semibold text-gray-800" x-text="detailItem.nama_spv"></p></div>
                                <div class="col-span-2"><p class="text-xs text-gray-500 font-bold uppercase">Alamat</p><p class="font-semibold text-gray-800" x-text="detailItem.lokasi || '-'"></p></div>
                                
                                <div class="col-span-2 border-t border-gray-200 my-1"></div>
                                
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Brand</p><p class="font-semibold text-blue-600" x-text="detailItem.brand"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Tipe</p><p class="font-semibold text-gray-800" x-text="detailItem.jenis_permintaan"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Tools</p><p class="font-bold text-red-600" x-text="detailItem.jenis_tools_branding"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Ukuran</p><p class="font-semibold text-gray-800" x-text="detailItem.ukuran_tools_branding || '-'"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Qty</p><p class="font-semibold text-gray-800" x-text="detailItem.qty_tools"></p></div>
                                <div><p class="text-xs text-gray-500 font-bold uppercase">Kirim</p><p class="font-semibold text-gray-800" x-text="detailItem.pengiriman || '-'"></p></div>
                                <div class="col-span-2 bg-gray-50 p-3 rounded border border-gray-200"><p class="text-xs text-gray-500 font-bold uppercase">Ket. Tambahan</p><p class="italic text-gray-700" x-text="detailItem.keterangan_tambahan || '-'"></p></div>
                            </div>

                            {{-- Bagian Baru: Tampilan Foto --}}
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-gray-800 font-bold mb-4 flex items-center"><i class="fas fa-camera text-gray-500 mr-2"></i> Lampiran Foto</h4>
                                
                                {{-- 1. FOTO AREA PEMASANGAN --}}
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 font-bold uppercase mb-2">Foto Area Pemasangan</p>
                                    <div class="flex flex-wrap gap-2">
                                        {{-- Loop Foto Area --}}
                                        <template x-for="img in (detailItem.photo_area_pemasangan ? JSON.parse(detailItem.photo_area_pemasangan) : [])">
                                            <a :href="'/storage/' + img" target="_blank" class="group relative block w-24 h-24 rounded overflow-hidden border border-gray-300 hover:shadow-lg transition">
                                                <img :src="'/storage/' + img" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" alt="Foto Area">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition"></div>
                                            </a>
                                        </template>
                                        {{-- Pesan Jika Kosong --}}
                                        <span x-show="!detailItem.photo_area_pemasangan || JSON.parse(detailItem.photo_area_pemasangan).length === 0" class="text-xs text-gray-400 italic py-2">Tidak ada foto area.</span>
                                    </div>
                                </div>

                                {{-- 2. FOTO SUGEST DESIGN --}}
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase mb-2">Foto Sugest Design</p>
                                    <div class="flex flex-wrap gap-2">
                                        {{-- Loop Foto Sugest --}}
                                        <template x-for="img in (detailItem.photo_sugest_design ? JSON.parse(detailItem.photo_sugest_design) : [])">
                                            <a :href="'/storage/' + img" target="_blank" class="group relative block w-24 h-24 rounded overflow-hidden border border-gray-300 hover:shadow-lg transition">
                                                <img :src="'/storage/' + img" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" alt="Foto Sugest">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition"></div>
                                            </a>
                                        </template>
                                        {{-- Pesan Jika Kosong --}}
                                        <span x-show="!detailItem.photo_sugest_design || JSON.parse(detailItem.photo_sugest_design).length === 0" class="text-xs text-gray-400 italic py-2">Tidak ada foto sugest.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 text-right pt-4 border-t border-gray-100">
                                <button type="button" class="px-6 py-2 bg-gray-600 text-white font-bold rounded hover:bg-gray-700 transition shadow-lg" @click="showModal = false">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>
</html>