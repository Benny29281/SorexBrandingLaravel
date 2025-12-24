<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <--- JANGAN LUPA TAMBAHKAN INI
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
{
    view()->composer('layouts.header_admin', function ($view) {
        
        // 1. Ambil 5 Notifikasi Terbaru
        $notifications = Notification::latest()->take(5)->get();
        
        // 2. Hitung Berapa yang Belum Dibaca
        $unreadCount = Notification::where('is_read', false)->count();

        // 3. Kirim ke View
        $view->with('notifications', $notifications);
        $view->with('unreadCount', $unreadCount);
    });
}
}