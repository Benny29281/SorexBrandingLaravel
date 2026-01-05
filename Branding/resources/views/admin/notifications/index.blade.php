<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Notifikasi - SOREX Admin</title>
    
    {{-- Tailwind & FontAwesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .bg-sidebar { background-color: #3d3d3d; }
        .bg-sidebar-active { background-color: #e02222; }
        .bg-header { background-color: #2b2b2b; }
        body { background-color: #f3f4f6; font-family: 'Segoe UI', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar_admin')

    {{-- MAIN WRAPPER --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        {{-- HEADER --}}
        @include('layouts.header_admin')
    
        {{-- ISI KONTEN (SCROLLABLE) --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            
            {{-- Container Utama: Padding disesuaikan agar sejajar --}}
            <div class="container mx-auto px-6 py-8">
                
                {{-- HEADER KONTEN (Judul & Tombol) --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Semua Notifikasi</h1>
                        <p class="text-gray-500 text-sm mt-1">Lihat seluruh riwayat aktivitas sistem.</p>
                    </div>
                    
                    <a href="{{ route('notification.read.all') }}" class="bg-white text-gray-700 px-5 py-2.5 rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 text-sm font-bold transition flex items-center group">
                        <i class="fas fa-check-double mr-2 text-blue-500 group-hover:text-blue-600"></i> Tandai Semua Dibaca
                    </a>
                </div>

                {{-- LIST NOTIFIKASI --}}
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    @forelse($notifications as $notif)
                        <div class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition duration-150 {{ $notif->is_read ? 'bg-white' : 'bg-blue-50/40' }}">
                            <a href="{{ route('notification.read', $notif->id) }}" class="block p-5 group">
                                <div class="flex items-start">
                                    
                                    {{-- Ikon --}}
                                    <div class="flex-shrink-0 pt-1">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center transition-colors 
                                            {{ $notif->is_read ? 'bg-gray-100 text-gray-400 group-hover:bg-gray-200' : 
                                            ($notif->type == 'PENGIRIMAN' ? 'bg-green-100 text-green-600' : 
                                            ($notif->type == 'REVISI' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600 shadow-sm')) 
                                            }}">
                                            <i class="fas 
                                                @if($notif->type == 'REVISI') fa-edit 
                                                @elseif($notif->type == 'PENGIRIMAN') fa-truck 
                                                @else fa-bell 
                                                @endif 
                                                text-lg">
                                            </i>
                                        </div>
                                    </div>
                                    
                                    {{-- Teks Konten --}}
                                    <div class="ml-4 flex-1">
                                        <div class="flex justify-between items-start">
                                            <h4 class="text-base font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                                                {{ $notif->title }}
                                            </h4>
                                            <span class="text-xs text-gray-400 whitespace-nowrap ml-2 bg-gray-100 px-3 py-1 rounded-full flex items-center">
                                                <i class="far fa-clock mr-1"></i> {{ $notif->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $notif->message }}</p>
                                    </div>

                                    {{-- Titik Biru (Unread) --}}
                                    @if(!$notif->is_read)
                                        <div class="ml-4 pt-4 flex flex-col items-center">
                                            <div class="w-2.5 h-2.5 bg-blue-500 rounded-full shadow-lg shadow-blue-500/50"></div>
                                            <span class="text-[10px] text-blue-500 font-bold mt-1">Baru</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="p-16 text-center text-gray-400 flex flex-col items-center justify-center">
                            <div class="bg-gray-50 p-4 rounded-full mb-4">
                                <i class="fas fa-bell-slash text-4xl text-gray-300"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-500">Tidak ada notifikasi</h4>
                            <p class="text-sm mt-1">Semua aktivitas sistem akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>

            </div> {{-- End Container --}}
        </main>

        {{-- Footer --}}
        <footer class="w-full bg-white text-center text-xs p-4 text-gray-500 border-t shadow-inner z-10">
            &copy; 2025 SOREX Admin System. All Rights Reserved.
        </footer>
    </div>
    
    @include('components.keep-alive')
</body>
</html>