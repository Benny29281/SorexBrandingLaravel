{{-- PERHATIKAN: x-data harus pakai 'showModalDownload' biar cocok sama modalnya --}}
<div x-data="{ showModalDownload: false }">

    {{-- NAVBAR MERAH --}}
    <nav class="bg-red-600 px-6 py-4 flex justify-between items-center text-white shadow-md">
        
        {{-- Logo Kiri --}}
        <div class="font-bold text-xl">SOREX SYSTEM</div>

        {{-- Menu Kanan --}}
        <div class="flex gap-4 items-center">
            
            {{-- TOMBOL PEMICU MODAL (Updated) --}}
            <button @click="showModalDownload = true" class="font-bold hover:bg-red-700 px-3 py-1 rounded transition">
                <i class="fas fa-download mr-1"></i> DOWNLOAD DATA
            </button>

            {{-- Tombol Logout --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-white text-red-600 px-4 py-1 rounded-full font-bold text-sm">
                    Log Out
                </button>
            </form>
        </div>
    </nav>

    {{-- PANGGIL FILE MODAL DISINI --}}
    @include('user.download_modal')

</div>