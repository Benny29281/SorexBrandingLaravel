<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Laporan - Sorex Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bar2.png') }}">
    
    <style>
        .bg-sidebar { background-color: #3d3d3d; }
        .bg-sidebar-active { background-color: #e02222; }
        .bg-header { background-color: #2b2b2b; }
        body { background-color: #f3f4f6; font-family: 'Segoe UI', sans-serif; }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans">

    @include('layouts.sidebar_admin')

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        @include('layouts.header_admin')

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="max-w-7xl mx-auto mt-4">
                
                {{-- JUDUL --}}
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fas fa-file-excel text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-700">Export Laporan Branding</h1>
                        <p class="text-sm text-gray-500">Sesuaikan filter dan kolom sesuai kebutuhan laporan.</p>
                    </div>
                </div>

                {{-- REVISI: openColumns: false (DEFAULT TERTUTUP) --}}
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-visible" x-data="{ openColumns: false, selectAll: true }">
                    <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
                        <h3 class="text-white font-bold text-lg"><i class="fas fa-filter mr-2"></i> Konfigurasi Export</h3>
                    </div>

                    <form action="{{ route('admin.laporan.export') }}" method="POST" class="p-8">
                        @csrf
                        
                        {{-- 1. FILTER AREA & TANGGAL --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 border-b border-gray-200 pb-8">
                            <div x-data="{ open: false, selected: [] }">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Regional / Area</label>
                            <div class="relative">
                                <button type="button" 
                                    @click="open = !open" 
                                    class="w-full border-gray-300 border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 bg-white flex justify-between items-center shadow-sm"
                                    :class="open ? 'ring-2 ring-green-500' : ''">
                                    
                                    <span class="text-gray-700 truncate">
                                        <template x-if="selected.length === 0">
                                            <span>SEMUA DATA</span>
                                        </template>
                                        <template x-if="selected.length > 0">
                                            <span x-text="selected.join(', ')"></span>
                                        </template>
                                    </span>
                                    <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>

                                <div x-show="open" 
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    class="absolute left-0 right-0 z-[9999] mt-2 bg-white border border-gray-200 rounded-lg shadow-xl p-3"
                                    style="display: none;">
                                    
                                    <div class="max-h-60 overflow-y-auto">
                                        <div class="mb-3">
                                            <p class="px-2 pb-1 text-[10px] font-bold text-gray-400 uppercase border-b mb-2 tracking-widest">Regional 1</p>
                                            <div class="grid grid-cols-1 gap-1">
                                                <template x-for="item in ['JT', 'DK', 'LP']">
                                                    <label class="flex items-center px-3 py-2 hover:bg-green-50 rounded-md cursor-pointer transition-colors group">
                                                        <input type="checkbox" name="area[]" :value="item" x-model="selected"
                                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-4 h-4">
                                                        <span class="ml-3 text-sm text-gray-700 group-hover:text-green-700 font-medium" x-text="item"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="mb-1">
                                            <p class="px-2 pb-1 text-[10px] font-bold text-gray-400 uppercase border-b mb-2 tracking-widest">Regional 2</p>
                                            <div class="grid grid-cols-1 gap-1">
                                                <template x-for="item in ['JB', 'JR']">
                                                    <label class="flex items-center px-3 py-2 hover:bg-green-50 rounded-md cursor-pointer transition-colors group">
                                                        <input type="checkbox" name="area[]" :value="item" x-model="selected"
                                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-4 h-4">
                                                        <span class="ml-3 text-sm text-gray-700 group-hover:text-green-700 font-medium" x-text="item"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-2 pt-2 border-t border-gray-100 flex justify-between items-center">
                                        <span class="text-[10px] text-gray-400" x-text="selected.length + ' dipilih'"></span>
                                        <button type="button" @click="selected = []" class="text-[11px] text-red-500 hover:text-red-700 font-bold">Hapus Semua</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2 text-sm">Dari Tanggal</label>
                                <input type="date" name="start_date" class="w-full border-gray-300 border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 cursor-pointer">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2 text-sm">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="w-full border-gray-300 border rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 cursor-pointer">
                            </div>
                        </div>

                        {{-- 2. PILIH KOLOM --}}
                        <div class="mb-6 relative z-50">
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Pilih Header / Kolom Excel</label>

                            <button type="button" @click="openColumns = !openColumns" 
                                    class="w-full bg-white border border-green-500 text-gray-700 py-3 px-4 rounded-lg shadow-sm text-left flex justify-between items-center hover:bg-gray-50 focus:outline-none transition-colors">
                                <span class="flex items-center">
                                    <i class="fas fa-table text-green-600 mr-3"></i>
                                    <span class="font-medium">Klik untuk Memilih Kolom...</span>
                                </span>
                                <i class="fas fa-chevron-down transition-transform duration-300" :class="openColumns ? 'transform rotate-180' : ''"></i>
                            </button>

                            <div x-show="openColumns" 
                                 x-cloak 
                                 class="mt-2 bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden w-full z-50">
                                
                                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-500 uppercase">Daftar Header Tersedia</span>
                                    <label class="flex items-center cursor-pointer hover:bg-gray-200 px-3 py-1 rounded transition select-none">
                                        <input type="checkbox" x-model="selectAll" class="rounded text-green-600 focus:ring-green-500 w-4 h-4 cursor-pointer">
                                        <span class="ml-2 text-xs font-bold text-gray-600">Pilih Semua</span>
                                    </label>
                                </div>

                                {{-- GRID CHECKBOXES --}}
                                <div class="p-6 bg-white max-h-[500px] overflow-y-auto custom-scroll">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        
                                        {{-- GROUP 1: INFO UTAMA --}}
                                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                            <div class="mb-3 border-b border-gray-200 pb-2">
                                                <span class="text-xs font-bold text-gray-800 uppercase tracking-wider bg-gray-200 px-2 py-1 rounded">BAGIAN 1: INFO REQUEST</span>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="request_id" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm">REQUEST ID</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="submission_date" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm">TGL REQUEST</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="nama_sales" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm">SALES</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="nama_toko" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm">TOKO</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="lokasi" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm">Lokasi</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="area_sales" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm">AREA</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="brand" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm font-bold">TIPE (B/P)</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="via" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm">VIA</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="jenis_tools_branding" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm font-bold">PERMINTAAN</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="qty_tools" checked x-bind:checked="selectAll" class="rounded text-gray-800"><span class="text-sm">QTY</span></label>
                                            </div>
                                        </div>

                                        {{-- GROUP 2: DESIGN & VENDOR --}}
                                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                            <div class="mb-3 border-b border-blue-200 pb-2">
                                                <span class="text-xs font-bold text-blue-800 uppercase tracking-wider bg-blue-200 px-2 py-1 rounded">BAGIAN 2: DESIGN & VENDOR</span>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="pembuatan_design" checked x-bind:checked="selectAll" class="rounded text-blue-600"><span class="text-sm">PEMBUATAN DESIGN</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="approve_leader" checked x-bind:checked="selectAll" class="rounded text-blue-600"><span class="text-sm">APPROVE LEADER</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="approve_toko" checked x-bind:checked="selectAll" class="rounded text-blue-600"><span class="text-sm">APPROVE TOKO</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="konfirmasi_design" checked x-bind:checked="selectAll" class="rounded text-blue-600"><span class="text-sm">KONFIRMASI DESIGN</span></label>
                                                <div class="border-t border-blue-200 my-2"></div>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="tanggal_masuk_vendor" checked x-bind:checked="selectAll" class="rounded text-orange-600"><span class="text-sm font-bold text-orange-700">MASUK VENDOR</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="nama_vendor" checked x-bind:checked="selectAll" class="rounded text-orange-600"><span class="text-sm font-bold text-orange-700">NAMA VENDOR</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="sj_di_terima_tasya" checked x-bind:checked="selectAll" class="rounded text-green-700"><span class="text-sm font-bold text-green-800">SJ DI TERIMA TASYA</span></label>
                                            </div>
                                        </div>

                                        {{-- GROUP 3: GUDANG & FINISH --}}
                                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                            <div class="mb-3 border-b border-green-200 pb-2">
                                                <span class="text-xs font-bold text-green-800 uppercase tracking-wider bg-green-200 px-2 py-1 rounded">BAGIAN 3: GUDANG & FINISH</span>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="po_selesai_gudang_fr" checked x-bind:checked="selectAll" class="rounded text-green-600"><span class="text-sm">PO SELESAI & KE GUDANG FR</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="packing_barang_fr" checked x-bind:checked="selectAll" class="rounded text-green-600"><span class="text-sm">PACKING DI GUDANG FR</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="kirim_ke_dadap" checked x-bind:checked="selectAll" class="rounded text-green-600"><span class="text-sm">KIRIM KE DADAP</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="terima_di_dadap" checked x-bind:checked="selectAll" class="rounded text-green-600"><span class="text-sm">TERIMA DI DADAP</span></label>
                                                <div class="border-t border-green-200 my-2"></div>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="kirim_ke_ekspedisi" checked x-bind:checked="selectAll" class="rounded text-purple-600"><span class="text-sm font-bold text-purple-700">KIRIM KE EKSPEDISI</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="nomor_resi" checked x-bind:checked="selectAll" class="rounded text-purple-600"><span class="text-sm font-bold text-purple-700">NOMOR RESI</span></label>
                                                <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="konfirmasi_penerimaan" checked x-bind:checked="selectAll" class="rounded text-purple-600"><span class="text-sm font-bold text-purple-700">KONF. PENERIMAAN</span></label>
                                            </div>
                                        </div>

                                    </div>
                                    
                                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                                        <button type="button" @click="openColumns = false" class="text-sm text-green-600 font-bold hover:underline">
                                            <i class="fas fa-check mr-1"></i> Selesai Memilih
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 mt-8 border-t border-gray-200">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-10 rounded-lg shadow-lg transform transition hover:scale-105 flex items-center">
                                <i class="fas fa-file-download mr-2"></i> DOWNLOAD EXCEL
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </main>
    </div>

    @include('components.keep-alive')
</body>
</html>