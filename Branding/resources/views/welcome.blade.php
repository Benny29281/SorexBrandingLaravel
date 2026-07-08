<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - SOREX Branding</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Font Google & Icon --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bar2.png') }}">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* 1. ANIMASI BACKGROUND BERGERAK */
        .bg-animated {
            background: linear-gradient(-45deg, #d71920, #8a0f13, #ff4d4d, #d71920);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* 2. ANIMASI ELEMEN MUNCUL */
        .animate-enter {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-100 { animation-delay: 0.2s; }
        .delay-200 { animation-delay: 0.4s; }
        .delay-300 { animation-delay: 0.6s; }
    </style>
</head>
<body class="bg-animated h-screen flex items-center justify-center overflow-hidden relative">

    {{-- Overlay Pattern --}}
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

    <div class="relative z-10 text-center px-4 w-full max-w-4xl">
        
        {{-- LOGO AREA (UPDATED) --}}
        <div class="mb-8 animate-enter">
            <div class="inline-block bg-white p-6 rounded-3xl shadow-2xl transform transition hover:scale-105 duration-500">
                
                {{-- GANTI 'logo.png' DENGAN NAMA FILE LOGO ANDA YANG ADA DI FOLDER public/img --}}
                <img src="{{ asset('img/logooo.png') }}" 
                     alt="Logo SOREX" 
                     class="h-16 md:h-24 w-auto object-contain"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                
                {{-- Fallback Teks (Jika gambar tidak ketemu) --}}
                <h1 class="text-4xl font-extrabold text-[#d71920] hidden">SOREX</h1>
            
            </div>
        </div>

        {{-- TEXT AREA --}}
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-2 drop-shadow-md animate-enter delay-100">
            Website Branding
        </h1>
        <p class="text-white/90 text-lg md:text-xl mb-12 animate-enter delay-200 font-light">
            Kelola request, desain, dan monitoring dalam satu Tujuan.
        </p>

        {{-- BUTTON AREA --}}
        @if (Route::has('login'))
            <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-6 animate-enter delay-300">
                @auth
                    <a href="{{ url('login') }}" class="group relative px-8 py-3 bg-white text-[#d71920] font-bold rounded-full shadow-lg overflow-hidden w-64 transition-all hover:scale-105">
                        <span class="relative z-10">LOG IN <i class="fas fa-arrow-right ml-2"></i></span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="group px-10 py-3 bg-white text-[#d71920] font-bold rounded-full shadow-xl hover:bg-gray-50 hover:scale-105 transition transform duration-300 w-64 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i> LOG IN
                    </a>


                    {{-- @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-10 py-3 bg-transparent border-2 border-white text-white font-bold rounded-full hover:bg-white/10 hover:scale-105 transition transform duration-300 w-64 flex items-center justify-center">
                            REGISTER
                        </a>
                    @endif --}}
                @endauth
            </div>
        @endif

    </div>

    {{-- Footer Kecil --}}
    <div class="absolute bottom-6 text-white/60 text-xs animate-enter delay-300">
        &copy; {{ date('Y') }} SOREX Branding. All Rights Reserved.
    </div>

</body>
</html>