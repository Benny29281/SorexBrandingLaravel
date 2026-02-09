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
        .bg-header { background-color: #2b2b2b; }
        
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
        
        
</body>
</html>