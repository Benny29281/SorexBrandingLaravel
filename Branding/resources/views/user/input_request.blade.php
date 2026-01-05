<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Request Branding - SOREX</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>

    {{-- SweetAlert2 (Wajib untuk Popup ID) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
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

{{-- Tambahkan x-data agar navbar mobile & profil berfungsi --}}
<body x-data="{ open: false, showProfileModal: false }" class="bg-gray-100 font-sans min-h-screen flex flex-col">

    {{-- =========================================
         NAVBAR
         ========================================= --}}
    <header class="w-full py-4 px-6 sm:px-10 flex justify-between items-center text-white z-50 relative bg-sorex shadow-md">
        <div class="flex items-center z-50">
            {{-- Klik Logo kembali ke Home --}}
            <a href="{{ route('user.home') }}">
                <img src="{{ asset('img/logo5.png') }}" alt="SOREX Logo" class="h-10 md:h-12 w-auto drop-shadow-md" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <h1 class="text-3xl font-extrabold tracking-widest italic drop-shadow-md hidden">SOREX</h1>
            </a>
        </div>

        {{-- Hamburger Mobile --}}
        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-2xl"></i>
            <i x-show="open" x-cloak class="fas fa-times text-2xl"></i>
        </button>

        {{-- Menu Desktop --}}
        <nav class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider items-center">
            
            <a href="{{ route('user.home') }}" class="hover:text-red-200 transition flex items-center">
                <i class="fas fa-home mr-2"></i> Home
            </a>
            
            <a href="{{ route('user.log') }}" class="hover:text-red-200 transition relative group flex items-center">
                <i class="fas fa-history mr-2"></i> Log Aktivitas
            </a>
            
            <a href="{{ route('user.download.page') }}" class="text-white font-bold text-sm hover:underline flex items-center uppercase tracking-wider">
                <i class="fas fa-file-download mr-2"></i> Download Data
            </a>
            
            <div class="border-l border-red-300 h-6 mx-2"></div>
            
            {{-- PROFILE BULAT --}}
            <div class="relative group cursor-pointer mr-2" @click="showProfileModal = true">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-lg bg-white transform group-hover:scale-110 transition duration-300">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" 
                         alt="Profile" class="w-full h-full object-cover">
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-white text-sorex px-5 py-2 rounded-full font-bold hover:bg-gray-100 transition shadow-lg transform hover:scale-105 text-xs">
                    Log Out <i class="fas fa-sign-out-alt ml-1"></i>
                </button>
            </form>
        </nav>

        {{-- Menu Mobile --}}
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-full"
             x-cloak
             class="absolute top-0 left-0 w-full bg-red-900/95 backdrop-blur-md shadow-2xl md:hidden pt-24 pb-8 px-6 flex flex-col space-y-4 text-center z-40 border-b border-red-700">
            
            {{-- Profile Mobile --}}
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

    {{-- MAIN CONTENT --}}
    <main class="flex-grow container mx-auto px-4 py-8 max-w-5xl">

        {{-- Judul Halaman --}}
        <div class="mb-6 text-center md:text-left border-b border-gray-300 pb-4 flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Form Pengajuan Branding</h1>
                <p class="text-gray-500 text-sm mt-1">Silakan lengkapi data di bawah ini. ID Tiket akan muncul setelah submit.</p>
            </div>
            <a href="{{ route('user.home') }}" class="hidden md:flex items-center text-sorex font-bold hover:text-red-800 transition text-sm mt-4 md:mt-0">
                <i class="fas fa-arrow-left mr-2"></i> KEMBALI KE HOME
            </a>
        </div>

        {{-- FORM CONTAINER --}}
        <form action="{{ route('user.request.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg overflow-hidden">
            @csrf

            {{-- BAGIAN 1: IDENTITAS & REGIONAL --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-gray-700 font-bold uppercase text-sm tracking-wider"><i class="fas fa-id-card mr-2"></i> Identitas Pengajuan</h3>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Email (Readonly) --}}
                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-2">Email</label>
                    <input type="text" value="{{ Auth::user()->email }}" readonly class="w-full bg-gray-100 border border-gray-300 text-gray-500 rounded-lg px-4 py-2 cursor-not-allowed">
                </div>

                {{-- Nama Sales --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Nama Sales <span class="text-red-500">*</span></label>
                    <input type="text" 
                           name="nama_sales" 
                           value="{{ old('nama_sales', Auth::user()->name) }}" 
                           required 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" 
                           placeholder="Masukkan Nama Sales">
                </div>

                {{-- Area Sales (Otomatis Filter) --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Area Sales <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="area_sales" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex focus:ring-1 focus:ring-red-200 outline-none appearance-none bg-white">
                            <option value="" disabled selected>-- Pilih Area --</option>

                            @if(Auth::user()->regional == 'reg1' || Auth::user()->regional == 'Regional 1')
                                <optgroup label="Regional 1">
                                    <option value="JT">JT</option>
                                    <option value="DK">DK</option>
                                    <option value="LP">LP</option>
                                </optgroup>
                            @endif

                            @if(Auth::user()->regional == 'reg2' || Auth::user()->regional == 'Regional 2')
                                <optgroup label="Regional 2">
                                    <option value="JB">JB</option>
                                    <option value="JR">JR</option>
                                </optgroup>
                            @endif
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Nama SPV (SEKARANG WAJIB) --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Nama SPV <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_spv" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none uppercase" placeholder="Nama Supervisor">
                </div>
            </div>

            {{-- BAGIAN 2: DATA TOKO --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-b border-gray-200">
                <h3 class="text-gray-700 font-bold uppercase text-sm tracking-wider"><i class="fas fa-store mr-2"></i> Data Toko</h3>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Toko --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Nama Toko <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_toko" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none uppercase" placeholder="CONTOH: TOKO MAJU JAYA">
                </div>

                {{-- Lokasi / Alamat --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Lokasi <span class="text-red-500">*</span></label>
                    <textarea name="lokasi" required rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" placeholder="Alamat toko (Nama Kota / nama Daerah)..."></textarea>
                </div>
            </div>

            {{-- BAGIAN 3: DETAIL REQUEST --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-b border-gray-200">
                <h3 class="text-gray-700 font-bold uppercase text-sm tracking-wider"><i class="fas fa-tools mr-2"></i> Detail Tools & Brand</h3>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Brand --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Brand <span class="text-red-500">*</span></label>
                    <select name="brand" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                        <option value="" disabled selected>-- Pilih Brand --</option>
                        <option value="SOREX MAN">SOREX MAN</option>
                        <option value="SOREX LADIES">SOREX LADIES</option>
                        <option value="SOREX KIDS">SOREX KIDS</option>
                    </select>
                </div>

                {{-- Jenis Permintaan --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Jenis Permintaan <span class="text-red-500">*</span></label>
                    <select name="jenis_permintaan" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                        <option value="" disabled selected>-- Pilih Jenis --</option>
                        <option value="BARU">Baru</option>
                        <option value="PEREMAJAAN">Peremajaan</option>
                    </select>
                </div>

                {{-- Jenis Tools (Datalist) --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Jenis Tools Branding <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="jenis_tools" list="list_tools" required autocomplete="off" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none transition placeholder-gray-400" placeholder="Ketik untuk mencari (Contoh: Stiker...)">
                        <datalist id="list_tools">
                            <option value="SPANDUK KOREA"></option>
                            <option value="SPANDUK KOREA (PAKAI MATA AYAM)"></option>
                            <option value="SPANDUK CHINA"></option>
                            <option value="SPANDUK CHINA (PAKAI MATA AYAM)"></option>
                            <option value="PVC BOARD"></option>
                            <option value="NEONBOX"></option>
                            <option value="STIKER BACKLITE (UNTUK NEONBOX)"></option>
                            <option value="STIKER OVERPRINT (UNTUK NEONBOX)"></option>
                            <option value="STIKER DOFF"></option>
                            <option value="STIKER GLOSSY"></option>
                            <option value="STIKER ONEWAY"></option>
                            <option value="POSTER DOFF"></option>
                            <option value="POSTER GLOSSY"></option>
                            <option value="AKRILIK KAPUR / PVC"></option>
                            <option value="AKRILIK BENING"></option>
                            <option value="IMPRABOARD"></option>
                            <option value="ART PAPER / POP"></option>
                            <option value="X BANNER"></option>
                            <option value="ROLL UP BANNER"></option>
                            <option value="TRIPOD BANNER"></option>
                            <option value="WOBBLER"></option>
                            <option value="CUTTING AKRILIK BACKWALL"></option>
                            <option value="Lainnya"></option>
                        </datalist>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1"><i>*Tips: Ketik kata kunci, opsi akan muncul otomatis.</i></p>
                </div>

                {{-- Ukuran (SEKARANG WAJIB) --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Ukuran Tools Branding <span class="text-red-500">*</span></label>
                    <input type="text" name="ukuran" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" placeholder="Contoh: 300cm x 100cm">
                </div>

                {{-- Qty --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">QTY Tools <span class="text-red-500">*</span></label>
                    <input type="number" name="qty" value="1" min="1" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                </div>

                {{-- Pengiriman (SEKARANG WAJIB) --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Pengiriman / Ekspedisi <span class="text-red-500">*</span></label>
                    <input type="text" name="pengiriman" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" placeholder="Contoh: Kirim via Kobra / Bawa Sendiri">
                </div>

                {{-- Keterangan Tambahan (TETAP OPSIONAL) --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Keterangan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea name="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" placeholder="Catatan khusus untuk desain atau pengiriman..."></textarea>
                </div>
            </div>

            {{-- BAGIAN 4: UPLOAD FOTO --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-b border-gray-200">
                <h3 class="text-gray-700 font-bold uppercase text-sm tracking-wider"><i class="fas fa-camera mr-2"></i> Upload Foto</h3>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Foto Area (WAJIB) --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">
                        Foto Area Pemasangan <span class="text-red-500">*</span>
                        <span class="text-xs font-normal text-gray-500 block">(Maksimal 5 Foto)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition">
                        <input type="file" 
                               name="foto_area[]" 
                               required
                               multiple 
                               accept="image/*"
                               onchange="checkMaxFiles(this, 5)"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG (Max 2MB/foto). Tahan CTRL/Shift untuk pilih banyak.</p>
                    </div>
                </div>

                {{-- Foto Suggest (TETAP OPSIONAL) --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">
                        Foto Referensi Design <span class="text-gray-400 font-normal">(Opsional)</span>
                        <span class="text-xs font-normal text-gray-500 block">(Maksimal 5 Foto)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50 transition">
                        <input type="file" 
                               name="foto_sugest[]" 
                               multiple 
                               accept="image/*"
                               onchange="checkMaxFiles(this, 5)"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG (Max 2MB/foto). Tahan CTRL/Shift untuk pilih banyak.</p>
                    </div>
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <div class="bg-gray-100 px-6 py-4 border-t border-gray-200 flex justify-end">
                <button type="submit" class="btn-sorex text-white font-bold py-3 px-8 rounded-lg shadow-lg flex items-center transform active:scale-95 transition">
                    <i class="fas fa-paper-plane mr-2"></i> KIRIM REQUEST
                </button>
            </div>

        </form>

        <div class="text-center mt-8 text-gray-400 text-xs pb-8">
            &copy; 2025 SOREX Branding System. All Rights Reserved.
        </div>

    </main>

    {{-- MODAL PROFILE POPUP --}}
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

    {{-- SCRIPT: Validasi & SweetAlert --}}
    <script>
        function checkMaxFiles(input, max) {
            if (input.files.length > max) {
                Swal.fire({ icon: 'warning', title: 'Terlalu Banyak!', text: 'Maksimal hanya boleh upload ' + max + ' foto sekaligus.', confirmButtonColor: '#d71920' });
                input.value = ""; 
            }
        }

        @if(session('created_id'))
            let timerInterval;
            Swal.fire({
                title: 'BERHASIL TERKIRIM!',
                html: `
                    <div class="text-center">
                        <div class="mb-4 text-green-600 text-5xl"><i class="fas fa-check-circle"></i></div>
                        <p class="mb-4 text-gray-700">Request Branding Anda telah tersimpan.</p>
                        <p class="text-sm text-gray-500 mb-1">Silakan simpan ID ini:</p>
                        <div class="bg-yellow-50 border-2 border-yellow-400 border-dashed rounded-lg p-4 mt-3 cursor-pointer hover:bg-yellow-100 transition" onclick="copyId()">
                            <span class="text-4xl font-black text-red-600 tracking-wider font-mono select-all" id="textId">{{ session('created_id') }}</span>
                            <div class="text-xs text-gray-400 mt-1"><i class="fas fa-copy"></i> Klik untuk menyalin</div>
                        </div>
                        <p class="mt-4 text-sm text-gray-500">Akan kembali ke menu utama dalam <b></b> milidetik.</p>
                    </div>`,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: true,
                confirmButtonText: '<i class="fas fa-home"></i> Kembali Sekarang',
                confirmButtonColor: '#d71920',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => { b.textContent = Swal.getTimerLeft() }, 100)
                },
                willClose: () => { clearInterval(timerInterval) }
            }).then((result) => {
                window.location.href = "{{ route('user.home') }}";
            });

            function copyId() {
                var idTeks = "{{ session('created_id') }}";
                navigator.clipboard.writeText(idTeks);
                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                Toast.fire({ icon: 'success', title: 'Disalin!' });
            }
        @endif

        @if ($errors->any())
            Swal.fire({ icon: 'error', title: 'Gagal Submit!', html: 'Mohon lengkapi data wajib.<br><span class="text-sm text-gray-500">Periksa kolom bertanda bintang (*) merah.</span>', confirmButtonColor: '#d71920' });
        @endif
    </script>

    @include('components.keep-alive')
</body>
</html>