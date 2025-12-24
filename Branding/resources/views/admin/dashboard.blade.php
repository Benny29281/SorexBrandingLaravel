<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sorex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="bar2.png" type="image/png">

    <style>
        /* Warna kustom berdasarkan gaya Metronic */
        .bg-sidebar { background-color: #3d3d3d; }
        .bg-sidebar-active { background-color: #e02222; }
        .bg-header { background-color: #2b2b2b; } /* Warna header gelap */
        
        /* Warna Widget Statistik */
        .bg-stat-blue { background-color: #27a9e3; }
        .bg-stat-green { background-color: #28b779; }
        .bg-stat-purple { background-color: #852b99; }
        .bg-stat-orange { background-color: #ffb848; }
        .bg-stat-footer { background-color: rgba(0,0,0,0.1); }

        body { background-color: #e9ecf3; font-family: sans-serif; }
        [x-cloak] { display: none !important; } 
    </style>
</head>
<body class="flex h-screen overflow-hidden font-sans">

    @include('layouts.sidebar_admin')

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        @include('layouts.header_admin')
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl text-gray-700 font-light">Dashboard <small class="text-sm text-gray-500">statistics and more</small></h1>
                    <div class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-home"></i> Home <i class="fas fa-angle-right mx-1"></i> Dashboard
                    </div>
                </div>
                {{-- <button class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-2 rounded shadow flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i> March 22, 2014 - April 22, 2014 <i class="fas fa-angle-down ml-2"></i>
                </button> --}}
            </div>

{{-- BAGIAN 1: KOTAK STATISTIK (KOTAK-KOTAK) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-blue-600 rounded-lg shadow-lg text-white overflow-hidden relative group hover:bg-blue-700 transition">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-lg font-semibold opacity-90">Total JT, DK & LP</h5>
                                <h2 class="text-4xl font-bold mt-2">{{ $total_jt_dk_lp ?? 0 }}</h2>
                                <p class="text-sm opacity-75 mt-1">Data Branding Request 1</p>
                            </div>
                            <i class="fas fa-clipboard-list text-6xl opacity-30"></i>
                        </div>
                    </div>
                    <a href="{{ route('data.branding1') }}" class="block bg-black bg-opacity-20 py-2 px-5 text-sm hover:bg-opacity-30 transition flex justify-between items-center">
                        Lihat Data Lengkap <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>

                <div class="bg-green-600 rounded-lg shadow-lg text-white overflow-hidden relative group hover:bg-green-700 transition">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-lg font-semibold opacity-90">Total JB & JR</h5>
                                <h2 class="text-4xl font-bold mt-2">{{ $total_jb_jr ?? 0 }}</h2>
                                <p class="text-sm opacity-75 mt-1">Data Branding Request 2</p>
                            </div>
                            <i class="fas fa-file-invoice text-6xl opacity-30"></i>
                        </div>
                    </div>
                    <a href="{{ route('data.branding2') }}" class="block bg-black bg-opacity-20 py-2 px-5 text-sm hover:bg-opacity-30 transition flex justify-between items-center">
                        Lihat Data Lengkap <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>

                <div class="bg-gray-800 rounded-lg shadow-lg text-white overflow-hidden relative">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="text-lg font-semibold opacity-90">Grand Total</h5>   
                                <h2 class="text-4xl font-bold mt-2">{{ $grand_total ?? 0 }}</h2>
                                <p class="text-sm opacity-75 mt-1">Semua Permintaan Masuk</p>
                            </div>
                            <i class="fas fa-database text-6xl opacity-30"></i>
                        </div>
                    </div>
                    <div class="bg-black bg-opacity-20 py-2 px-5 text-sm">
                        Total Keseluruhan
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded shadow-sm border border-gray-200 mb-6">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    
                    <div class="w-full md:w-1/4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Tahun</label>
                        <select name="year" class="block w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            <option value="">-- Semua Tahun --</option>
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ ($selectedYear == $i) ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="w-full md:w-1/4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Bulan</label>
                        <select name="month" class="block w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            <option value="">-- Semua Bulan --</option>
                            <option value="01" {{ $selectedMonth == '01' ? 'selected' : '' }}>Januari</option>
                            <option value="02" {{ $selectedMonth == '02' ? 'selected' : '' }}>Februari</option>
                            <option value="03" {{ $selectedMonth == '03' ? 'selected' : '' }}>Maret</option>
                            <option value="04" {{ $selectedMonth == '04' ? 'selected' : '' }}>April</option>
                            <option value="05" {{ $selectedMonth == '05' ? 'selected' : '' }}>Mei</option>
                            <option value="06" {{ $selectedMonth == '06' ? 'selected' : '' }}>Juni</option>
                            <option value="07" {{ $selectedMonth == '07' ? 'selected' : '' }}>Juli</option>
                            <option value="08" {{ $selectedMonth == '08' ? 'selected' : '' }}>Agustus</option>
                            <option value="09" {{ $selectedMonth == '09' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ $selectedMonth == '10' ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ $selectedMonth == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ $selectedMonth == '12' ? 'selected' : '' }}>Desember</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

           <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-700 font-bold mb-4 border-b pb-2">Tren Permintaan</h3>
                    <div class="h-64"><canvas id="chartTren"></canvas></div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-700 font-bold mb-4 border-b pb-2">Top 10 Branding Tools</h3>
                    <div class="h-64"><canvas id="chartTools"></canvas></div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-700 font-bold mb-4 border-b pb-2">Sebaran Area Sales</h3>
                    <div class="h-64"><canvas id="chartArea"></canvas></div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-700 font-bold mb-4 border-b pb-2">Komposisi Brand</h3>
                    <div class="h-64 flex justify-center"><canvas id="chartBrand"></canvas></div>
                </div>

            </div>

        </main>

       {{-- Tampilkan Pesan Sukses/Error Global
@if(session('success'))
    <div class="bg-green-500 text-white p-3 rounded mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-500 text-white p-3 rounded mb-4">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    
    <div class="bg-white p-6 rounded shadow-sm border-l-4 border-red-500">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-lg font-bold text-gray-700">
                <i class="fas fa-map-marker-alt text-red-600 mr-2"></i> Area JT, DK, LP
            </h3>
        </div>
        <p class="text-sm text-gray-600 mb-4">Upload file Excel untuk area ini. Data masuk ke tabel utama.</p>

        <form action="{{ route('import.branding') }}" method="POST" enctype="multipart/form-data">
            @csrf 
            <div class="mb-4">
                <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-300 rounded cursor-pointer p-1">
            </div>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow transition duration-200">
                <i class="fas fa-upload mr-2"></i> Upload JT, DK, LP
            </button>
        </form>
    </div>

    <div class="bg-white p-6 rounded shadow-sm border-l-4 border-blue-500">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-lg font-bold text-gray-700">
                <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i> Area JB & JR
            </h3>
        </div>
        <p class="text-sm text-gray-600 mb-4">Upload file Excel untuk area ini. Data masuk ke tabel kedua.</p>

        <form action="{{ route('import.branding2') }}" method="POST" enctype="multipart/form-data">
            @csrf 
            <div class="mb-4">
                <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded cursor-pointer p-1">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-200">
                <i class="fas fa-upload mr-2"></i> Upload JB & JR
            </button>
        </form>
    </div> --}}

</div>


    </div>
    <script>
        // --- 1. TREN PERMINTAAN (2 LINE CHART) ---
        new Chart(document.getElementById('chartTren'), {
            type: 'line',
            data: {
                labels: @json($trenLabels), // Nama Bulan
                datasets: [
                    {
                        label: 'JT, DK, LP',
                        data: @json($tren1),
                        borderColor: '#f13636', // Biru
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.3,
                        fill: false
                    },
                    {
                        label: 'JB & JR',
                        data: @json($tren2),
                        borderColor: '#22c55e', // Hijau
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
            }
        });

        // --- 2. TOOLS TERBANYAK (STACKED BAR) ---
        new Chart(document.getElementById('chartTools'), {
            type: 'bar',
            data: {
                labels: @json($toolsLabels), // Nama Tools
                datasets: [
                    {
                        label: 'JT, DK, LP',
                        data: @json($tools1),
                        backgroundColor: '#f13636', // Biru
                    },
                    {
                        label: 'JB & JR',
                        data: @json($tools2),
                        backgroundColor: '#22c55e', // Hijau
                    }
                ]
            },
            options: { 
                indexAxis: 'y', // Horizontal
                responsive: true, 
                maintainAspectRatio: false,
                scales: {
                    x: { stacked: true }, // Aktifkan Mode Tumpuk
                    y: { stacked: true }
                }
            }
        });

        // --- 3. AREA SALES (Tetap sama) ---
        new Chart(document.getElementById('chartArea'), {
            type: 'bar',
            data: {
                labels: @json($areaLabels),
                datasets: [{
                    label: 'Request per Area',
                    data: @json($areaValues),
                    backgroundColor: ['#f13636',
                        '#edec13',
                        '#3b82f6', 
                        '#bc36f1', 
                        '#22c55e'], 
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // --- 4. KOMPOSISI BRAND (Tetap sama) ---
        const brandLabels = @json($brandLabels);
        const brandColors = brandLabels.map(label => {
            if(label.includes('LADIES')) return '#f13636'; 
            if(label.includes('MAN')) return '#4ded13';   
            if(label.includes('KIDS')) return '#edec13'; 
            return '#9ca3af'; 
        });

        new Chart(document.getElementById('chartBrand'), {
            type: 'doughnut',
            data: {
                labels: brandLabels,
                datasets: [{
                    data: @json($brandValues),
                    backgroundColor: brandColors,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>

</body>
</html>