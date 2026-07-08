<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Notifikasi - SOREX Admin</title>
    
    {{-- Tailwind & FontAwesome --}}
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
        
        /* Custom Scrollbar Tipis */
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar_admin')

    {{-- MAIN WRAPPER --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        {{-- HEADER --}}
        @include('layouts.header_admin')
    
        {{-- ISI KONTEN --}}
        <main class="flex-1 overflow-hidden bg-white flex flex-col">
            
            {{-- HEADER PAGE --}}
            <div class="bg-white border-b border-gray-200 px-6 py-3 shadow-sm flex justify-between items-center z-10 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="bg-red-600 p-2 rounded-lg text-white">
                        <i class="fas fa-bell text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-black leading-none">Pusat Notifikasi</h1>
                        <p class="text-xs text-black font-medium mt-1">Total: <b>{{ $notifications->total() }}</b> aktivitas</p>
                    </div>
                </div>
                
                <a href="{{ route('notification.read.all') }}" class="text-xs font-bold text-black bg-gray-100 hover:bg-gray-200 border border-gray-300 px-3 py-1.5 rounded-lg transition flex items-center shadow-sm whitespace-nowrap">
                    <i class="fas fa-check-double mr-1.5"></i> Tandai Semua Dibaca
                </a>
            </div>

            {{-- LIST NOTIFIKASI --}}
            <div class="flex-1 overflow-y-auto custom-scroll p-0">
                <div class="bg-white divide-y divide-gray-100">
                    
                    @forelse($notifications as $notif)
                        @php
                            // Tentukan Warna Background Ikon (Ikonnya tetap Lonceng)
                            // Agar tetap ada pembeda visual sedikit namun seragam bentuknya
                            $iconBg = 'bg-blue-100 text-blue-600'; // Default Biru
                            
                            if ($notif->type == 'REVISI') {
                                $iconBg = 'bg-orange-100 text-orange-600'; // Revisi = Orange
                            } elseif (str_contains(strtoupper($notif->title), 'BARU')) {
                                $iconBg = 'bg-green-100 text-green-600'; // Baru = Hijau
                            }

                            // Style Baris (Belum Dibaca = Background agak gelap + Border kiri)
                            $rowClass = $notif->is_read ? 'bg-white hover:bg-gray-50' : 'bg-gray-50 border-l-4 border-l-blue-600';
                            $paddingClass = $notif->is_read ? 'pl-4' : 'pl-3'; 
                        @endphp

                        {{-- ITEM NOTIFIKASI --}}
                        <a href="{{ route('notification.read', $notif->id) }}" class="block {{ $rowClass }} border-b border-gray-100 last:border-0 transition duration-150 group">
                            <div class="py-3 pr-4 {{ $paddingClass }} flex items-center gap-3">
                                
                                {{-- 1. Ikon Lonceng (Semua sama) --}}
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs {{ $iconBg }}">
                                        <i class="fas fa-bell"></i> {{-- ICON LONCENG --}}
                                    </div>
                                </div>

                                {{-- 2. Konten Teks (Hitam Semua) --}}
                                <div class="flex-1 min-w-0 grid grid-cols-1 md:grid-cols-12 gap-1 md:gap-4 items-center">
                                    
                                    {{-- Judul --}}
                                    <div class="md:col-span-3">
                                        <h4 class="text-sm font-extrabold text-black truncate">
                                            {{ $notif->title }}
                                        </h4>
                                    </div>

                                    {{-- Pesan --}}
                                    <div class="md:col-span-7">
                                        <p class="text-xs font-medium text-black truncate">
                                            {{ $notif->message }}
                                        </p>
                                    </div>

                                    {{-- Waktu --}}
                                    <div class="md:col-span-2 text-right">
                                        <span class="text-[10px] font-bold text-black whitespace-nowrap bg-gray-200 px-2 py-0.5 rounded-full">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>

                                {{-- 3. Arrow Indicator --}}
                                <div class="flex-shrink-0 w-4 text-center">
                                    @if(!$notif->is_read)
                                        <div class="w-2 h-2 bg-blue-600 rounded-full mx-auto animate-pulse"></div>
                                    @else
                                        <i class="fas fa-chevron-right text-black text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    @endif
                                </div>

                            </div>
                        </a>
                    @empty
                        {{-- STATE KOSONG --}}
                        <div class="py-20 text-center flex flex-col items-center justify-center">
                            <div class="bg-gray-100 p-4 rounded-full mb-3">
                                <i class="fas fa-bell-slash text-3xl text-black"></i>
                            </div>
                            <h4 class="text-sm font-bold text-black">Belum ada notifikasi</h4>
                        </div>
                    @endforelse

                </div>

                {{-- Pagination --}}
                @if($notifications->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>
    
    @include('components.keep-alive')
</body>
</html>