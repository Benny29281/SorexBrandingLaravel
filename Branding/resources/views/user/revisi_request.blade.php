<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisi Data Branding</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn-sorex { background-color: #d71920; color: white; }
        .btn-sorex:hover { background-color: #b01217; }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border-t-4 border-[#d71920]">
        
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Revisi Data</h1>
            <p class="text-gray-500 text-sm">Masukkan ID Request yang ingin Anda revisi.</p>
        </div>

        {{-- Tampilkan Error Jika ID Salah --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm font-bold">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('user.request.check') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">ID Request</label>
                <input type="text" name="request_id" required 
                       class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 text-lg focus:border-red-500 focus:outline-none uppercase" 
                       placeholder="Contoh: BS101 atau RB205">
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('user.home') }}" class="text-gray-500 hover:text-gray-800 text-sm font-bold">Kembali</a>
                <button type="submit" class="btn-sorex px-6 py-2 rounded-lg font-bold shadow transition">
                    Cari Data <i class="fas fa-search ml-2"></i>
                </button>
            </div>
        </form>

    </div>

</body>
</html>