<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Request - {{ $data->request_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn-sorex { background-color: #d71920; transition: 0.3s; }
        .btn-sorex:hover { background-color: #b01217; }
    </style>
</head>
<body class="bg-gray-100 font-sans min-h-screen py-8">

    <div class="container mx-auto px-4 max-w-5xl">
        
        {{-- JUDUL --}}
        <div class="bg-white rounded-t-xl p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Revisi Data: <span class="text-red-600">{{ $data->request_id }}</span></h1>
                <p class="text-gray-500 text-sm">Silakan ubah data yang diperlukan. Kosongkan foto jika tidak ingin mengganti gambar lama.</p>
            </div>
            <a href="{{ route('user.request.revisi') }}" class="text-gray-500 hover:text-red-600 font-bold"><i class="fas fa-times"></i> Batal</a>
        </div>

        {{-- FORM UPDATE --}}
        <form action="{{ route('user.request.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-b-xl shadow-lg p-8">
            @csrf
            @method('PUT')

            {{-- HIDDEN INPUTS (PENTING) --}}
            <input type="hidden" name="request_id" value="{{ $data->request_id }}">
            <input type="hidden" name="table_type" value="{{ $tableType }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- NAMA TOKO --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-1">Nama Toko</label>
                    <input type="text" name="nama_toko" value="{{ $data->nama_toko }}" required class="w-full border p-2 rounded focus:border-red-500 outline-none uppercase">
                </div>

                {{-- LOKASI --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-1">Lokasi / Alamat</label>
                    <textarea name="lokasi" required class="w-full border p-2 rounded focus:border-red-500 outline-none">{{ $data->lokasi }}</textarea>
                </div>

                {{-- AREA SALES --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Area Sales</label>
                    {{-- Input readonly agar user tidak memindahkan data antar regional sembarangan, atau ganti Select jika boleh --}}
                    <input type="text" name="area_sales" value="{{ $data->area_sales }}" readonly class="w-full bg-gray-100 border p-2 rounded cursor-not-allowed">
                </div>

                {{-- NAMA SALES --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nama Sales</label>
                    <input type="text" name="nama_sales" value="{{ $data->nama_sales }}" required class="w-full border p-2 rounded focus:border-red-500 outline-none">
                </div>

                {{-- SPV --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nama SPV</label>
                    <input type="text" name="nama_spv" value="{{ $data->nama_spv }}" class="w-full border p-2 rounded focus:border-red-500 outline-none uppercase">
                </div>

                {{-- BRAND --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Brand</label>
                    <select name="brand" required class="w-full border p-2 rounded">
                        <option value="SOREX MAN" {{ $data->brand == 'SOREX MAN' ? 'selected' : '' }}>SOREX MAN</option>
                        <option value="SOREX LADIES" {{ $data->brand == 'SOREX LADIES' ? 'selected' : '' }}>SOREX LADIES</option>
                        <option value="SOREX KIDS" {{ $data->brand == 'SOREX KIDS' ? 'selected' : '' }}>SOREX KIDS</option>
                    </select>
                </div>

                {{-- JENIS PERMINTAAN --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Jenis Permintaan</label>
                    <select name="jenis_permintaan" required class="w-full border p-2 rounded">
                        <option value="BARU" {{ $data->jenis_permintaan == 'BARU' ? 'selected' : '' }}>BARU</option>
                        <option value="PEREMAJAAN" {{ $data->jenis_permintaan == 'PEREMAJAAN' ? 'selected' : '' }}>PEREMAJAAN</option>
                    </select>
                </div>

                 {{-- TOOLS --}}
                 {{-- <div>
                    <label class="block font-bold text-gray-700 mb-1">Jenis Tools</label>
                    {{-- Tips: Copy option dari form input sebelumnya, hapus 'selected' manual, biarkan user pilih ulang atau biarkan text --}}
                    {{-- <input type="text" name="jenis_tools" value="{{ $data->jenis_tools_branding }}" required class="w-full border p-2 rounded">
                    <p class="text-xs text-gray-400 mt-1">*Ketik manual jika ingin mengubah, atau biarkan.</p>
                </div>  --}}
                  {{-- =========================================================
                     REVISI: INPUT TEXT DENGAN DATALIST (SEARCHABLE)
                     ========================================================= --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Jenis Tools Branding <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" 
                               name="jenis_tools" 
                               list="list_tools" 
                               value="{{ $data->jenis_tools_branding }}"
                               required 
                               autocomplete="off"
                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:border-sorex outline-none transition placeholder-gray-400"
                               placeholder="Ketik untuk mencari (Contoh: Stiker...)">
                        
                        <datalist id="list_tools">
                            <option value="SPANDUK KOREA"></option>
                            <option value="SPANDUK KOREA (PAKAI MATA AYAM)"></option>
                            <option value="SPANDUK CHINA"></option>
                            <option value="SPANDUK CHINA (PAKAI MATA AYAM)"></option>
                            <option value="PVC BOARD"></option>
                            <option value="NEONBOX"></option>
                            <option value="STIKER BACKLITE (UNTUK NEONBOX)"></option>
                            <option value="STIKER OVERPRINT (UNTUK NEONBOX)"></option>
                            <option value="STIKER DOFF"></option>
                            <option value="STIKER GLOSSY"></option>
                            <option value="STIKER ONEWAY"></option>
                            <option value="POSTER DOFF"></option>
                            <option value="POSTER GLOSSY"></option>
                            <option value="AKRILIK KAPUR / PVC"></option>
                            <option value="AKRILIK BENING"></option>
                            <option value="IMPRABOARD"></option>
                            <option value="ART PAPER / POP"></option>
                            <option value="X BANNER"></option>
                            <option value="ROLL UP BANNER"></option>
                            <option value="TRIPOD BANNER"></option>
                            <option value="WOBBLER"></option>
                            <option value="CUTTING AKRILIK BACKWALL"></option>
                            <option value="Lainnya"></option>
                        </datalist>

                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1"><i>*Tips: Ketik kata kunci, opsi akan muncul otomatis.</i></p>
                </div>
                {{-- ========================================================= --}}

                {{-- UKURAN --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Ukuran</label>
                    <input type="text" name="ukuran" value="{{ $data->ukuran_tools_branding }}" class="w-full border p-2 rounded">
                </div>

                {{-- QTY --}}
                <div>
                    <label class="block font-bold text-gray-700 mb-1">QTY</label>
                    <input type="number" name="qty" value="{{ $data->qty_tools }}" required class="w-full border p-2 rounded">
                </div>

                 {{-- PENGIRIMAN --}}
                 <div class="col-span-1 md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-1">Pengiriman</label>
                    <input type="text" name="pengiriman" value="{{ $data->pengiriman }}" class="w-full border p-2 rounded">
                </div>

                {{-- KETERANGAN --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" class="w-full border p-2 rounded">{{ $data->keterangan_tambahan }}</textarea>
                </div>

            </div>

            {{-- BAGIAN FOTO (OPSIONAL UPDATE) --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="font-bold text-lg mb-4 text-gray-800"><i class="fas fa-camera mr-2"></i> Update Foto (Opsional)</h3>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 text-sm text-yellow-700">
                    Warning: Jika Anda mengupload foto baru di bawah ini, <b>foto lama akan terhapus/terganti</b>. 
                    Jika tidak ingin mengubah foto, biarkan kosong.
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Ganti Foto Area (Max 5)</label>
                        <input type="file" name="foto_area[]" multiple class="w-full border p-2 rounded bg-white">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Ganti Foto Sugest (Max 5)</label>
                        <input type="file" name="foto_sugest[]" multiple class="w-full border p-2 rounded bg-white">
                    </div>
                </div>
            </div>

            {{-- TOMBOL SIMPAN --}}
            <div class="mt-8 flex justify-end">
                <button type="submit" class="btn-sorex text-white font-bold py-3 px-10 rounded-lg shadow-lg">
                    <i class="fas fa-save mr-2"></i> SIMPAN PERUBAHAN
                </button>
            </div>

        </form>
    </div>

</body>
</html>