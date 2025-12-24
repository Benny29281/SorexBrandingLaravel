<div class="p-6">
    @if(Auth::user()->role === 'admin')
        <h1 class="text-red-600 font-bold">HALO ADMIN!</h1>
        <p>Anda bisa melihat semua data Regional (JB_JR dan JT_DK_LP).</p>
        <button>Tombol Edit Data</button>
    @else
        <h1>Halo {{ Auth::user()->name }}</h1>
        <p>Anda login di regional: {{ Auth::user()->regional }}</p>
    @endif
</div>