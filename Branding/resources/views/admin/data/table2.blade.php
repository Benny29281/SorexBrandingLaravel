<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Branding JT, DK, LP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Data Branding (JB & JR)</h1>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
            <table class="min-w-full w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Tanggal</th>
                        <th class="py-3 px-6 text-left">ID Request</th>
                        <th class="py-3 px-6 text-left">Nama Toko</th>
                        <th class="py-3 px-6 text-left">Area</th>
                        <th class="py-3 px-6 text-left">Brand</th>
                        <th class="py-3 px-6 text-center">Qty</th>
                        <th class="py-3 px-6 text-left">Jenis Tools</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse($data as $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left">{{ $item->submission_date }}</td>
                            <td class="py-3 px-6 text-left font-bold">{{ $item->request_id }}</td>
                            <td class="py-3 px-6 text-left">{{ $item->nama_toko }}</td>
                            <td class="py-3 px-6 text-left">
                                <span class="bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs">{{ $item->area_sales }}</span>
                            </td>
                            <td class="py-3 px-6 text-left">{{ $item->brand }}</td>
                            <td class="py-3 px-6 text-center font-bold">{{ $item->qty_tools }}</td>
                            <td class="py-3 px-6 text-left">{{ $item->jenis_tools_branding }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-3 px-6 text-center">Belum ada data yang diupload.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $data->links() }}
        </div>
    </div>

</body>
</html>