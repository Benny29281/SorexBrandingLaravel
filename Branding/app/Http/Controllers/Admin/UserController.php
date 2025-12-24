<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // 1. MENAMPILKAN DAFTAR USER (RIWAYAT)
    public function index()
    {
        // Ambil semua user terbaru, paginate 10 per halaman
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // 2. FORM REGISTER (CREATE)
    public function create()
    {
        return view('admin.users.register');
    }

    // 3. SIMPAN USER BARU
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'regional' => ['required', 'string'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'regional' => $request->regional,
        ]);

        // Setelah register, arahkan kembali ke LIST USER
        return redirect()->route('admin.users.index')->with('success', 'User berhasil didaftarkan!');
    }

    // 4. FORM EDIT USER
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // 5. UPDATE USER (GANTI PASSWORD JIKA DIISI)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id], // Cek unik kecuali diri sendiri
            'regional' => ['required', 'string'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password boleh kosong
        ]);

        // Update data dasar
        $user->name = $request->name;
        $user->email = $request->email;
        $user->regional = $request->regional;

        // Cek apakah kolom password diisi?
        if ($request->filled('password')) {
            // Jika diisi, ganti password baru
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    // 6. HAPUS USER
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}