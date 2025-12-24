<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User Baru - Sorex Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" href="bar2.png" type="image/png">

    <style>
        .bg-sidebar { background-color: #3d3d3d; }
        .bg-sidebar-active { background-color: #e02222; }
        .bg-header { background-color: #2b2b2b; }
        body { background-color: #f3f4f6; font-family: 'Segoe UI', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans">

    {{-- 1. PANGGIL SIDEBAR --}}
    @include('layouts.sidebar_admin')

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        {{-- 2. HEADER --}}
        @include('layouts.header_admin')

        {{-- 3. KONTEN UTAMA --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            
            <div class="flex flex-col justify-center items-center min-h-[80vh]">
                
                {{-- Notifikasi Sukses --}}
                @if(session('success'))
                    <div class="w-full max-w-md bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm animate-fade-in-down">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2 text-lg"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
                {{-- Validasi Error Global --}}
                @if ($errors->any())
                    <div class="w-full max-w-md bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- KARTU REGISTER --}}
                <div class="w-full max-w-md bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                    
                    {{-- Header Kartu --}}
                    <div class="bg-gray-800 px-8 py-6 text-center">
                        <h2 class="text-2xl font-bold text-white mb-1">Register User Baru</h2>
                        <p class="text-gray-400 text-sm">Tambahkan admin atau staff regional</p>
                    </div>

                    {{-- Body Form --}}
                    <div class="p-8">
                        <form method="POST" action="{{ route('admin.register.store') }}">
                            @csrf

                            {{-- Nama Lengkap --}}
                            <div class="mb-5">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    <i class="fas fa-user text-gray-400 mr-1"></i> Nama Lengkap
                                </label>
                                <input class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                                       id="name" type="text" name="name" placeholder="Contoh: Budi Santoso" required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-5">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i> Email Address
                                </label>
                                <input class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                                       id="email" type="email" name="email" placeholder="nama@sorex.co.id" required>
                            </div>

                            {{-- PILIHAN REGIONAL (DROP DOWN) --}}
                            <div class="mb-5">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="regional">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> Pilih Regional
                                </label>
                                <div class="relative">
                                    <select class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200 appearance-none bg-white" 
                                            id="regional" name="regional" required>
                                        <option value="" disabled selected>-- Pilih Regional --</option>
                                        <option value="Regional 1">Regional 1 (JT, DK, LP)</option>
                                        <option value="Regional 2">Regional 2 (JB & JR)</option>
                                        {{-- Tambahkan opsi lain jika perlu, misal Admin --}}
                                        <option value="Admin">Admin</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="mb-5">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i> Password
                                </label>
                                <input class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                                       id="password" type="password" name="password" placeholder="********" required>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="mb-8">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i> Konfirmasi Password
                                </label>
                                <input class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200" 
                                       id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi password" required>
                            </div>

                            {{-- Tombol Submit --}}
                            <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-300 flex justify-center items-center group" type="submit">
                                <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform"></i> Tambahkan User
                            </button>

                        </form>
                    </div>
                </div>

                <p class="text-center text-gray-500 text-xs mt-6">
                    &copy; {{ date('Y') }} Sorex System. All rights reserved.
                </p>

            </div>

        </main>
    </div>

</body>
</html>