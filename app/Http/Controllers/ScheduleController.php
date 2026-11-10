<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $start = Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        // Booking yang disetujui untuk ditampilkan di kalender
        $approvedBookings = Booking::with(['classroom', 'user'])
            ->whereIn('status', [Booking::STATUS_DISETUJUI, Booking::STATUS_PENDING])
            ->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->get();

        $classrooms = Classroom::orderBy('kode_ruangan')->get();

        $days = collect(range(1, $end->day))
            ->map(function ($day) use ($start) {
                return $start->copy()->day($day)->toDateString();
            });

        $prevMonth = $start->copy()->subMonth()->format('Y-m');
        $nextMonth = $start->copy()->addMonth()->format('Y-m');

        return view('schedule.index', compact(
            'approvedBookings', 'classrooms', 'days', 'month', 'prevMonth', 'nextMonth', 'start'
        ));
    }

    public function classrooms()
    {
        $classrooms = Classroom::withCount([
            'bookings as total_pengajuan',
            'bookings as pengajuan_pending' => fn ($q) => $q->where('status', Booking::STATUS_PENDING),
        ])->orderBy('kode_ruangan')->get();

        return view('schedule.classrooms', compact('classrooms'));
    }
}
