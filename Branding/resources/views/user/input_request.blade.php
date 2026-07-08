<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Request Branding - SOREX</title>
    
    {{-- Library CSS & JS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bar2.png') }}">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        .border-sorex { border-color: #d71920; }
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; }
        /* Custom Select Arrow */
        select { 
            -webkit-appearance: none; 
            -moz-appearance: none; 
            appearance: none; 
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
        }
        [x-cloak] { display: none !important; }

        /* Custom File Input Style */
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }
        .file-input-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
    </style>
</head>

{{-- Menambahkan state 'submitting' untuk loading animation --}}
<body x-data="{ open: false, showProfileModal: false, submitting: false }" class="bg-gray-50 font-sans min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <header class="w-full py-3 px-4 sm:px-10 flex justify-between items-center text-white z-50 relative bg-sorex shadow-md">
        <div class="flex items-center z-50">
            <a href="{{ route('user.home') }}">
                {{-- Pastikan path logo benar --}}
                <img src="{{ asset('img/logo5.png') }}" alt="SOREX Logo" class="h-8 md:h-12 w-auto drop-shadow-md" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-widest italic drop-shadow-md hidden">SOREX</h1>
            </a>
        </div>

        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-xl"></i>
            <i x-show="open" x-cloak class="fas fa-times text-xl"></i>
        </button>

        <nav class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider items-center">
            <a href="{{ route('user.home') }}" class="hover:text-red-200 transition flex items-center"><i class="fas fa-home mr-2"></i> Home</a>
            <a href="{{ route('user.log') }}" class="hover:text-red-200 transition relative group flex items-center"><i class="fas fa-history mr-2"></i> Log Aktivitas</a>
            <div class="border-l border-red-300 h-6 mx-2"></div>
            
            <div class="relative group cursor-pointer mr-2" @click="showProfileModal = true">
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
            <div class="border-t border-white/20 pt-4 mt-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-white text-sorex rounded-xl font-bold shadow-md active:scale-95 transition">Log Out</button>
                </form>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow container mx-auto px-4 py-8 max-w-3xl"> 

        <div class="mb-6 text-center border-b border-gray-200 pb-4">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800">Form Pengajuan Branding</h1>
            <p class="text-gray-500 text-sm mt-1">Pengajuan request data branding sorex.</p>
        </div>

        {{-- GLOBAL ERROR ALERT (Opsional: Muncul jika ada error apapun) --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Terjadi Kesalahan!</p>
                <p class="text-sm">Mohon periksa kembali form isian di bawah yang berwarna merah.</p>
            </div>
        @endif

        {{-- FORM INPUT --}}
        {{-- Menambahkan @submit="submitting = true" untuk mengaktifkan loading state --}}
        <form action="{{ route('user.request.store') }}" method="POST" enctype="multipart/form-data" 
              @submit="submitting = true"
              class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf

            {{-- 1. IDENTITAS --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-white">
                <h3 class="text-gray-700 font-bold uppercase text-xs tracking-wider flex items-center">
                    <i class="fas fa-id-card mr-2 text-gray-400"></i> Identitas
                </h3>
            </div>
            
            <div class="p-6 space-y-4">
                
                {{-- Email --}}
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Email</label>
                    <input type="text" value="{{ Auth::user()->email }}" readonly class="w-full bg-gray-100 border border-transparent text-gray-500 rounded-lg px-4 py-3 text-sm font-medium focus:outline-none focus:ring-0 cursor-not-allowed">
                </div>

                {{-- Area Sales --}}
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Area Sales</label>
                    <input type="text" name="area_sales" id="area_sales" value="{{ Auth::user()->regional }}" readonly class="w-full bg-gray-100 border border-transparent text-gray-800 font-bold rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-0 cursor-not-allowed">
                </div>

                {{-- Nama SPV --}}
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-2">Nama SPV</label>
                    <input type="text" name="nama_spv" value="{{ Auth::user()->name }}" readonly class="w-full bg-gray-100 border border-transparent text-gray-800 font-bold rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-0 cursor-not-allowed">
                </div>

                {{-- Nama Sales --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Nama Sales <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="nama_sales" list="list_sales_filtered" required autocomplete="off" value="{{ old('nama_sales') }}"
                               class="w-full bg-white border @error('nama_sales') border-red-500 @else border-gray-200 @enderror rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition placeholder-gray-400" 
                               placeholder="Pilih Sales...">
                        <datalist id="list_sales_filtered"></datalist>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1 ml-1">*Menampilkan sales area user</p>
                    
                    {{-- Error Message --}}
                    @error('nama_sales')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 2. DATA TOKO --}}
            <div class="bg-gray-50/50 px-6 py-4 border-t border-b border-gray-100">
                <h3 class="text-gray-700 font-bold uppercase text-xs tracking-wider flex items-center">
                    <i class="fas fa-store mr-2 text-gray-400"></i> Data Toko
                </h3>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Nama Toko <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_toko" required value="{{ old('nama_toko') }}"
                           class="w-full bg-white border @error('nama_toko') border-red-500 @else border-gray-200 @enderror rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition uppercase placeholder-gray-400" 
                           placeholder="CONTOH: TOKO MAJU JAYA">
                    @error('nama_toko')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Lokasi <span class="text-red-500">*</span></label>
                    <textarea name="lokasi" required rows="1" 
                              class="w-full bg-white border @error('lokasi') border-red-500 @else border-gray-200 @enderror rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition resize-none">{{ old('lokasi') }}</textarea>
                    @error('lokasi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 3. DETAIL BRANDING --}}
            <div class="bg-gray-50/50 px-6 py-4 border-t border-b border-gray-100">
                <h3 class="text-gray-700 font-bold uppercase text-xs tracking-wider flex items-center">
                    <i class="fas fa-tools mr-2 text-gray-400"></i> Detail Branding
                </h3>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Brand <span class="text-red-500">*</span></label>
                        <select name="brand" required class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition">
                            <option value="SOREX MAN" {{ old('brand') == 'SOREX MAN' ? 'selected' : '' }}>SOREX MAN</option>
                            <option value="SOREX LADIES" {{ old('brand') == 'SOREX LADIES' ? 'selected' : '' }}>SOREX LADIES</option>
                            <option value="SOREX KIDS" {{ old('brand') == 'SOREX KIDS' ? 'selected' : '' }}>SOREX KIDS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Jenis Permintaan <span class="text-red-500">*</span></label>
                        <select name="jenis_permintaan" required class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition">
                            <option value="BARU" {{ old('jenis_permintaan') == 'BARU' ? 'selected' : '' }}>Baru</option>
                            <option value="PEREMAJAAN" {{ old('jenis_permintaan') == 'PEREMAJAAN' ? 'selected' : '' }}>Peremajaan</option>
                        </select>
                    </div>
                </div>

                {{-- Jenis Tools --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Jenis Tools Branding <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="jenis_tools" list="list_tools_db" required autocomplete="off" value="{{ old('jenis_tools') }}"
                               class="w-full bg-white border @error('jenis_tools') border-red-500 @else border-gray-200 @enderror rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition placeholder-gray-400" 
                               placeholder="Ketik untuk mencari...">
                        <datalist id="list_tools_db">
                            @foreach($toolsList as $tool)
                                <option value="{{ $tool }}"></option>
                            @endforeach
                        </datalist>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    @error('jenis_tools')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Ukuran</label>
                        <input type="text" name="ukuran" required value="{{ old('ukuran') }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition">
                        @error('ukuran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Qty</label>
                        <input type="number" name="qty" value="{{ old('qty', 1) }}" required class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Pengiriman</label>
                    <input type="text" name="pengiriman" required value="{{ old('pengiriman') }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="2" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm focus:border-sorex focus:ring-1 focus:ring-red-100 outline-none transition">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            {{-- 4. UPLOAD FOTO --}}
            <div class="bg-gray-50/50 px-6 py-4 border-t border-b border-gray-100">
                <h3 class="text-gray-700 font-bold uppercase text-xs tracking-wider flex items-center">
                    <i class="fas fa-camera mr-2 text-gray-400"></i> Upload Foto
                </h3>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Foto Area --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                {{-- Foto Area --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Ganti Foto Area (Max 5)</label>
                    <div class="border-2 border-dashed @error('foto_area') border-red-500 @else border-gray-300 @enderror rounded-lg p-4 text-center hover:bg-gray-50 transition">
                        <input type="file" name="foto_area[]" multiple accept="image/*" required
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer">
                    </div>
                    @error('foto_area') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Foto Sugest --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Ganti Foto Sugest (Max 5)</label>
                    <div class="border-2 border-dashed @error('foto_sugest') border-red-500 @else border-gray-300 @enderror rounded-lg p-4 text-center hover:bg-gray-50 transition">
                        <input type="file" name="foto_sugest[]" multiple accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                    @error('foto_sugest') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            </div>

            <div class="bg-white px-6 py-6 border-t border-gray-100">
                {{-- Tombol dengan Loading State --}}
                <button type="submit" 
                        :disabled="submitting" 
                        :class="{ 'opacity-70 cursor-wait': submitting, 'hover:shadow-xl hover:bg-red-700': !submitting }"
                        class="w-full bg-sorex text-white font-bold py-4 rounded-xl shadow-lg transform active:scale-[0.98] transition flex items-center justify-center text-base tracking-wide">
                    
                    {{-- Tampilan Normal --}}
                    <span x-show="!submitting" class="flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> KIRIM REQUEST
                    </span>

                    {{-- Tampilan Loading --}}
                    <span x-show="submitting" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mengirim Data...
                    </span>
                </button>
            </div>
        </form>
        
        <div class="text-center mt-8 text-gray-400 text-xs pb-10">&copy; 2025 SOREX Branding System.</div>
    </main>

    {{-- MODAL PROFILE --}}
    <div x-show="showProfileModal" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 relative flex flex-col items-center text-center" @click.away="showProfileModal = false">
            <button @click="showProfileModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-red-100 shadow-xl mb-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true&size=128" alt="Profile" class="w-full h-full object-cover">
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h2>
            <div class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-bold mb-4 uppercase tracking-wider">{{ Auth::user()->regional ?? 'User' }}</div>
            <div class="w-full border-t border-gray-100 pt-4"><p class="text-gray-500 text-sm mb-1">Area:</p><p class="text-gray-800 font-medium break-all">{{ Auth::user()->role }}</p></div>
            <button @click="showProfileModal = false" class="mt-6 w-full py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">Tutup</button>
        </div>
    </div>

    {{-- SCRIPT PINTAR --}}
    <script>
        // Mengambil data sales dengan aman menggunakan json_encode dari PHP
        const masterSales = {!! json_encode($salesList) !!}; 
        
        document.addEventListener('DOMContentLoaded', () => {
            const userArea = "{{ Auth::user()->regional }}"; 
            const salesDatalist = document.getElementById('list_sales_filtered');
            salesDatalist.innerHTML = '';
            
            // Debugging di console (tekan F12)
            console.log("User Area:", userArea);

            if(userArea && masterSales && masterSales[userArea]) {
                masterSales[userArea].forEach(item => {
                    let opt = document.createElement('option');
                    // Handle jika item berupa object {name: '...'} atau string biasa
                    opt.value = item.name ? item.name : item; 
                    salesDatalist.appendChild(opt);
                });
            } else {
                console.warn('Tidak ada data sales ditemukan untuk Area: ' + userArea);
            }
        });

        // FUNGSI UPDATE NAMA FILE SAAT UPLOAD
        function updateFileName(input, textId) {
            const textEl = document.getElementById(textId);
            if (input.files && input.files.length > 0) {
                if (input.files.length === 1) {
                    textEl.textContent = input.files[0].name;
                    textEl.classList.remove('text-gray-400');
                    textEl.classList.add('text-gray-800', 'font-medium');
                } else {
                    textEl.textContent = input.files.length + " files selected";
                    textEl.classList.remove('text-gray-400');
                    textEl.classList.add('text-gray-800', 'font-medium');
                }
            } else {
                textEl.textContent = "No file chosen";
                textEl.classList.add('text-gray-400');
                textEl.classList.remove('text-gray-800', 'font-medium');
            }
        }

        @if(session('created_id'))
            Swal.fire({ 
                title: 'BERHASIL DIKIRIM!', 
                html: 'Nomor Request ID Anda:<br><b style="font-size: 1.5em; color: #d71920;">{{ session('created_id') }}</b>', 
                icon: 'success', 
                timer: 3000, 
                showConfirmButton: true,
                confirmButtonColor: '#d71920',
                confirmButtonText: 'OK',
            }).then(() => { 
                window.location.href = "{{ route('user.home') }}"; 
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#d71920'
            });
        @endif
    </script>

    @include('components.keep-alive')
</body>
</html>