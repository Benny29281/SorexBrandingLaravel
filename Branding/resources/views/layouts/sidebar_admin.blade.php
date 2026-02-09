<aside class="w-64 bg-sidebar text-gray-400 flex flex-col shadow-xl z-20 hidden md:flex font-light">
    
    {{-- HEADER SIDEBAR --}}
    <div class="h-16 flex items-center px-6 border-b border-gray-700 bg-black bg-opacity-20">
         <span class="text-xl font-bold tracking-wider text-white">SOREX <span class="text-red-500 text-xs">ADMIN</span></span>
    </div>

    <nav class="flex-1 overflow-y-auto py-4">
        
        {{-- ================================================= --}}
        {{-- MENU UMUM (BISA DILIHAT SEMUA ROLE) --}}
        {{-- ================================================= --}}

        {{-- 1. DASHBOARD --}}
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-sidebar-active text-white border-l-4 border-red-600' : 'hover:bg-gray-700 hover:text-white border-l-4 border-transparent' }}">
            <i class="fas fa-home w-6 text-center"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>

        {{-- 2. REQUEST BRANDING --}}
        {{-- User Design bisa akses ini sesuai request --}}
        <a href="{{ route('admin.request.branding') }}" 
           class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('admin.request.branding') ? 'bg-sidebar-active text-white border-l-4 border-red-600' : 'hover:bg-gray-700 hover:text-white border-l-4 border-transparent' }}">
            <i class="fas fa-pen-to-square w-6 text-center"></i>
            <span class="ml-3">Request Branding</span>
        </a>

        {{-- 3. STATUS BRANDING --}}
            <a href="{{ route('admin.status.index') }}" 
               class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('admin.status.index') ? 'bg-sidebar-active text-white border-l-4 border-red-600' : 'hover:bg-gray-700 hover:text-white border-l-4 border-transparent' }}">
                <i class="fas fa-list-check w-6 text-center"></i>
                <span class="ml-3">Status Branding</span>
            </a>
        {{-- ================================================= --}}
        {{-- MENU KHUSUS OPERASIONAL (ADMIN & REGIONAL) --}}
        {{-- User 'Design' TIDAK BISA LIHAT INI --}}
        {{-- ================================================= --}}
        
        @if(auth()->user()->regional != 'Design')

            {{-- 4. LAPORAN --}}
            <a href="{{ route('admin.laporan.index') }}" 
               class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('admin.laporan.index') ? 'bg-sidebar-active text-white border-l-4 border-red-600' : 'hover:bg-gray-700 hover:text-white border-l-4 border-transparent' }}">
                <i class="fas fa-file-excel w-6 text-center"></i>
                <span class="ml-3">Laporan & Export</span>
            </a>

        @endif

        {{-- 5. NOTIFIKASI (SEMUA BISA LIHAT) --}}
        <a href="{{ route('notification.index') }}" 
           class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('notification.index') ? 'bg-sidebar-active text-white border-l-4 border-red-600' : 'hover:bg-gray-700 hover:text-white border-l-4 border-transparent' }}">
            <i class="fas fa-bell w-6 text-center"></i>
            <span class="ml-3">Notifikasi</span>
            @if(isset($unreadCount) && $unreadCount > 0)
                <span class="ml-auto bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $unreadCount }}</span>
            @endif
        </a>

        {{-- ================================================= --}}
        {{-- MENU KHUSUS SUPER ADMIN --}}
        {{-- User 'Design' dan 'Regional' TIDAK BISA LIHAT --}}
        {{-- ================================================= --}}
        
        @if(auth()->user()->regional == 'Admin')

            {{-- SEPARATOR: USER MANAGEMENT --}}
            <div class="px-6 py-2 mt-2 text-xs font-bold text-gray-500 uppercase tracking-widest border-t border-gray-700">
                User Management
            </div>

            {{-- 6. REGISTER USER --}}
            <a href="{{ route('admin.register') }}" 
               class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('admin.register') ? 'bg-sidebar-active text-white border-l-4 border-red-600' : 'hover:bg-gray-700 hover:text-white border-l-4 border-transparent' }}">
                <i class="fas fa-user-plus w-6 text-center"></i>
                <span class="ml-3">Register User</span>
            </a>

            {{-- 7. MANAGE LOGIN USER --}}
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.edit') ? 'bg-sidebar-active text-white border-l-4 border-red-600' : 'hover:bg-gray-700 hover:text-white border-l-4 border-transparent' }}">
                <i class="fas fa-users w-6 text-center"></i>
                <span class="ml-3">Manage User Login</span>
            </a>

            {{-- 8. MASTER DATA --}}
            <a href="{{ route('admin.master.index') }}" 
               class="flex items-center px-6 py-3 transition-colors {{ request()->routeIs('admin.master.index') ? 'bg-sidebar-active text-white border-l-4 border-red-600' : 'hover:bg-gray-700 hover:text-white border-l-4 border-transparent' }}">
                <i class="fas fa-database w-6 text-center"></i>
                <span class="ml-3">Controller Data Inputan</span>
            </a>

        @endif

        {{-- 9. SETTING (UMUM) --}}
        {{-- <a href="{{ url('/profile') }}" class="flex items-center px-6 py-3 hover:bg-gray-700 hover:text-white transition-colors border-l-4 border-transparent">
            <i class="fas fa-cog w-6 text-center"></i>
            <span class="ml-3">Setting</span>
        </a> --}}

    </nav>

    {{-- FOOTER LOGOUT --}}
    <div class="p-4 border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded transition-colors justify-center font-bold shadow-lg">
                <i class="fas fa-sign-out-alt mr-2"></i> Log Out
            </button>
        </form>
    </div>
</aside>