<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    // 1. FUNGSI KLIK SATU NOTIFIKASI (Tandai Baca -> Redirect)
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            // Ubah status jadi sudah dibaca
            $notification->update(['is_read' => true]);

            // Redirect ke URL tujuan asli (misal: ke halaman detail request)
            return redirect($notification->url);
        }

        return back();
    }

    // 2. FUNGSI TANDAI SEMUA SUDAH DIBACA
    public function markAllRead()
    {
        // Update semua notifikasi yang belum dibaca menjadi dibaca
        Notification::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    // 3. HALAMAN LIHAT SEMUA NOTIFIKASI
    public function index()
    {
        // 1. BERSIH-BERSIH PINTAR
        
        // A. Hapus yang SUDAH DIBACA jika lewat 4 hari (Biar bersih)
        Notification::where('is_read', true)
                    ->where('created_at', '<', \Carbon\Carbon::now()->subDays(4))
                    ->delete();

        // B. Hapus yang BELUM DIBACA jika lewat 14 hari (Jaga-jaga kalau admin cuti)
        Notification::where('is_read', false)
                    ->where('created_at', '<', \Carbon\Carbon::now()->subDays(14))
                    ->delete();

        // 2. Tampilkan Data (Tetap dibatasi 100 per halaman agar tidak berat)
        $notifications = Notification::latest()->paginate(100);

        return view('admin.notifications.index', compact('notifications'));
    }
}