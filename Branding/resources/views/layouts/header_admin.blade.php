<header class="h-16 bg-header flex items-center justify-between px-6 text-white shadow-md z-20 relative">
    
    {{-- BAGIAN KIRI: LOGO --}}
    <div class="flex items-center">
        <img src="{{ asset('img/logo5.png') }}" alt="Sorex Logo" class="h-10 w-auto transition hover:scale-105">
    </div>

    {{-- BAGIAN KANAN: NOTIFIKASI & PROFIL --}}
        <div class="flex items-center space-x-4 md:space-x-6">

            {{-- 1. IKON NOTIFIKASI DENGAN ANGKA --}}
            <div class="relative" x-data="{ notifOpen: false }">
                <button @click="notifOpen = !notifOpen" class="relative p-2 text-gray-300 hover:text-white transition focus:outline-none">
                    <i class="fas fa-bell text-xl"></i>
                    
                    {{-- BADGE ANGKA --}}
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white border-2 border-gray-800 animate-pulse">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </button>

                {{-- Dropdown Isi Notifikasi --}}
                <div x-show="notifOpen" 
                     @click.away="notifOpen = false"
                     style="display: none;"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-3 w-80 bg-white rounded-lg shadow-xl py-2 z-50 text-gray-800 ring-1 ring-black ring-opacity-5">
                    
                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-lg">
                        <h3 class="font-bold text-sm text-gray-700">Notifikasi</h3>
                        {{-- REVISI: Link ke Route Tandai Semua --}}
                        <a href="{{ route('notification.read.all') }}" class="text-xs text-blue-500 hover:text-blue-700 font-medium">Tandai Semua Dibaca</a>
                    </div>

                    <div class="max-h-64 overflow-y-auto">
                        @forelse($notifications ?? [] as $notif)
                            {{-- REVISI: Link ke Route Perantara (notification.read) --}}
                            <a href="{{ route('notification.read', $notif->id) }}" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition {{ $notif->is_read ? 'opacity-60' : 'bg-blue-50' }}">
                                <div class="flex items-start">
                                    {{-- Ikon Dinamis (Edit vs Input) --}}
                                    <div class="flex-shrink-0 {{ $notif->type == 'REVISI' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }} rounded-full p-2 mr-3">
                                        <i class="fas {{ $notif->type == 'REVISI' ? 'fa-edit' : 'fa-clipboard-list' }} text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $notif->title }}</p>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">{{ $notif->message }}</p>
                                        <p class="text-[10px] text-gray-400 mt-1 flex items-center">
                                            <i class="far fa-clock mr-1"></i> {{ $notif->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    {{-- Titik Biru jika belum dibaca --}}
                                    @if(!$notif->is_read)
                                        <span class="ml-auto w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1"></span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-8 text-center text-gray-400">
                                <i class="far fa-bell-slash text-3xl mb-2 opacity-50"></i>
                                <p class="text-sm">Tidak ada notifikasi baru</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="border-t border-gray-100 pt-1 bg-gray-50 rounded-b-lg">
                        {{-- REVISI: Link ke Route Lihat Semua --}}
                        <a href="{{ route('notification.index') }}" class="block text-center px-4 py-2 text-xs font-bold text-gray-600 hover:text-blue-600 hover:bg-gray-100 transition rounded-b-lg">
                            Lihat Semua Notifikasi <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

        <div class="h-8 w-px bg-gray-600 mx-2"></div>

        {{-- 2. PROFIL USER --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none hover:bg-gray-700 p-2 rounded transition-colors pl-2">
                <img class="h-9 w-9 rounded-full object-cover border-2 border-gray-500" 
                     src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&color=7F9CF5&background=EBF4FF" alt="Admin">
                
                <div class="hidden md:flex flex-col items-start text-sm">
                    <span class="font-semibold">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <span class="text-xs text-gray-400">Administrator</span>
                </div>
                <i :class="{'rotate-180': open}" class="fas fa-chevron-down text-xs transition-transform duration-200 ml-1"></i>
            </button>

            <div x-show="open" @click.away="open = false" style="display: none;" 
                 class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5 text-gray-700">
                <div class="px-4 py-3 border-b text-sm bg-gray-50">
                    <p class="text-gray-500 text-xs">Signed in as</p>
                    <p class="font-bold truncate text-gray-800">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <a href="{{ url('/profile') }}" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 transition-colors">
                    <i class="fas fa-cog w-5 mr-2 text-gray-400"></i> Settings
                </a>
                <div class="border-t border-gray-100 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                        <i class="fas fa-sign-out-alt w-5 mr-2"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>