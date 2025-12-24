<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Request Branding - SOREX</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- SweetAlert2 (Wajib untuk Popup ID) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Warna Custom Sorex */
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        .border-sorex { border-color: #d71920; }
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; }
        
        /* Style untuk Select Option agar lebih rapi */
        select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
    </style>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-md border-b-4 border-sorex sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Tombol Kembali --}}
                <div class="flex items-center">
                    <a href="{{ route('user.home') }}" class="flex items-center text-sorex font-bold text-2xl tracking-tighter hover:text-red-800 transition">
                        <i class="fas fa-chevron-left mr-2 text-lg"></i> KEMBALI
                    </a>
                </div>
                
                {{-- User Info --}}
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <div class="text-right hidden md:block">
                        <div class="font-bold text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="text-xs">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="bg-gray-200 p-2 rounded-full">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow container mx-auto px-4 py-8 max-w-5xl">

        {{-- Judul Halaman --}}
        <div class="mb-6 text-center md:text-left border-b border-gray-300 pb-4">
            <h1 class="text-3xl font-bold text-gray-800">Form Pengajuan Branding</h1>
            <p class="text-gray-500 text-sm mt-1">Silakan lengkapi data di bawah ini. ID Tiket akan muncul setelah submit.</p>
        </div>

        {{-- FORM CONTAINER --}}
        <form action="{{ route('user.request.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg overflow-hidden">
            @csrf

            {{-- BAGIAN 1: IDENTITAS & REGIONAL --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-gray-700 font-bold uppercase text-sm tracking-wider"><i class="fas fa-id-card mr-2"></i> Identitas Pengaju</h3>
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

                            {{-- LOGIKA FILTER REGIONAL --}}
                            @if(Auth::user()->regional == 'reg1' || Auth::user()->regional == 'Regional 1')
                                <optgroup label="Regional 1">
                                    <option value="JT">JT (Jawa Tengah)</option>
                                    <option value="DK">DK (DKI Jakarta)</option>
                                    <option value="LP">LP (Luar Pulau)</option>
                                </optgroup>
                            @endif

                            @if(Auth::user()->regional == 'reg2' || Auth::user()->regional == 'Regional 2')
                                <optgroup label="Regional 2">
                                    <option value="JB">JB (Jawa Barat)</option>
                                    <option value="JR">JR (Jawa Timur)</option>
                                </optgroup>
                            @endif
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Nama SPV --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Nama SPV</label>
                    <input type="text" name="nama_spv" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none uppercase" placeholder="Nama Supervisor">
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
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Lokasi / Alamat Toko <span class="text-red-500">*</span></label>
                    <textarea name="lokasi" required rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" placeholder="Alamat lengkap toko (Jalan, Nomor, Kota)..."></textarea>
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
                        <option value="BARU">Baru (Pasang Baru)</option>
                        <option value="PEREMAJAAN">Peremajaan (Ganti Lama)</option>
                    </select>
                </div>

                 {{-- =========================================================
                     REVISI: INPUT TEXT DENGAN DATALIST (SEARCHABLE)
                     ========================================================= --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Jenis Tools Branding <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" 
                               name="jenis_tools" 
                               list="list_tools" 
                               required 
                               autocomplete="off"
                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none transition placeholder-gray-400"
                               placeholder="Ketik untuk mencari (Contoh: Stiker...)">
                        
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
                {{-- ========================================================= --}}
                {{-- Ukuran --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Ukuran Tools Branding</label>
                    <input type="text" name="ukuran" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" placeholder="Contoh: 300cm x 100cm">
                </div>

                {{-- Qty --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">QTY Tools <span class="text-red-500">*</span></label>
                    <input type="number" name="qty" value="1" min="1" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none">
                </div>

                {{-- Pengiriman --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Pengiriman / Ekspedisi</label>
                    <input type="text" name="pengiriman" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" placeholder="Contoh: Kirim via Kobra / Bawa Sendiri">
                </div>

                {{-- Keterangan Tambahan --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none" placeholder="Catatan khusus untuk desain atau pengiriman..."></textarea>
                </div>

            </div>

            {{-- BAGIAN 4: UPLOAD FOTO --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-b border-gray-200">
                <h3 class="text-gray-700 font-bold uppercase text-sm tracking-wider"><i class="fas fa-camera mr-2"></i> Upload Foto</h3>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Foto Area --}}
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

                {{-- Foto Suggest --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-2 text-sm">
                        Foto Sugest Design (Referensi)
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

    {{-- SCRIPT: Validasi & SweetAlert Popup --}}
    <script>
        // 1. Validasi Maksimal Jumlah File
        function checkMaxFiles(input, max) {
            if (input.files.length > max) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Terlalu Banyak!',
                    text: 'Maksimal hanya boleh upload ' + max + ' foto sekaligus.',
                    confirmButtonColor: '#d71920'
                });
                input.value = ""; // Reset input
            }
        }

        // 2. SweetAlert: Popup SUKSES dengan ID REQUEST (PENTING)
        @if(session('created_id'))
            Swal.fire({
                title: 'BERHASIL TERKIRIM!',
                html: `
                    <div class="text-center">
                        <div class="mb-4 text-green-600 text-5xl"><i class="fas fa-check-circle"></i></div>
                        <p class="mb-4 text-gray-700">Request Branding Anda telah tersimpan.</p>
                        <p class="text-sm text-gray-500 mb-1">Silakan simpan ID ini untuk <b>Revisi</b> & <b>Tracking</b>:</p>
                        <div class="bg-yellow-50 border-2 border-yellow-400 border-dashed rounded-lg p-4 mt-3 cursor-pointer hover:bg-yellow-100 transition" onclick="copyId()">
                            <span class="text-4xl font-black text-red-600 tracking-wider font-mono select-all" id="textId">
                                {{ session('created_id') }}
                            </span>
                            <div class="text-xs text-gray-400 mt-1"><i class="fas fa-copy"></i> Klik untuk menyalin</div>
                        </div>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: '<i class="fas fa-times"></i> Tutup',
                confirmButtonColor: '#333',
                allowOutsideClick: false,
                allowEscapeKey: false
            });

            // Fungsi Helper untuk Copy ID saat diklik
            function copyId() {
                var idTeks = "{{ session('created_id') }}";
                navigator.clipboard.writeText(idTeks);
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'ID ' + idTeks + ' berhasil disalin!'
                });
            }
        @endif

        // 3. SweetAlert: Popup ERROR Validasi (Jika ada data kosong)
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal Submit!',
                html: 'Mohon lengkapi semua data form yang wajib diisi.<br><span class="text-sm text-gray-500">Periksa kolom bertanda bintang (*) merah.</span>',
                confirmButtonColor: '#d71920'
            });
        @endif
    </script>

</body>
</html>