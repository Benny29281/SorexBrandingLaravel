<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Sorex Admin</title>
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

    @include('layouts.sidebar_admin')

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        @include('layouts.header_admin')

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            
            <div class="flex flex-col justify-center items-center min-h-[80vh]">
                
                {{-- KARTU EDIT --}}
                <div class="w-full max-w-md bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                    
                    <div class="bg-yellow-600 px-8 py-6 text-center">
                        <h2 class="text-2xl font-bold text-white mb-1">Edit Data User</h2>
                        <p class="text-yellow-100 text-sm">Biarkan password kosong jika tidak ingin mengganti</p>
                    </div>

                    <div class="p-8">
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- Nama Lengkap --}}
                            <div class="mb-5">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                                <input class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500" 
                                       type="text" name="name" value="{{ $user->name }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="mb-5">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                <input class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500" 
                                       type="email" name="email" value="{{ $user->email }}" required>
                            </div>

                            {{-- Regional --}}
                            <div class="mb-5">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Regional</label>
                                <select class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white" name="regional" required>
                                    <option value="Regional 1" {{ $user->regional == 'Regional 1' ? 'selected' : '' }}>Regional 1 (JT, DK, LP)</option>
                                    <option value="Regional 2" {{ $user->regional == 'Regional 2' ? 'selected' : '' }}>Regional 2 (JB & JR)</option>
                                    <option value="Admin" {{ $user->regional == 'Admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>

                            <hr class="my-6 border-gray-200">
                            <p class="text-sm text-gray-500 mb-4 italic">Isi kolom di bawah HANYA jika ingin reset password user.</p>

                            {{-- Password Baru --}}
                            <div class="mb-5">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru (Opsional)</label>
                                <input class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500" 
                                       type="password" name="password" placeholder="Biarkan kosong jika tetap">
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="mb-8">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                                <input class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500" 
                                       type="password" name="password_confirmation" placeholder="Ulangi password baru">
                            </div>

                            <div class="flex gap-4">
                                <a href="{{ route('admin.users.index') }}" class="w-1/2 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg text-center">
                                    Batal
                                </a>
                                <button class="w-1/2 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded-lg shadow-md" type="submit">
                                    Simpan Perubahan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>