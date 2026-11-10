<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Format tanggal dalam Bahasa Indonesia
        Carbon::setLocale('id');

        // Pagination memakai Bootstrap 5 (sesuai tema UI)
        Paginator::useBootstrapFive();
        // Bagikan jumlah notifikasi belum dibaca & pengajuan pending ke seluruh view
        View::composer('*', function ($view) {
            $unreadCount = 0;
            $pendingCount = 0;

            if (auth()->check()) {
                $unreadCount = Notification::where('user_id', auth()->id())
                    ->whereNull('read_at')
                    ->count();

                if (auth()->user()->isAkademik() || auth()->user()->isAdmin()) {
                    $pendingCount = Booking::where('status', Booking::STATUS_PENDING)->count();
                }
            }

            $view->with([
                'unreadNotificationsCount' => $unreadCount,
                'pendingCount' => $pendingCount,
            ]);
        });
    }
}
