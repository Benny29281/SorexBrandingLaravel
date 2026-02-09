<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\Notification;
use Illuminate\Support\Facades\Storage; 
use League\Flysystem\Filesystem;        
use Google\Client;                      
use Google\Service\Drive;               
use Masbug\Flysystem\GoogleDriveAdapter; 

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // --- LOGIKA NOTIFIKASI (BIARKAN SAJA) ---
        view()->composer('layouts.header_admin', function ($view) {
            $notifications = Notification::latest()->take(5)->get();
            $unreadCount = Notification::where('is_read', false)->count();
            $view->with('notifications', $notifications);
            $view->with('unreadCount', $unreadCount);
        });

        // --- KONFIGURASI GOOGLE DRIVE (PERBAIKAN DISINI) ---
        Storage::extend('google', function($app, $config) {
            $client = new \Google\Client();
            
            // 1. Auth
            if (!empty($config['serviceAccountCredentials'])) {
                $client->setAuthConfig($config['serviceAccountCredentials']);
            } 
            elseif (!empty($config['clientId'])) {
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $client->refreshToken($config['refreshToken']);
            }

            // 2. Scope (Penting!)
            $client->addScope(\Google\Service\Drive::DRIVE);

            $service = new \Google\Service\Drive($client);
            
            // 3. Folder ID
            $folderId = $config['folderId'] ?? '/';;
            
            $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $folderId);
            $driver  = new \League\Flysystem\Filesystem($adapter);

            // ============================================================
            // PERBAIKAN UTAMA:
            // Bungkus driver dengan FilesystemAdapter milik Laravel
            // ============================================================
            return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
        });
    }
}