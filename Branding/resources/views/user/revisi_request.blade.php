<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisi Data Branding - SOREX</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    
    {{-- Google Font: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; transform: translateY(-2px); shadow: 0 4px 6px rgba(0,0,0,0.1); }

        /* Style Lengkungan Background */
        .curved-bg {
            background-color: #f3f4f6;
            border-top-left-radius: 50% 20%;
            border-top-right-radius: 50% 20%;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 75%; 
            z-index: 10;
        }
        
        /* Media Query untuk iPad & Desktop */
        @media (min-width: 768px) {
            .curved-bg {
                 border-top-left-radius: 50% 100%;
                 border-top-right-radius: 0;
                 height: 85%;
                 width: 70%;
                 right: 0;
                 left: auto;
            }
        }
        
        [x-cloak] { display: none !important; }
        
        /* Animasi Slide Up untuk Card */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUp 0.6s ease-out forwards; }
    </style>
</head>

{{-- Tambahkan x-data agar navbar mobile & profil berfungsi --}}
<body x-data="{ open: false, showProfileModal: false }" class="bg-sorex min-h-screen flex flex-col relative overflow-x-hidden selection:bg-red-200 selection:text-red-900">

    {{-- =========================================
         1. BACKGROUND ELEMENTS
         ========================================= --}}
    <div class="fixed inset-0 z-0 bg-sorex">
        {{-- Background Image Overlay --}}
        <img src="{{ asset('img/bg3.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-10 mix-blend-overlay" onerror="this.style.display='none'"> 
    </div>

    {{-- LENGKUNGAN PUTIH --}}
    <div class="curved-bg shadow-2xl"></div>


    {{-- =========================================
         2. NAVBAR / HEADER (UPDATED - LEBIH RINGKAS)
         ========================================= --}}
    <header class="w-full py-3 px-4 sm:px-10 flex justify-between items-center text-white z-50 relative bg-sorex shadow-md"> {{-- Padding dan Shadow disesuaikan --}}
        <div class="flex items-center z-50">
            <a href="{{ route('user.home') }}">
                <img src="{{ asset('img/logo5.png') }}" alt="SOREX Logo" class="h-8 md:h-12 w-auto drop-shadow-md" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"> {{-- Logo lebih kecil di mobile --}}
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-widest italic drop-shadow-md hidden">SOREX</h1>
            </a>
        </div>

        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-xl"></i> {{-- Icon lebih kecil --}}
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
         3. MAIN CONTENT (Card Revisi)
         ========================================= --}}
    <main class="flex-1 flex flex-col justify-center items-center px-4 z-20 relative w-full py-8">
        
        {{-- Card Container (Animasi Slide Up) --}}
        {{-- Responsif: flex-col (HP) -> flex-row (iPad/Desktop) --}}
        <div class="bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row animate-slide-up border border-gray-100">

            {{-- BAGIAN KIRI: Form Input --}}
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative">
                
                {{-- Header Card --}}
                <div class="mb-4">
                    <span class="inline-block p-3 bg-red-50 rounded-2xl text-sorex mb-4 shadow-sm">
                        <i class="fas fa-edit text-2xl"></i>
                    </span>
                    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Revisi Data</h1>
                    <p class="text-gray-500 mb-6 leading-relaxed text-sm">
                        Masukkan <strong>ID Request</strong> Anda untuk menemukan data yang ingin diperbaiki.
                    </p>
                </div>

                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-sorex text-red-700 p-4 rounded-r-lg mb-6 shadow-sm flex items-start animate-pulse">
                        <i class="fas fa-exclamation-circle mt-1 mr-3 text-lg"></i>
                        <div>
                            <p class="font-bold text-sm">Terjadi Kesalahan</p>
                            <p class="text-xs">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                {{-- FORM PENCARIAN --}}
                <form action="{{ route('user.request.check') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2 text-xs uppercase tracking-wider">ID Request / Tiket</label>
                        <div class="relative group">
                            {{-- Icon Hashtag --}}
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-sorex transition">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            
                            {{-- INPUT ID DENGAN AUTO UPPERCASE --}}
                            <input type="text" 
                                   name="request_id" 
                                   required 
                                   oninput="this.value = this.value.toUpperCase()"
                                   class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl py-3 pl-10 pr-4 text-gray-800 font-bold placeholder-gray-400 focus:outline-none focus:border-sorex focus:bg-white transition-all uppercase tracking-widest shadow-inner text-lg" 
                                   placeholder="CONTOH: BS123">
                        </div>
                    </div>

                    {{-- Tombol Cari --}}
                    <button type="submit" class="w-full btn-sorex text-white font-bold py-3 rounded-xl shadow-lg flex items-center justify-center group text-lg transform active:scale-95 transition">
                        Cari Data 
                        <i class="fas fa-search ml-3 transform group-hover:scale-110 transition"></i>
                    </button>
                </form>
                
                {{-- Tombol Batal Khusus Mobile (Agar User mudah kembali) --}}
                <div class="mt-6 text-center md:hidden">
                    <a href="{{ route('user.home') }}" class="text-gray-400 hover:text-sorex text-sm font-semibold transition flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Batal & Kembali
                    </a>
                </div>
            </div>

            {{-- BAGIAN KANAN: Gambar Ilustrasi --}}
            {{-- LOGIKA: 'hidden' di HP, 'flex' di iPad (md) ke atas --}}
            <div class="hidden md:flex w-1/2 bg-gradient-to-br from-red-700 to-red-900 relative items-center justify-center p-10 text-center overflow-hidden">
                
                {{-- Dekorasi Bulatan Blur --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-black opacity-20 rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>

                <div class="relative z-10 text-white">
                    {{-- Ilustrasi Ikon Besar --}}
                    <div class="mb-6 animate-bounce">
                        <i class="fas fa-clipboard-check text-8xl drop-shadow-xl opacity-90"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold mb-2">Pastikan Data Valid</h2>
                    <p class="text-red-100 text-sm opacity-90 leading-relaxed max-w-xs mx-auto">
                        Periksa kembali ID tiket Anda. Pastikan data yang direvisi sudah sesuai agar proses approval berjalan lancar.
                    </p>
                    
                    {{-- Dots Decoration --}}
                    <div class="mt-8 flex justify-center space-x-2">
                        <div class="w-2 h-2 bg-white rounded-full opacity-40"></div>
                        <div class="w-2 h-2 bg-white rounded-full opacity-100"></div>
                        <div class="w-2 h-2 bg-white rounded-full opacity-40"></div>
                    </div>
                </div>
            </div>

        </div>

    </main>

    {{-- FOOTER --}}
    <footer class="w-full text-center py-4 text-xs font-medium text-black/60 z-50 relative">
        &copy; 2025 SOREX Branding System. All Rights Reserved.
    </footer>

    {{-- SweetAlert Logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Pop-up jika ada error (Data sudah pernah direvisi / tidak ditemukan)
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: "{{ $errors->first() }}",
                    confirmButtonColor: '#d71920',
                    confirmButtonText: 'Saya Mengerti',
                    footer: '<a href="https://wa.me/NOMOR_ADMIN_DISINI" class="text-sorex font-bold text-xs" target="_blank"><i class="fab fa-whatsapp"></i> Hubungi Admin Sorex</a>'
                });
            @endif

            // Pop-up jika sukses
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#d71920'
                });
            @endif
        });
    </script>

    {{-- MODAL PROFILE POPUP (Sama seperti Home) --}}
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

    @include('components.keep-alive')
</body>
</html>