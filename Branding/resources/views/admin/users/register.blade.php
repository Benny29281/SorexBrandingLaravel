<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User Baru - Sorex Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bar2.png') }}">

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
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6 flex flex-col justify-center items-center">
            
            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="w-full max-w-5xl bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex items-center">
                    <i class="fas fa-check-circle mr-3 text-lg"></i> 
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            @endif
            
            {{-- Validasi Error --}}
            @if ($errors->any())
                <div class="w-full max-w-5xl bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- KARTU REGISTER (WIDE LAPTOP MODE) --}}
            {{-- max-w-5xl membuat form lebar tapi menyisakan ruang di kiri kanan --}}
            <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                
                {{-- Header Kartu --}}
                <div class="bg-gray-800 px-8 py-5 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-white tracking-wide">Register User Baru</h2>
                        <p class="text-gray-400 text-sm mt-1">Form pendaftaran untuk akses admin atau staff regional</p>
                    </div>
                    <div class="bg-gray-700 p-3 rounded-full text-white shadow-inner">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                </div>

                {{-- Body Form --}}
                <div class="p-8">
                    <form method="POST" action="{{ route('admin.register.store') }}">
                        @csrf

                        {{-- GRID LAYOUT: 2 KOLOM AGAR HEMAT TEMPAT VERTIKAL --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                            
                            {{-- KOLOM KIRI --}}
                            <div class="space-y-6">
                                {{-- Nama Lengkap --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2" for="name">Nama Lengkap</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <input class="w-full pl-11 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none bg-gray-50 focus:bg-white transition text-gray-700" 
                                               id="name" type="text" name="name" placeholder="Masukkan nama lengkap user" required>
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2" for="password">Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                        <input class="w-full pl-11 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none bg-gray-50 focus:bg-white transition text-gray-700" 
                                               id="password" type="password" name="password" placeholder="********" required>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="space-y-6">
                                {{-- Email --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2" for="email">Email Address</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <input class="w-full pl-11 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none bg-gray-50 focus:bg-white transition text-gray-700" 
                                               id="email" type="email" name="email" placeholder="contoh@sorex.co.id" required>
                                    </div>
                                </div>

                                {{-- Konfirmasi Password --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2" for="password_confirmation">Ulangi Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <input class="w-full pl-11 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none bg-gray-50 focus:bg-white transition text-gray-700" 
                                               id="password_confirmation" type="password" name="password_confirmation" placeholder="********" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- REGIONAL (FULL WIDTH) --}}
                        <div class="mb-8">
                            <label class="block text-gray-700 font-bold mb-2" for="regional">Regional / Role</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <select class="w-full pl-11 pr-10 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none bg-gray-50 focus:bg-white transition appearance-none cursor-pointer font-medium text-gray-700" 
                                        id="regional" name="regional" required>
                                    <option value="" disabled selected>-- Pilih Wilayah Akses --</option>
                                    
                                    <optgroup label="Regional 1">
                                            <option value="JT">(JT)</option>
                                            <option value="DK">(DK)</option>
                                            <option value="LP">(LP)</option>
                                        </optgroup>

                                        {{-- KELOMPOK REGIONAL 2 --}}
                                        <optgroup label="Regional 2">
                                            <option value="JB">(JB)</option>
                                            <option value="JR">(JR)</option>
                                        </optgroup>

                                        {{-- KELOMPOK PUSAT --}}
                                        <optgroup label="Pusat / Management">
                                            <option value="Design">Staff Design</option> 
                                            <option value="Admin">Super Admin</option>
                                        </optgroup>
                                </select>
                                {{-- Panah Dropdown --}}
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-600">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Form (Tombol) --}}
                        <div class="flex items-center justify-end border-t border-gray-100 pt-6">
                            <button class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 flex items-center transform hover:-translate-y-0.5" type="submit">
                                <i class="fas fa-save mr-2"></i> Simpan Data User
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <p class="text-center text-gray-400 text-sm mt-6">
                &copy; {{ date('Y') }} Sorex System. Managed by Admin IT.
            </p>

        </main>
    </div>

</body>
</html>