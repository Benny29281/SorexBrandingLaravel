<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Sorex Admin</title>
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

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4"> {{-- Padding dikurangi jadi p-4 --}}
            
            {{-- CONTAINER UTAMA (Dibatasi Lebarnya) --}}
            <div class="max-w-6xl mx-auto"> 

                <div class="flex justify-between items-center mb-4"> {{-- Margin bottom dikurangi --}}
                    <h1 class="text-xl font-bold text-gray-700">Manajemen User</h1> {{-- Font size dikurangi --}}
                    <a href="{{ route('admin.register') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1.5 px-4 rounded shadow text-sm">
                        <i class="fas fa-user-plus mr-2"></i> Tambah User Baru
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded shadow-sm text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- TABEL USER (COMPACT) --}}
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Regional</th>
                                <th class="px-4 py-2 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-4 py-2 border-b border-gray-200 bg-white text-sm"> {{-- Padding cell dikurangi --}}
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8"> {{-- Avatar lebih kecil (w-8 h-8) --}}
                                            <img class="w-full h-full rounded-full border border-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" alt="" />
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap font-semibold text-sm">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-600 whitespace-no-wrap">{{ $user->email }}</p>
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 bg-white text-sm text-center">
                                    @if($user->role == 'admin')
                                        <span class="relative inline-block px-2 py-0.5 font-semibold text-red-900 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                            <span class="relative text-xs">Admin Pusat</span>
                                        </span>
                                    @else
                                        <span class="relative inline-block px-2 py-0.5 font-semibold text-green-900 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                            <span class="relative text-xs">{{ $user->regional ?? '-' }}</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b border-gray-200 bg-white text-sm text-center">
                                    <div class="flex justify-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-yellow-600 hover:text-yellow-800 transition p-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        @if(Auth::id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition p-1" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{-- Pagination Compact --}}
                    @if($users->hasPages())
                        <div class="px-4 py-3 bg-white border-t border-gray-200 text-xs">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>

                <div class="mt-4 text-center text-gray-400 text-xs">
                    Total User Terdaftar: <b>{{ $users->total() }}</b>
                </div>

            </div>
        </main>
    </div>
</body>
</html>