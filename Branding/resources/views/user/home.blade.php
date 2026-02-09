<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOREX Branding Portal</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Google Font: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; transform: translateY(-3px); }

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
    </style>
</head>

{{-- LOGIC PHP SEDERHANA DI VIEW UNTUK MEMUDAHKAN PENGECEKAN --}}
@php
    $userReg = strtoupper(trim(Auth::user()->regional));
    $isReg1 = in_array($userReg, ['JT', 'DK', 'LP', 'REGIONAL 1', 'REG1']);
@endphp

<body x-data="{ open: false, showModalDownload: false, showProfileModal: false }" class="bg-sorex min-h-screen flex flex-col relative overflow-x-hidden selection:bg-red-200 selection:text-red-900">

    {{-- BACKGROUND --}}
    <div class="fixed inset-0 z-0 bg-sorex">
        <img src="{{ asset('img/bg3.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-10 mix-blend-overlay" onerror="this.style.display='none'"> 
    </div>

    {{-- LENGKUNGAN --}}
    <div class="curved-bg shadow-2xl"></div>

    {{-- NAVBAR --}}
    <header class="w-full py-4 px-6 sm:px-10 flex justify-between items-center text-white z-50 relative">
        <div class="flex items-center z-50">
            <img src="{{ asset('img/logo5.png') }}" alt="SOREX Logo" class="h-10 md:h-12 w-auto drop-shadow-md" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <h1 class="text-3xl font-extrabold tracking-widest italic drop-shadow-md hidden">SOREX</h1>
        </div>

        {{-- Hamburger Mobile --}}
        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-2xl"></i>
            <i x-show="open" x-cloak class="fas fa-times text-2xl"></i>
        </button>

        {{-- Menu Desktop --}}
        <nav class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider items-center">
            
            <a href="{{ route('user.log') }}" class="hover:text-red-200 transition relative group flex items-center">
                <i class="fas fa-history mr-2"></i> Log Aktivitas
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-white transition-all group-hover:w-full"></span>
            </a>
            
            <button @click="showModalDownload = true" class="text-white font-bold text-sm hover:underline flex items-center focus:outline-none uppercase tracking-wider">
                <i class="fas fa-file-download mr-2"></i> Download Data
            </button>
            
            <div class="border-l border-red-300 h-6 mx-2"></div>
            
            {{-- PROFILE --}}
            <div class="relative group cursor-pointer mr-2" @click="showProfileModal = true">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-lg bg-red-800 transform group-hover:scale-110 transition duration-300">
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
        <div x-show="open" x-cloak class="absolute top-0 left-0 w-full bg-red-900/95 backdrop-blur-md shadow-2xl md:hidden pt-24 pb-8 px-6 flex flex-col space-y-4 text-center z-40 border-b border-red-700">
            
            {{-- Profile Mobile --}}
            <div class="flex flex-col items-center mb-4 border-b border-red-800 pb-4">
                <div class="relative w-16 h-16 rounded-full overflow-hidden border-2 border-white mb-2 bg-red-800 shadow-xl">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" 
                         class="w-full h-full object-cover">
                </div>
                <h3 class="text-white font-bold text-lg tracking-wide">{{ Auth::user()->name }}</h3>
                <p class="text-red-200 text-xs">{{ Auth::user()->email }}</p>
            </div>

            <a href="{{ route('user.log') }}" class="block py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition">
                <i class="fas fa-history mr-2"></i> Log Aktivitas
            </a>
            
            <button @click="showModalDownload = true; open = false" class="w-full py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition text-white uppercase flex items-center justify-center">
                <i class="fas fa-file-download mr-2"></i> Download Data
            </button>

            <div class="border-t border-white/20 pt-4 mt-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-white text-sorex rounded-xl font-bold shadow-md active:scale-95 transition">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col justify-center items-center px-4 z-20 relative w-full max-w-5xl mx-auto py-10 md:py-0">
        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            
            {{-- Bagian Kiri (Teks) --}}
            <div class="text-center md:text-left space-y-4 md:pl-10">
                <h2 class="text-4xl md:text-5xl font-bold text-white md:text-sorex drop-shadow-md md:drop-shadow-none leading-tight">Branding Request</h2>
                <p class="text-black text-lg font-bold md:font-medium leading-relaxed bg-white/30 md:bg-transparent p-2 md:p-0 rounded-lg backdrop-blur-sm md:backdrop-blur-none shadow-sm md:shadow-none">
                    Kelola permintaan branding toko, revisi, dan pelacakan status dalam satu tampilan.
                </p>
            </div>

            {{-- Bagian Kanan (Card) --}}
            <div class="flex flex-col space-y-6 items-center md:items-end w-full">
                
                {{-- Card Input --}}
                <div class="bg-white p-6 rounded-3xl shadow-xl w-full max-w-sm border border-gray-100 transform hover:scale-[1.02] transition duration-300">
                    
                    {{-- BAGIAN ATAS: INPUT --}}
                    <div class="mb-6 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Input Data Branding</h3>
                        
                        <a href="{{ route('user.request.create') }}" class="btn-sorex text-white w-full py-3 rounded-xl font-bold shadow-lg shadow-red-200 flex items-center justify-center group">
                            {{-- PANGGIL LANGSUNG REGIONAL USER (HURUF BESAR) --}}
                            <span>
                                Input Data {{ strtoupper(Auth::user()->regional) }}
                            </span>
                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition"></i>
                        </a>
                    </div>

                    <div class="border-t border-gray-100 my-4 relative">
                        <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white px-2 text-xs text-gray-400 font-bold uppercase">Atau</span>
                    </div>

                    {{-- BAGIAN BAWAH: REVISI --}}
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Revisi Data Branding</h3>
                        
                        <a href="{{ route('user.request.revisi') }}" class="block w-full py-3 rounded-xl font-bold border-2 border-sorex text-sorex hover:bg-red-50 transition">
                            {{-- PANGGIL LANGSUNG REGIONAL USER --}}
                            Revisi Data {{ strtoupper(Auth::user()->regional) }}
                        </a>
                    </div>

                </div>

                {{-- Card Tracking --}}
                <div class="bg-white/90 backdrop-blur-sm p-6 rounded-3xl shadow-lg w-full max-w-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700 mb-1 flex items-center"><i class="fas fa-search text-sorex mr-2"></i> Tracking Data Request</h3>
                    <p class="text-xs text-gray-500 mb-4">Cari berdasarkan ID Request, Toko, atau Sales</p>
                    <form action="{{ route('user.request.track') }}" method="GET" class="relative">
                        
                        {{-- INPUT SEARCH (SUDAH DIPERBAIKI) --}}
                        <input type="text" name="keyword" class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-4 pr-12 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition text-sm font-medium" 
                               placeholder="Kode: @if($isReg1) BS... @else RB... @endif">
                        
                        <button type="submit" class="absolute right-2 top-2 bottom-2 bg-sorex text-white px-3 rounded-lg hover:bg-red-800 transition"><i class="fas fa-search"></i></button>
                    </form>
                </div>

            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="w-full text-center py-4 text-xs font-medium text-black/60 z-50 relative">
        &copy; 2025 SOREX Branding System. All Rights Reserved.
    </footer>

    {{-- MODAL DOWNLOAD --}}
    <div x-show="showModalDownload" style="display: none;" 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4"
         x-cloak>
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative" @click.away="showModalDownload = false">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <i class="fas fa-file-excel text-green-600 text-lg"></i>
                    </div>
                    Download Data
                </h2>
                <button @click="showModalDownload = false" class="text-gray-400 hover:text-red-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('user.download.status') }}" method="GET">
                <div class="space-y-4">
                    {{-- INPUT AREA OTOMATIS BERDASARKAN USER (HIDDEN) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Area Download</label>
                        <div class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 font-bold text-sorex">
                            <i class="fas fa-map-marker-alt mr-2"></i> AREA {{ strtoupper(Auth::user()->regional) }}
                        </div>
                        <input type="hidden" name="area" value="{{ strtoupper(Auth::user()->regional) }}">
                        <p class="text-[10px] text-gray-500 mt-1 italic">*Data yang didownload otomatis sesuai dengan area tugas Anda.</p>
                    </div>

                    {{-- Date Input --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Dari</label>
                            <input type="date" name="start_date" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Sampai</label>
                            <input type="date" name="end_date" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-3">
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button type="button" @click="showModalDownload = false" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 shadow-lg shadow-green-200 transition flex items-center justify-center"><i class="fas fa-download mr-2"></i> Download</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL PROFILE --}}
    <div x-show="showProfileModal" style="display: none;" 
         class="fixed inset-0 z-[110] flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4"
         x-cloak>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 relative flex flex-col items-center text-center" @click.away="showProfileModal = false">
            <button @click="showProfileModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-red-100 shadow-xl mb-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true&size=128" 
                     alt="Profile" class="w-full h-full object-cover">
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h2>
            <div class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-bold mb-4 uppercase tracking-wider">
                {{ Auth::user()->regional ?? 'User' }}
            </div>
            <div class="w-full border-t border-gray-100 pt-4">
                <p class="text-gray-500 text-sm mb-1">Alamat Email:</p>
                <p class="text-gray-800 font-medium break-all">{{ Auth::user()->email }}</p>
            </div>
            <button @click="showProfileModal = false" class="mt-6 w-full py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">
                Tutup
            </button>
        </div>
    </div>

    {{-- SCRIPT ALERT --}}
    <script>
        @if(session('success'))
            Swal.fire({ title: 'BERHASIL!', text: "{{ session('success') }}", icon: 'success', timer: 3000, timerProgressBar: true, showConfirmButton: false });
        @endif
        @if(session('error') || $errors->any())
            Swal.fire({ icon: 'error', title: 'Gagal', text: "{{ session('error') ?? 'Terjadi kesalahan input.' }}", confirmButtonColor: '#d71920' });
        @endif
    </script>
</body>
</html>