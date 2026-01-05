<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus notifikasi yang lebih dari 4 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    // Hapus data lama
    \App\Models\Notification::where('created_at', '<', now()->subDays(3))->delete();

    $this->info('Notifikasi lama berhasil dihapus!');
    }
}
