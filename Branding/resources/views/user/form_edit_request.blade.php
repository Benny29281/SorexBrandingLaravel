<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Request - {{ $data->request_id }}</title>
    
    {{-- Library --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bar2.png') }}">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        .border-sorex { border-color: #d71920; }
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; }
        select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>

{{-- Tambahkan x-data --}}
<body x-data="{ open: false, showProfileModal: false }" class="bg-gray-100 font-sans min-h-screen flex flex-col">

    {{-- =========================================
         NAVBAR (REVISI: TANPA GARIS MERAH TUA)
         ========================================= --}}
    {{-- Hapus 'border-b-4 border-red-800' --}}
    <header class="w-full py-4 px-6 sm:px-10 flex justify-between items-center text-white z-50 relative bg-sorex shadow-md">
        <div class="flex items-center z-50">
            <a href="{{ route('user.home') }}">
                <img src="{{ asset('img/logo5.png') }}" alt="SOREX Logo" class="h-10 md:h-12 w-auto drop-shadow-md" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <h1 class="text-3xl font-extrabold tracking-widest italic drop-shadow-md hidden">SOREX</h1>
            </a>
        </div>

        {{-- Hamburger --}}
        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-2xl"></i>
            <i x-show="open" x-cloak class="fas fa-times text-2xl"></i>
        </button>

        {{-- Menu Desktop --}}
        <nav class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider items-center">
            <a href="{{ route('user.home') }}" class="hover:text-red-200 transition flex items-center"><i class="fas fa-home mr-2"></i> Home</a>
            <a href="{{ route('user.log') }}" class="hover:text-red-200 transition relative group flex items-center"><i class="fas fa-history mr-2"></i> Log Aktivitas</a>
            <div class="border-l border-red-300 h-6 mx-2"></div>
            
            {{-- PROFILE BULAT --}}
            <div class="relative group cursor-pointer mr-2" @click="showProfileModal = true">
                {{-- Hapus bg-red-800 --}}
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-lg bg-white transform group-hover:scale-110 transition duration-300">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" alt="Profile" class="w-full h-full object-cover">
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-white text-sorex px-5 py-2 rounded-full font-bold hover:bg-gray-100 transition shadow-lg transform hover:scale-105 text-xs">Log Out <i class="fas fa-sign-out-alt ml-1"></i></button>
            </form>
        </nav>

        {{-- Menu Mobile --}}
        <div x-show="open" x-transition x-cloak class="absolute top-0 left-0 w-full bg-red-900/95 backdrop-blur-md shadow-2xl md:hidden pt-24 pb-8 px-6 flex flex-col space-y-4 text-center z-40 border-b border-red-700">
            <div class="flex flex-col items-center mb-4 border-b border-red-800 pb-4">
                <div class="relative w-16 h-16 rounded-full overflow-hidden border-2 border-white mb-2 bg-white shadow-xl">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" class="w-full h-full object-cover">
                </div>
                <h3 class="text-white font-bold text-lg tracking-wide">{{ Auth::user()->name }}</h3>
                <p class="text-red-200 text-xs">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('user.home') }}" class="block py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition"><i class="fas fa-home mr-2"></i> Home</a>
            <a href="{{ route('user.log') }}" class="block py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition"><i class="fas fa-history mr-2"></i> Log Aktivitas</a>
            <a href="{{ route('user.download.page') }}" class="block w-full py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition text-white uppercase"><i class="fas fa-file-download mr-2"></i> Download Data</a>
            <div class="border-t border-white/20 pt-4 mt-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-white text-sorex rounded-xl font-bold shadow-md active:scale-95 transition">Log Out</button>
                </form>
            </div>
        </div>
    </header>

    {{-- =========================================
         MAIN CONTENT (FORM EDIT)
         ========================================= --}}
    <main class="flex-grow container mx-auto px-4 py-8 max-w-5xl">
        
        {{-- Header Halaman --}}
        <div class="mb-6 flex flex-col md:flex-row justify-between items-center border-b border-gray-300 pb-4">
            <div class="text-center md:text-left mb-4 md:mb-0">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center justify-center md:justify-start">
                    Revisi Data: <span class="text-sorex ml-2">{{ $data->request_id }}</span>
                </h1>
                <p class="text-gray-500 text-sm mt-1">Silakan ubah data yang diperlukan.</p>
            </div>
            {{-- <a href="{{ route('user.request.revisi') }}" class="text-gray-500 hover:text-red-600 font-bold bg-white px-4 py-2 rounded-lg border border-gray-300 shadow-sm transition">
                <i class="fas fa-times mr-2"></i> Batal
            </a> --}}
        </div>

        {{-- FORM UPDATE --}}
        <form action="{{ route('user.request.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            @csrf
            @method('PUT')

            <input type="hidden" name="request_id" value="{{ $data->request_id }}">
            <input type="hidden" name="table_type" value="{{ $tableType }}">

            {{-- SECTION 1: DATA UTAMA --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-gray-700 font-bold uppercase text-sm tracking-wider"><i class="fas fa-edit mr-2"></i> Data Utama</h3>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Nama Toko --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Nama Toko</label>
                    <input type="text" name="nama_toko" value="{{ $data->nama_toko }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none uppercase font-bold text-gray-700">
                </div>

                {{-- Lokasi --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Lokasi</label>
                    <textarea name="lokasi" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" rows="2">{{ $data->lokasi }}</textarea>
                </div>

                {{-- Area Sales --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Area Sales</label>
                    <input type="text" name="area_sales" value="{{ $data->area_sales }}" readonly class="w-full bg-gray-100 border border-gray-300 text-gray-500 rounded-lg px-4 py-2 cursor-not-allowed font-medium">
                </div>

                {{-- Nama Sales --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Nama Sales</label>
                    <input type="text" name="nama_sales" value="{{ $data->nama_sales }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                </div>

                {{-- SPV --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Nama SPV</label>
                    <input type="text" name="nama_spv" value="{{ $data->nama_spv }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none uppercase">
                </div>
            </div>

            {{-- SECTION 2: DETAIL BRANDING --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-b border-gray-200">
                <h3 class="text-gray-700 font-bold uppercase text-sm tracking-wider"><i class="fas fa-tools mr-2"></i> Detail Branding</h3>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Brand --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Brand</label>
                    <select name="brand" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                        <option value="SOREX MAN" {{ $data->brand == 'SOREX MAN' ? 'selected' : '' }}>SOREX MAN</option>
                        <option value="SOREX LADIES" {{ $data->brand == 'SOREX LADIES' ? 'selected' : '' }}>SOREX LADIES</option>
                        <option value="SOREX KIDS" {{ $data->brand == 'SOREX KIDS' ? 'selected' : '' }}>SOREX KIDS</option>
                    </select>
                </div>

                {{-- Jenis Permintaan --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Jenis Permintaan</label>
                    <select name="jenis_permintaan" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                        <option value="BARU" {{ $data->jenis_permintaan == 'BARU' ? 'selected' : '' }}>BARU</option>
                        <option value="PEREMAJAAN" {{ $data->jenis_permintaan == 'PEREMAJAAN' ? 'selected' : '' }}>PEREMAJAAN</option>
                    </select>
                </div>

                {{-- Jenis Tools (Searchable) --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Jenis Tools Branding</label>
                    <div class="relative">
                        <input type="text" name="jenis_tools" list="list_tools" value="{{ $data->jenis_tools_branding }}" required autocomplete="off" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none transition placeholder-gray-400 font-medium" placeholder="Ketik untuk mencari...">
                        <datalist id="list_tools">
                            <option value="AKRILIK BENING"></option>
                            <option value="AKRILIK KAPUR / PVC"></option>
                            <option value="ART PAPER / POP"></option>
                            <option value="CUTTING AKRILIK BACKWALL"></option>
                            <option value="IMPRABOARD"></option>
                            <option value="NEONBOX"></option>
                            <option value="POSTER DOFF"></option>
                            <option value="POSTER GLOSSY"></option>
                            <option value="PVC BOARD"></option>
                            <option value="ROLL UP BANNER"></option>
                            <option value="SPANDUK CHINA"></option>
                            <option value="SPANDUK CHINA (PAKAI MATA AYAM)"></option>
                            <option value="SPANDUK KOREA (PAKAI MATA AYAM)"></option>
                            <option value="STIKER BACKLITE (UNTUK NEONBOX)"></option>
                            <option value="STIKER DOFF"></option>
                            <option value="STIKER GLOSSY"></option>
                            <option value="STIKER ONEWAY"></option>
                            <option value="STIKER OVERPRINT (UNTUK NEONBOX)"></option>
                            <option value="TRIPOD BANNER"></option>
                            <option value="WOBBLER"></option>
                            <option value="X BANNER"></option>
                        </datalist>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400"><i class="fas fa-search"></i></div>
                    </div>
                </div>

                {{-- Ukuran --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Ukuran</label>
                    <input type="text" name="ukuran" value="{{ $data->ukuran_tools_branding }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                </div>

                {{-- Qty --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">QTY</label>
                    <input type="number" name="qty" value="{{ $data->qty_tools }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                </div>

                {{-- Pengiriman --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Pengiriman</label>
                    <input type="text" name="pengiriman" value="{{ $data->pengiriman }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                </div>

                {{-- Keterangan --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">{{ $data->keterangan_tambahan }}</textarea>
                </div>
            </div>

            {{-- SECTION 3: UPDATE FOTO --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-b border-gray-200">
                <h3 class="text-gray-700 font-bold uppercase text-sm tracking-wider"><i class="fas fa-camera mr-2"></i> Update Foto (Opsional)</h3>
            </div>

            <div class="p-6">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
                    <p class="text-sm text-yellow-700">
                        <strong>Perhatian:</strong> Jika Anda mengupload foto baru di bawah ini, <b>foto lama akan terhapus/terganti</b>. 
                        Jika tidak ingin mengubah foto, biarkan kolom ini kosong.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Ganti Foto Area (Max 5)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition">
                            <input type="file" name="foto_area[]" multiple class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Ganti Foto Sugest (Max 5)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition">
                            <input type="file" name="foto_sugest[]" multiple class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="bg-gray-100 px-6 py-4 border-t border-gray-200 flex justify-end">
                <button type="submit" class="btn-sorex text-white font-bold py-3 px-10 rounded-lg shadow-lg flex items-center transform active:scale-95 transition">
                    <i class="fas fa-save mr-2"></i> SIMPAN REVISI
                </button>
            </div>

        </form>
    </main>

    {{-- MODAL PROFILE POPUP (SAMA DENGAN PAGE LAIN) --}}
    <div x-show="showProfileModal" style="display: none;" 
         class="fixed inset-0 z-[110] flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90">
        
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 relative flex flex-col items-center text-center" @click.away="showProfileModal = false">
            <button @click="showProfileModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-red-100 shadow-xl mb-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true&size=128" alt="Profile" class="w-full h-full object-cover">
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h2>
            <div class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-bold mb-4 uppercase tracking-wider">{{ Auth::user()->regional ?? 'User' }}</div>
            <div class="w-full border-t border-gray-100 pt-4">
                <p class="text-gray-500 text-sm mb-1">Alamat Email:</p>
                <p class="text-gray-800 font-medium break-all">{{ Auth::user()->email }}</p>
            </div>
            <button @click="showProfileModal = false" class="mt-6 w-full py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">Tutup</button>
        </div>
    </div>

    {{-- Footer --}}
    <div class="text-center mt-8 text-gray-400 text-xs pb-8">
        &copy; 2025 SOREX Branding System. All Rights Reserved.
    </div>

    @include('components.keep-alive')
</body>
</html>