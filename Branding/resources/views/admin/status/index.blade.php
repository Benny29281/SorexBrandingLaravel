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
        
        /* Sticky Header Table */
        th { position: sticky; top: 0; z-index: 20; box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1); }

        /* Custom Scrollbar Tipis */
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

        <main class="flex-1 overflow-y-auto bg-gray-100 p-6" x-data="{ 
    activeTab: '{{ request('tab', 'regional1') }}', 
    subTab: '{{ request('subtab', 'proses') }}', 
    showModal: false,
    openImportModal: false,
    processItem: { parent_data: {} },

    /* FUNGSI: Bersihkan data jam agar muncul di modal (Syntax Error sudah diperbaiki) */
    setProcessItem(item) {
        let cleanedData = JSON.parse(JSON.stringify(item));
        
        const dateFields = [
            'submission_date','pembuatan_design', 'approve_leader', 'approve_toko', 'konfirmasi_design',
            'tanggal_masuk_vendor', 'sj_di_terima_tasya', 'po_selesai_gudang_fr',
            'kirim_ke_dadap', 'terima_di_dadap', 'kirim_ke_ekspedisi', 'konfirmasi_penerimaan'
        ];

        dateFields.forEach(field => {
            if (cleanedData[field] && cleanedData[field].length > 10) {
                cleanedData[field] = cleanedData[field].substring(0, 10);
            }
        });

        this.processItem = cleanedData;
        this.showModal = true;
    }
}">
    
    {{-- HEADER & TOMBOL IMPORT --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-700">Status Branding</h1>
            
            {{-- TOMBOL BUKA MODAL IMPORT --}}
            <button @click="openImportModal = true" class="mt-2 text-xs bg-green-600 hover:bg-green-700 text-white py-1.5 px-3 rounded shadow transition flex items-center">
                <i class="fas fa-file-excel mr-2"></i> Import History Excel
            </button>
        </div>
        
        {{-- INFO JUMLAH DATA --}}
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

    {{-- SEARCH BAR --}}
    <div class="mb-6 bg-white p-2 rounded-lg shadow-sm border">
        <form action="{{ url()->current() }}" method="GET" class="flex gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full py-2 pl-10 pr-4 text-sm text-gray-700 bg-gray-50 border rounded-md focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Cari Request ID (RB...), Nama Toko, Sales, atau Vendor...">
            </div>
            <input type="hidden" name="tab" :value="activeTab">
            <input type="hidden" name="subtab" :value="subTab">
            <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 transition font-bold text-sm">Cari</button>
            @if(request('search'))
                <a href="{{ url()->current() }}?tab={{ request('tab', 'regional1') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition font-bold text-sm flex items-center"><i class="fas fa-times mr-1"></i> Reset</a>
            @endif
        </form>
    </div>

    {{-- TABS --}}
    <div class="flex space-x-4 border-b border-gray-300 mb-4">
        <button @click="activeTab = 'regional1'" :class="activeTab === 'regional1' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors"><i class="fas fa-map-marker-alt mr-2"></i> Regional 1</button>
        <button @click="activeTab = 'regional2'" :class="activeTab === 'regional2' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors"><i class="fas fa-map-marker-alt mr-2"></i> Regional 2</button>
    </div>

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

    {{-- TABEL DATA (REGIONAL 1 & 2) --}}
    @foreach(['regional1' => ['data_proses' => $data1_proses, 'data_selesai' => $data1_selesai, 'color' => 'red'], 'regional2' => ['data_proses' => $data2_proses, 'data_selesai' => $data2_selesai, 'color' => 'green']] as $key => $val)
        <div x-show="activeTab === '{{ $key }}'" x-transition.opacity {{ $key == 'regional2' ? 'x-cloak' : '' }}>
            {{-- Subtab Proses --}}
            <div x-show="subTab === 'proses'" class="bg-white rounded-lg shadow-md border-t-4 border-yellow-500 mb-6">
                <div class="w-full"> 
                    @include('admin.status.partials.table_status', ['data' => $val['data_proses'], 'color' => $val['color']])
                </div>
                <div class="px-5 py-4 border-t bg-gray-50">{{ $val['data_proses']->appends(['tab'=>$key, 'subtab'=>'proses', 'search'=>request('search')])->links() }}</div>
            </div>
            {{-- Subtab Selesai --}}
            <div x-show="subTab === 'selesai'" class="bg-white rounded-lg shadow-md border-t-4 border-{{ $val['color'] }}-600 mb-6" x-cloak>
                <div class="w-full bg-gray-50">
                    <div class="opacity-90">
                        @include('admin.status.partials.table_status', ['data' => $val['data_selesai'], 'color' => $val['color']])
                    </div>
                </div>
                <div class="px-5 py-4 border-t bg-gray-50">{{ $val['data_selesai']->appends(['tab'=>$key, 'subtab'=>'selesai', 'search'=>request('search')])->links() }}</div>
            </div>
        </div>
    @endforeach

    {{-- MODAL UPDATE STATUS (ISI SAMA PERSIS SEPERTI SEBELUMNYA) --}}
    <div x-show="showModal" style="display: none;" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="showModal = false">
                <div class="absolute inset-0 bg-gray-900 opacity-80"></div>
            </div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-7xl w-full">
                
                <div class="bg-yellow-500 px-6 py-3 flex justify-between items-center" :class="processItem.status_pekerjaan === 'SELESAI' ? 'bg-green-600' : 'bg-yellow-500'">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-edit mr-3"></i> 
                        <span x-text="processItem.status_pekerjaan === 'SELESAI' ? 'Data Selesai:' : 'Update Proses:'"></span> 
                        <span x-text="processItem.request_id" class="ml-2 bg-white text-gray-800 px-2 rounded text-sm font-mono"></span>
                    </h3>
                    <button @click="showModal = false" class="text-white hover:text-gray-200 text-xl"><i class="fas fa-times"></i></button>
                </div>

                <form action="{{ route('admin.request.store_status') }}" method="POST" class="flex flex-col md:flex-row h-[75vh]">
                    @csrf
                    <input type="hidden" name="tab" :value="activeTab">
                    <input type="hidden" name="subtab" :value="subTab">
                    <input type="hidden" name="id" :value="processItem.id">
                    <input type="hidden" name="id_status_branding" :value="processItem.id">
                    <input type="hidden" name="request_id" :value="processItem.request_id">
                    <input type="hidden" name="jenis_permintaan" :value="processItem.jenis_permintaan">

                    {{-- KOLOM KIRI: DATA REQUEST ASLI --}}
                    <div class="w-full md:w-4/12 bg-gray-50 p-5 overflow-y-auto modal-scroll border-r border-gray-200">
                        <h4 class="font-bold text-gray-700 mb-3 pb-2 border-b border-gray-300 text-xs uppercase tracking-wide">Data Request Asli</h4>
                        
                        <div class="bg-white p-3 rounded shadow-sm border border-gray-200 text-xs space-y-2">
                            <div class="grid grid-cols-2 gap-3">
                                <div><label class="font-bold text-gray-400 text-[10px]">TOKO</label><div class="font-semibold text-gray-800" x-text="processItem.parent_data ? processItem.parent_data.nama_toko : '-'"></div></div>
                                <div><label class="font-bold text-gray-400 text-[10px]">AREA</label><div class="font-bold text-blue-600" x-text="processItem.parent_data ? processItem.parent_data.area_sales : '-'"></div></div>
                                
                                <div><label class="font-bold text-gray-400 text-[10px]">SALES</label><div class="font-semibold" x-text="processItem.parent_data ? processItem.parent_data.nama_sales : '-'"></div></div>
                                <div><label class="font-bold text-gray-400 text-[10px]">BRAND</label><div class="font-semibold" x-text="processItem.parent_data ? processItem.parent_data.brand : '-'"></div></div>
                                
                                <div class="col-span-2">
                                    <label class="font-bold text-gray-400 text-[10px]">VIA REQUEST</label>
                                    <input type="text" name="via" :value="processItem.via || 'WEB'" readonly class="w-full border rounded px-2 py-1 bg-gray-100 text-gray-600 font-bold focus:outline-none cursor-not-allowed">
                                </div>

                                <div class="col-span-2 bg-yellow-50 p-2 rounded border border-yellow-100">
                                    <label class="font-bold text-gray-400 text-[10px]">TOOLS</label>
                                    <div class="text-red-600 font-extrabold text-sm" x-text="processItem.parent_data ? processItem.parent_data.jenis_tools_branding : '-'"></div>
                                </div>

                                <div class="col-span-2">
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="font-bold text-gray-400 text-[10px]">UKURAN (ASLI)</label>
                                        <label class="font-bold text-gray-400 text-[10px]">QTY</label>
                                    </div>
                                    <div class="flex gap-2">
                                        <input type="text" name="ukuran_fix" :value="processItem.ukuran_fix || (processItem.parent_data ? processItem.parent_data.ukuran_tools_branding : '')" class="w-full border border-gray-300 rounded px-2 py-1 text-gray-800 font-bold focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white" placeholder="Edit Ukuran...">
                                        <div class="bg-gray-100 px-3 py-1 rounded text-gray-800 font-bold border border-gray-200 min-w-[50px] text-center" x-text="(processItem.parent_data ? processItem.parent_data.qty_tools : '0')"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: FORM UPDATE --}}
                    <div class="w-full md:w-8/12 p-5 overflow-y-auto modal-scroll bg-white flex flex-col justify-between">
                        <div>
                            <h4 class="font-bold text-gray-700 mb-3 pb-2 border-b-2 border-yellow-400 text-xs uppercase tracking-wide flex justify-between">
                                <span>Update Tracking</span>
                                <span class="text-[10px] text-gray-400 normal-case italic">*Isi tanggal sesuai progress</span>
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                                {{-- Group 1: Design & Approval --}}
                                <div class="md:col-span-3 grid grid-cols-4 gap-3 bg-blue-50 p-3 rounded border border-blue-100">
                                    <div class="col-span-4 mb-1 border-b border-blue-200 pb-1 font-bold text-blue-800 text-[10px]">TAHAP DESAIN & APPROVAL</div>
                                    
                                    <div>
                                        <label class="block font-bold text-red-600 mb-1">Mulai Design</label>
                                        <input type="date" name="pembuatan_design" :value="processItem.pembuatan_design" class="w-full border rounded px-2 py-1" {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-red-600 mb-1">Design Final</label>
                                        <input type="date" name="konfirmasi_design" :value="processItem.konfirmasi_design" class="w-full border rounded px-2 py-1 bg-blue-100 font-semibold text-blue-900" {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-gray-600 mb-1">Acc Leader</label>
                                        <input type="date" name="approve_leader" :value="processItem.approve_leader" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                    </div>

                                    <div>
                                        <label class="block font-bold text-gray-600 mb-1">Acc Toko</label>
                                        <input type="date" name="approve_toko" :value="processItem.approve_toko" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                    </div>
                                </div>

                                {{-- Group 2: Vendor --}}
                                <div class="md:col-span-1 border border-gray-200 p-2 rounded">
                                    <label class="block font-bold text-gray-800 mb-2 text-[10px] border-b pb-1">VENDOR</label>
                                    <div class="space-y-2">
                                        <div>
                                            <label class="block text-gray-500 mb-0.5">Tgl Masuk</label>
                                            <input type="date" name="tanggal_masuk_vendor" :value="processItem.tanggal_masuk_vendor" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                        </div>
                                        <div>
                                            <label class="block text-gray-500 mb-0.5">Nama Vendor</label>
                                            <input type="text" name="nama_vendor" :value="processItem.nama_vendor" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ !in_array(auth()->user()->regional, ['Admin', 'Design']) ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                {{-- Group 3: Gudang --}}
                                <div class="md:col-span-2 border border-gray-200 p-2 rounded">
                                    <label class="block font-bold text-gray-800 mb-2 text-[10px] border-b pb-1">GUDANG & LOGISTIK</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div><label class="block text-gray-500 mb-0.5">SJ Tasya</label><input type="date" name="sj_di_terima_tasya" :value="processItem.sj_di_terima_tasya" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}></div>
                                        <div><label class="block text-gray-500 mb-0.5">PO Selesai</label><input type="date" name="po_selesai_gudang_fr" :value="processItem.po_selesai_gudang_fr" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}></div>
                                        <div><label class="block text-gray-500 mb-0.5">Packing FR</label><input type="text" name="packing_barang_fr" :value="processItem.packing_barang_fr" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}></div>
                                        <div><label class="block text-gray-500 mb-0.5">Kirim ke Dadap</label><input type="date" name="gudang_fr_kirim_ke_dadap" :value="processItem.kirim_ke_dadap" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}></div>
                                        <div><label class="block text-gray-500 mb-0.5">Terima di Dadap</label><input type="date" name="barang_diterima_dadap" :value="processItem.terima_di_dadap" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}></div>
                                    </div>
                                </div>

                                {{-- Group 4: Pengiriman --}}
                                <div class="md:col-span-3 bg-green-50 p-3 rounded border border-green-100">
                                    <label class="block font-bold text-green-800 mb-2 text-[10px] border-b border-green-200 pb-1">PENGIRIMAN & PENERIMAAN</label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <div><label class="block text-gray-600 mb-1">Kirim Ekspedisi</label><input type="date" name="kirim_ke_ekspedisi" :value="processItem.kirim_ke_ekspedisi" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}></div>
                                        <div><label class="block text-gray-600 mb-1">Nomor Resi</label><input type="text" name="nomor_resi" :value="processItem.nomor_resi" class="w-full border rounded px-2 py-1 bg-white border-green-300 text-green-800 font-bold {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}></div>
                                        <div><label class="block text-gray-600 mb-1">Konf. Terima Toko</label><input type="date" name="konfirmasi_penerimaan_barang" :value="processItem.konfirmasi_penerimaan" class="w-full border rounded px-2 py-1 {{ auth()->user()->regional === 'Design' ? 'bg-gray-100' : '' }}" {{ auth()->user()->regional !== 'Admin' ? 'readonly' : '' }}></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-3 border-t flex justify-end gap-3 bg-white sticky bottom-0">
                                <button type="button" @click="showModal = false" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg text-base transition duration-200"><i class="fas fa-times mr-2 text-lg"></i> Batal</button>

                                <template x-if="processItem.status_pekerjaan !== 'SELESAI'">
                                    <div class="flex gap-3">
                                        @if(auth()->user()->regional === 'Admin')
                                            <button type="submit" name="action" value="hapus" onclick="return confirm('Hapus Data?')" class="bg-red-100 hover:bg-red-200 text-red-700 font-bold py-3 px-5 rounded-lg text-base transition duration-200"><i class="fas fa-trash-alt text-lg"></i></button>
                                        @endif
                                        <button type="submit" name="action" value="update" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg text-base shadow-md transition duration-200 transform hover:scale-105" onclick="return confirm('Apakah Anda setuju untuk menyimpan perubahan ini?')"><i class="fas fa-save mr-2 text-lg"></i> Update Data</button>
                                        @if(auth()->user()->regional === 'Admin')
                                            <button type="submit" name="action" value="selesai" onclick="return confirm('Tandai SELESAI?')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-base shadow-md transition duration-200 transform hover:scale-105"><i class="fas fa-check-double mr-2 text-lg"></i> Tandai DONE</button>
                                        @endif
                                    </div>
                                </template>
                                <template x-if="processItem.status_pekerjaan === 'SELESAI'">
                                    <div class="flex gap-2">
                                        <button type="submit" name="action" value="update" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-xs shadow-sm" onclick="return confirm('Apakah Anda setuju untuk menyimpan perubahan ini?')"><i class="fas fa-save mr-2"></i> Simpan Revisi</button>
                                        <button type="submit" name="action" value="revisi" onclick="return confirm('Kembalikan ke PROSES?')" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-xs shadow-sm"><i class="fas fa-undo mr-2"></i> Batal Selesai (Revisi)</button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =========================================================
         MODAL IMPORT (SUDAH DIPERBAIKI)
         ========================================================= --}}
    <div x-show="openImportModal" style="display: none;" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            
            <div class="fixed inset-0 transition-opacity" @click="openImportModal = false">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full p-6 relative">
                
                {{-- JUDUL DINAMIS --}}
                <h3 class="text-lg font-bold mb-4 text-gray-800">
                    Import History Excel <span x-text="activeTab === 'regional1' ? '(Reg 1)' : '(Reg 2)'" class="text-blue-600 text-sm"></span>
                </h3>

                {{-- FORM ACTION DINAMIS: ROUTE OTOMATIS BERUBAH --}}
                <form :action="activeTab === 'regional1' ? '{{ route('admin.status.import') }}' : '{{ route('admin.status.import2') }}'" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih File Excel</label>
                        <input type="file" name="file_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none" required>
                    </div>
                    
                    <div class="flex justify-end gap-2 border-t pt-4">
                        <button type="button" @click="openImportModal = false" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition">Batal</button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>
    </div>

    @include('components.keep-alive')
</body>
</html>