<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Branding - Sorex Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        .bg-sidebar { background-color: #3d3d3d; }
        .bg-sidebar-active { background-color: #e02222; }
        .bg-header { background-color: #2b2b2b; }
        body { background-color: #f3f4f6; font-family: 'Segoe UI', sans-serif; }
        [x-cloak] { display: none !important; }
        
        .custom-scroll::-webkit-scrollbar { height: 10px; width: 8px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        .modal-scroll::-webkit-scrollbar { width: 8px; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        
        th { position: sticky; top: 0; z-index: 20; }
        .sticky-left { position: sticky; left: 0; z-index: 30; background-color: #f9fafb; }
        th.sticky-left { z-index: 40; background-color: #1f2937; }
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans">

    @include('layouts.sidebar_admin')

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        @include('layouts.header_admin')

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6" x-data="{ 
            activeTab: '{{ request('tab', 'regional1') }}', 
            subTab: '{{ request('subtab', 'proses') }}', 
            showModal: false,
            openImportModal: false,
            processItem: { parent_data: {} } 
        }">
            
            {{-- HEADER: JUDUL (KIRI) & COUNTER (KANAN) --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-700">Status Branding</h1>
                    <button @click="openImportModal = true" class="mt-2 text-xs bg-green-600 hover:bg-green-700 text-white py-1.5 px-3 rounded shadow transition flex items-center">
                        <i class="fas fa-file-excel mr-2"></i> Import History Excel
                    </button>
                </div>
                
                {{-- COUNTER DINAMIS --}}
                <div class="text-sm">
                    <template x-if="activeTab === 'regional1'">
                        <div class="flex gap-3">
                            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg shadow-sm border border-yellow-200 font-bold flex items-center">
                                <i class="fas fa-hourglass-half mr-2"></i> Proses: <strong class="ml-1 text-lg">{{ $data1_proses->total() }}</strong>
                            </div>
                            <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg shadow-sm border border-green-200 font-bold flex items-center">
                                <i class="fas fa-check-circle mr-2"></i> Selesai: <strong class="ml-1 text-lg">{{ $data1_selesai->total() }}</strong>
                            </div>
                        </div>
                    </template>

                    <template x-if="activeTab === 'regional2'">
                        <div class="flex gap-3">
                            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg shadow-sm border border-yellow-200 font-bold flex items-center">
                                <i class="fas fa-hourglass-half mr-2"></i> Proses: <strong class="ml-1 text-lg">{{ $data2_proses->total() }}</strong>
                            </div>
                            <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg shadow-sm border border-green-200 font-bold flex items-center">
                                <i class="fas fa-check-circle mr-2"></i> Selesai: <strong class="ml-1 text-lg">{{ $data2_selesai->total() }}</strong>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">{{ session('error') }}</div>
            @endif

            {{-- FORM PENCARIAN --}}
            <div class="mb-6 bg-white p-2 rounded-lg shadow-sm border">
                <form action="{{ url()->current() }}" method="GET" class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full py-2 pl-10 pr-4 text-sm text-gray-700 bg-gray-50 border rounded-md focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Cari Request ID (RB...), Nama Toko, Sales, atau Vendor...">
                    </div>

                    {{-- JAGA TAB AGAR TIDAK RESET SAAT SEARCH --}}
                    <input type="hidden" name="tab" :value="activeTab">
                    <input type="hidden" name="subtab" :value="subTab">

                    <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 transition font-bold text-sm">Cari</button>
                    
                    @if(request('search'))
                        <a href="{{ url()->current() }}?tab={{ request('tab', 'regional1') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition font-bold text-sm flex items-center"><i class="fas fa-times mr-1"></i> Reset</a>
                    @endif
                </form>
            </div>

            {{-- TAB REGIONAL --}}
            <div class="flex space-x-4 border-b border-gray-300 mb-4">
                <button @click="activeTab = 'regional1'" :class="activeTab === 'regional1' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors"><i class="fas fa-map-marker-alt mr-2"></i> Regional 1</button>
                <button @click="activeTab = 'regional2'" :class="activeTab === 'regional2' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors"><i class="fas fa-map-marker-alt mr-2"></i> Regional 2</button>
            </div>

            {{-- SUB TAB (PROSES / SELESAI) --}}
            <div class="flex justify-center mb-4">
                <div class="bg-white p-1 rounded-lg shadow-sm border inline-flex">
                    <button @click="subTab = 'proses'" :class="subTab === 'proses' ? 'bg-yellow-500 text-white shadow' : 'text-gray-500 hover:bg-gray-100'" class="px-4 py-1.5 rounded-md text-sm font-semibold transition-all">
                        <i class="fas fa-hourglass-half mr-1"></i> Sedang Proses
                    </button>
                    <button @click="subTab = 'selesai'" :class="subTab === 'selesai' ? 'bg-green-600 text-white shadow' : 'text-gray-500 hover:bg-gray-100'" class="px-4 py-1.5 rounded-md text-sm font-semibold transition-all ml-1">
                        <i class="fas fa-check-circle mr-1"></i> Riwayat Selesai
                    </button>
                </div>
            </div>

            {{-- KONTEN REGIONAL 1 --}}
            <div x-show="activeTab === 'regional1'" x-transition.opacity>
                <div x-show="subTab === 'proses'" class="bg-white rounded-lg shadow-md border-t-4 border-yellow-500 flex flex-col h-[70vh]">
                    <div class="overflow-auto custom-scroll flex-1 w-full rounded-b-lg">@include('admin.status.partials.table_status', ['data' => $data1_proses, 'color' => 'red'])</div>
                    <div class="px-5 py-3 border-t bg-gray-50">{{ $data1_proses->appends(['tab'=>'regional1', 'subtab'=>'proses', 'search'=>request('search')])->links() }}</div>
                </div>
                <div x-show="subTab === 'selesai'" class="bg-white rounded-lg shadow-md border-t-4 border-green-600 flex flex-col h-[70vh]" x-cloak>
                    <div class="overflow-auto custom-scroll flex-1 w-full rounded-b-lg bg-gray-50">
                        <div class="opacity-90">@include('admin.status.partials.table_status', ['data' => $data1_selesai, 'color' => 'red'])</div>
                    </div>
                    <div class="px-5 py-3 border-t bg-gray-50">{{ $data1_selesai->appends(['tab'=>'regional1', 'subtab'=>'selesai', 'search'=>request('search')])->links() }}</div>
                </div>
            </div>

            {{-- KONTEN REGIONAL 2 --}}
            <div x-show="activeTab === 'regional2'" x-cloak x-transition.opacity>
                <div x-show="subTab === 'proses'" class="bg-white rounded-lg shadow-md border-t-4 border-yellow-500 flex flex-col h-[70vh]">
                    <div class="overflow-auto custom-scroll flex-1 w-full rounded-b-lg">@include('admin.status.partials.table_status', ['data' => $data2_proses, 'color' => 'green'])</div>
                    <div class="px-5 py-3 border-t bg-gray-50">{{ $data2_proses->appends(['tab'=>'regional2', 'subtab'=>'proses', 'search'=>request('search')])->links() }}</div>
                </div>
                <div x-show="subTab === 'selesai'" class="bg-white rounded-lg shadow-md border-t-4 border-green-600 flex flex-col h-[70vh]" x-cloak>
                    <div class="overflow-auto custom-scroll flex-1 w-full rounded-b-lg bg-gray-50">
                        <div class="opacity-90">@include('admin.status.partials.table_status', ['data' => $data2_selesai, 'color' => 'green'])</div>
                    </div>
                    <div class="px-5 py-3 border-t bg-gray-50">{{ $data2_selesai->appends(['tab'=>'regional2', 'subtab'=>'selesai', 'search'=>request('search')])->links() }}</div>
                </div>
            </div>

            {{--  MODAL UPDATE (DENGAN INPUT TAB & SUBTAB)  --}}
            <div x-show="showModal" style="display: none;" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" @click="showModal = false">
                        <div class="absolute inset-0 bg-gray-900 opacity-80"></div>
                    </div>

                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl w-full">
                        
                        <div class="bg-yellow-500 px-6 py-4 flex justify-between items-center" :class="processItem.status_pekerjaan === 'SELESAI' ? 'bg-green-600' : 'bg-yellow-500'">
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <i class="fas fa-edit mr-3"></i> 
                                <span x-text="processItem.status_pekerjaan === 'SELESAI' ? 'Data Selesai:' : 'Update Proses:'"></span> 
                                <span x-text="processItem.request_id" class="ml-2 bg-white text-gray-800 px-2 rounded text-sm"></span>
                            </h3>
                            <button @click="showModal = false" class="text-white hover:text-gray-200 text-xl"><i class="fas fa-times"></i></button>
                        </div>

                        <form action="{{ route('admin.request.store_status') }}" method="POST" class="flex flex-col md:flex-row h-[80vh]">
                            @csrf
                            
                            {{-- INPUT PENTING: Kunci Posisi Tab & SubTab --}}
                            <input type="hidden" name="tab" :value="activeTab">
                            <input type="hidden" name="subtab" :value="subTab">

                            <input type="hidden" name="id" :value="processItem.id">
                            <input type="hidden" name="id_status_branding" :value="processItem.id">
                            <input type="hidden" name="request_id" :value="processItem.request_id">
                            <input type="hidden" name="jenis_permintaan" :value="processItem.jenis_permintaan">

                            {{-- KONTEN FORM --}}
                            <div class="w-full md:w-1/2 bg-gray-50 p-6 overflow-y-auto modal-scroll border-r border-gray-200">
                                <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b-2 border-gray-300">DATA REQUEST</h4>
                                <div class="bg-white p-4 rounded shadow-sm border border-gray-200 space-y-3 text-sm">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="font-bold text-gray-400 text-xs">TOKO</label><div x-text="processItem.parent_data ? processItem.parent_data.nama_toko : '-'"></div></div>
                                        <div><label class="font-bold text-gray-400 text-xs">AREA</label><div class="font-bold text-blue-600" x-text="processItem.parent_data ? processItem.parent_data.area_sales : '-'"></div></div>
                                        <div><label class="font-bold text-gray-400 text-xs">SALES</label><div x-text="processItem.parent_data ? processItem.parent_data.nama_sales : '-'"></div></div>
                                        <div><label class="font-bold text-gray-400 text-xs">BRAND</label><div x-text="processItem.parent_data ? processItem.parent_data.brand : '-'"></div></div>
                                        <div class="col-span-2"><label class="font-bold text-gray-400 text-xs">TOOLS</label><div class="text-red-600 font-bold" x-text="processItem.parent_data ? processItem.parent_data.jenis_tools_branding : '-'"></div></div>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full md:w-1/2 p-6 overflow-y-auto modal-scroll bg-white">
                                <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b-2 border-yellow-400">UPDATE TRACKING</h4>
                                <div class="grid grid-cols-1 gap-4 text-sm"> 
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded"><label class="block font-bold text-gray-700 text-xs mb-1">VIA</label><input type="text" name="via" value="WEB" readonly class="w-full border rounded px-2 py-1 bg-gray-200 cursor-not-allowed"></div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Pembuatan Design</label><input type="date" name="pembuatan_design" :value="processItem.pembuatan_design" class="w-full border rounded px-2 py-1"></div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div><label class="block font-bold text-gray-700 text-xs">Approve Leader</label><input type="date" name="approve_leader" :value="processItem.approve_leader" class="w-full border rounded px-2 py-1"></div>
                                        <div><label class="block font-bold text-gray-700 text-xs">Approve Toko</label><input type="date" name="approve_toko" :value="processItem.approve_toko" class="w-full border rounded px-2 py-1"></div>
                                    </div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Konfirmasi Design</label><input type="date" name="konfirmasi_design" :value="processItem.konfirmasi_design" class="w-full border rounded px-2 py-1"></div>
                                    <hr class="border-gray-200 my-2">
                                    <div><label class="block font-bold text-gray-700 text-xs">Tgl Masuk Vendor</label><input type="date" name="tanggal_masuk_vendor" :value="processItem.tanggal_masuk_vendor" class="w-full border rounded px-2 py-1"></div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Nama Vendor</label><input type="text" name="nama_vendor" :value="processItem.nama_vendor" class="w-full border rounded px-2 py-1"></div>
                                    <hr class="border-gray-200 my-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div><label class="block font-bold text-gray-700 text-xs">SJ Diterima Tasya</label><input type="date" name="sj_di_terima_tasya" :value="processItem.sj_di_terima_tasya" class="w-full border rounded px-2 py-1"></div>
                                        <div><label class="block font-bold text-gray-700 text-xs">PO Selesai Gudang FR</label><input type="date" name="po_selesai_gudang_fr" :value="processItem.po_selesai_gudang_fr" class="w-full border rounded px-2 py-1"></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div><label class="block font-bold text-gray-700 text-xs">Packing Barang FR</label><input type="text" name="packing_barang_fr" :value="processItem.packing_barang_fr" class="w-full border rounded px-2 py-1"></div>
                                        <div><label class="block font-bold text-gray-700 text-xs">Kirim ke Dadap</label><input type="date" name="gudang_fr_kirim_ke_dadap" :value="processItem.kirim_ke_dadap" class="w-full border rounded px-2 py-1"></div>
                                    </div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Barang Diterima Dadap</label><input type="date" name="barang_diterima_dadap" :value="processItem.terima_di_dadap" class="w-full border rounded px-2 py-1"></div>
                                    <hr class="border-gray-200 my-2">
                                    <div><label class="block font-bold text-gray-700 text-xs">Kirim ke Ekspedisi</label><input type="date" name="kirim_ke_ekspedisi" :value="processItem.kirim_ke_ekspedisi" class="w-full border rounded px-2 py-1"></div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Nomor Resi</label><input type="text" name="nomor_resi" :value="processItem.nomor_resi" class="w-full border rounded px-2 py-1 bg-green-50"></div>
                                    <div><label class="block font-bold text-gray-700 text-xs">Konf. Penerimaan Barang</label><input type="date" name="konfirmasi_penerimaan_barang" :value="processItem.konfirmasi_penerimaan" class="w-full border rounded px-2 py-1"></div>
                                </div>

                                <div class="mt-8 pt-4 border-t flex gap-3 bg-white">
                                    <template x-if="processItem.status_pekerjaan !== 'SELESAI'">
                                        <div class="flex gap-3 w-full">
                                            <button type="submit" name="action" value="hapus" onclick="return confirm('Hapus Data?')" class="bg-red-100 hover:bg-red-200 text-red-700 font-bold py-3 px-4 rounded transition shadow-sm border border-red-300"><i class="fas fa-trash-alt"></i></button>
                                            <button type="submit" name="action" value="update" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded transition shadow-sm"><i class="fas fa-save mr-2"></i> Update Data</button>
                                            <button type="submit" name="action" value="selesai" onclick="return confirm('Tandai SELESAI?')" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded transition shadow-sm"><i class="fas fa-check-double mr-2"></i> Tandai DONE</button>
                                        </div>
                                    </template>

                                    <template x-if="processItem.status_pekerjaan === 'SELESAI'">
                                        <div class="flex gap-3 w-full">
                                            <button type="submit" name="action" value="update" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded transition shadow-sm"><i class="fas fa-save mr-2"></i> Simpan Revisi</button>
                                            <button type="submit" name="action" value="revisi" onclick="return confirm('Kembalikan ke PROSES?')" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded transition shadow-sm"><i class="fas fa-undo mr-2"></i> Batal Selesai (Revisi)</button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MODAL IMPORT --}}
            <div x-show="openImportModal" style="display: none;" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 transition-opacity" @click="openImportModal = false"><div class="absolute inset-0 bg-gray-900 opacity-75"></div></div>
                    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full p-6 relative">
                        <h3 class="text-lg font-bold mb-4 text-gray-800">Import History Status</h3>
                        <form action="{{ route('admin.status.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4"><label class="block text-gray-700 text-sm font-bold mb-2">Pilih File Excel</label><input type="file" name="file_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none" required></div>
                            <div class="flex justify-end gap-2 border-t pt-4"><button type="button" @click="openImportModal = false" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition">Batal</button><button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">Upload</button></div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>
</html>