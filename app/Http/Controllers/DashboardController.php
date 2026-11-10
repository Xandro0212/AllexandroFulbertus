<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Classroom;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isMahasiswa()) {
            return $this->mahasiswaDashboard($user);
        }

        if ($user->isAkademik()) {
            return $this->akademikDashboard($user);
        }

        return $this->adminDashboard($user);
    }

    private function mahasiswaDashboard(User $user)
    {
        $stats = [
            'total' => Booking::where('user_id', $user->id)->count(),
            'pending' => Booking::where('user_id', $user->id)->where('status', Booking::STATUS_PENDING)->count(),
            'disetujui' => Booking::where('user_id', $user->id)->where('status', Booking::STATUS_DISETUJUI)->count(),
            'ditolak' => Booking::where('user_id', $user->id)->where('status', Booking::STATUS_DITOLAK)->count(),
        ];

        $recentBookings = Booking::with('classroom')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $classrooms = Classroom::orderBy('kode_ruangan')->get();

        return view('dashboard.mahasiswa', compact('stats', 'recentBookings', 'notifications', 'classrooms'));
    }

    private function akademikDashboard(User $user)
    {
        $stats = [
            'pending' => Booking::where('status', Booking::STATUS_PENDING)->count(),
            'disetujui' => Booking::where('status', Booking::STATUS_DISETUJUI)->count(),
            'ditolak' => Booking::where('status', Booking::STATUS_DITOLAK)->count(),
            'total_ruangan' => Classroom::count(),
        ];

        $pendingBookings = Booking::with(['user', 'classroom'])
            ->where('status', Booking::STATUS_PENDING)
            ->latest()
            ->take(10)
            ->get();

        $classrooms = Classroom::orderBy('kode_ruangan')->get();

        return view('dashboard.akademik', compact('stats', 'pendingBookings', 'classrooms'));
    }

    private function adminDashboard(User $user)
    {
        $stats = [
            'total_mahasiswa' => User::where('role', User::ROLE_MAHASISWA)->count(),
            'total_akademik' => User::where('role', User::ROLE_AKADEMIK)->count(),
            'total_ruangan' => Classroom::count(),
            'total_pengajuan' => Booking::count(),
            'pending' => Booking::where('status', Booking::STATUS_PENDING)->count(),
        ];

        $recentBookings = Booking::with(['user', 'classroom'])->latest()->take(5)->get();

        $classrooms = Classroom::orderBy('kode_ruangan')->get();

        return view('dashboard.admin', compact('stats', 'recentBookings', 'classrooms'));
    }
}
