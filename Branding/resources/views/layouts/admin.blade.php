<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SOREX</title>
    
    {{-- Library --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bar2.png') }}">
    
    <style>
        /* Warna Sidebar & Header sesuai Dashboard */
        .bg-sidebar { background-color: #3d3d3d; }
        .bg-sidebar-active { background-color: #d71920; } /* Merah */
        .bg-header { background-color: #2b2b2b; } /* Hitam Header */
        
        body { background-color: #f3f4f6; font-family: 'Segoe UI', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
</head>

<body class="flex h-screen overflow-hidden bg-gray-100 font-sans">

    {{-- 1. SIDEBAR (KIRI) --}}
    {{-- Pastikan file sidebar_admin.blade.php TIDAK menggunakan class 'fixed' agar flexbox bekerja --}}
        @include('layouts.sidebar_admin')

    {{-- 2. WRAPPER KANAN (HEADER + KONTEN) --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        {{-- HEADER (ATAS) --}}
        {{-- PENTING: Header ini yang membuat tampilan atas menjadi gelap --}}
        @include('layouts.header_admin')

        {{-- MAIN CONTENT (BAWAH) --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            @yield('content')
        </main>

    </div>

</body>
</html>