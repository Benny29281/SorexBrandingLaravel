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
    
    {{-- Google Font: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Setup Warna Kustom */
        .bg-sorex { background-color: #d71920; }
        .text-sorex { color: #d71920; }
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; transform: translateY(-3px); }

        /* Lengkungan Putih Elegan */
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
<body class="bg-sorex min-h-screen flex flex-col relative overflow-x-hidden selection:bg-red-200 selection:text-red-900">

    {{-- =========================================
         1. GAMBAR BACKGROUND (Paling Belakang)
         ========================================= --}}
    <div class="fixed inset-0 z-0 bg-sorex">
        <img src="{{ asset('img/bg3.jpg') }}" 
             alt="Background Sorex" 
             class="w-full h-full object-cover opacity-10 mix-blend-overlay"
             onerror="this.style.display='none'"> 
    </div>

    {{-- =========================================
         2. LENGKUNGAN PUTIH
         ========================================= --}}
    <div class="curved-bg shadow-2xl"></div>


    {{-- =========================================
         3. NAVBAR RESPONSIF
         ========================================= --}}
    <header x-data="{ open: false }" class="w-full py-4 px-6 sm:px-10 flex justify-between items-center text-white z-50 relative">
        
        {{-- REVISI 1: LOGO IMAGE --}}
        <div class="flex items-center z-50">
            {{-- Ganti 'logo.png' dengan nama file logo Anda --}}
            <img src="{{ asset('img/logo5.png') }}" 
                 alt="SOREX Logo" 
                 class="h-10 md:h-12 w-auto drop-shadow-md"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            
            {{-- Fallback jika gambar logo tidak ada/rusak --}}
            <h1 class="text-3xl font-extrabold tracking-widest italic drop-shadow-md hidden">SOREX</h1>
        </div>

        {{-- Tombol Hamburger --}}
        <button @click="open = !open" class="md:hidden text-white focus:outline-none z-50 p-2 rounded hover:bg-red-800 transition">
            <i x-show="!open" class="fas fa-bars text-2xl"></i>
            <i x-show="open" x-cloak class="fas fa-times text-2xl"></i>
        </button>

        {{-- Menu Desktop --}}
        <nav class="hidden md:flex space-x-8 text-sm font-semibold uppercase tracking-wider items-center">
            <a href="#" class="hover:text-red-200 transition relative group">
                Log Aktivitas
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-white transition-all group-hover:w-full"></span>
            </a>
            <a href="#" class="hover:text-red-200 transition relative group">
                Download Data
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-white transition-all group-hover:w-full"></span>
            </a>
            
            <div class="border-l border-red-300 h-6 mx-2"></div>
            
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-white text-sorex px-5 py-2 rounded-full font-bold hover:bg-gray-100 transition shadow-lg transform hover:scale-105">
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
            
            <a href="#" class="block py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition">
                <i class="fas fa-history mr-2"></i> Log Aktivitas
            </a>
            <a href="#" class="block py-3 hover:bg-white/10 rounded-xl font-bold tracking-wide transition">
                <i class="fas fa-download mr-2"></i> Download Data
            </a>
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
    {{-- =========================================
         4. MAIN CONTENT
         ========================================= --}}
    <main class="flex-1 flex flex-col justify-center items-center px-4 z-20 relative w-full max-w-5xl mx-auto py-10 md:py-0">
        
        <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

            <div class="text-center md:text-left space-y-4 md:pl-10">
                
                <h2 class="text-4xl md:text-5xl font-bold text-white md:text-sorex drop-shadow-md md:drop-shadow-none leading-tight">
                    Branding Request
                </h2>
          
                <p class="text-black text-lg font-bold md:font-medium leading-relaxed bg-white/30 md:bg-transparent p-2 md:p-0 rounded-lg backdrop-blur-sm md:backdrop-blur-none shadow-sm md:shadow-none">
                    Kelola permintaan branding toko, revisi, dan pelacakan status dalam satu tampilan.
                </p>

            </div>

            <div class="flex flex-col space-y-6 items-center md:items-end w-full">

                <div class="bg-white p-6 rounded-3xl shadow-xl w-full max-w-sm border border-gray-100 transform hover:scale-[1.02] transition duration-300">
                    
                    <div class="mb-6 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Input Data Branding</h3>
                        <a href="{{ route('user.request.create') }}" class="btn-sorex text-white w-full py-3 rounded-xl font-bold shadow-lg shadow-red-200 flex items-center justify-center group">
                            {{-- LOGIKA DINAMIS TEKS TOMBOL --}}
                            <span>
                                @if(Auth::user()->regional == 'reg1' || Auth::user()->regional == 'Regional 1')
                                    JT, DK & LP
                                @else
                                    JB & JR
                                @endif
                            </span>
                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition"></i>
                        </a>
                    </div>

                    <div class="border-t border-gray-100 my-4 relative">
                        <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white px-2 text-xs text-gray-400 font-bold uppercase">Atau</span>
                    </div>

                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Revisi Data Branding</h3>
                        <a href="{{ route('user.request.revisi') }}" class="block w-full py-3 rounded-xl font-bold border-2 border-sorex text-sorex hover:bg-red-50 transition">
                            {{-- LOGIKA DINAMIS TEKS TOMBOL REVISI --}}
                            @if(Auth::user()->regional == 'reg1' || Auth::user()->regional == 'Regional 1')
                                JT, DK & LP
                            @else
                                JB & JR
                            @endif
                        </a>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur-sm p-6 rounded-3xl shadow-lg w-full max-w-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-search text-sorex mr-2"></i> Tracking Data Request
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">Cari berdasarkan ID Request, Toko, atau Sales</p>
                    
                    <form action="{{ route('user.request.track') }}" method="GET" class="relative">
                        <input type="text" name="keyword" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-4 pr-12 py-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition text-sm font-medium"
                               {{-- Placeholder dinamis --}}
                               placeholder="Kode: @if(Auth::user()->regional == 'reg1' || Auth::user()->regional == 'Regional 1') BS... @else RB... @endif">
                        <button type="submit" class="absolute right-2 top-2 bottom-2 bg-sorex text-white px-3 rounded-lg hover:bg-red-800 transition">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </main>

    {{-- FOOTER --}}
    <footer class="w-full text-center py-4 text-xs font-medium text-black/60 z-50 relative">
        &copy; 2025 SOREX Branding System. All Rights Reserved.
    </footer>

</body>
</html>