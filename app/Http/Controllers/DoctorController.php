<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorController extends Controller
{
    /**
     * Menampilkan daftar semua dokter.
     */
    public function index(): View
    {
        $doctors = Doctor::latest()->paginate(12);
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Menampilkan detail dokter beserta jadwal yang tersedia.
     */
    public function show(Doctor $doctor): View
    {
        $availableTimeSlots = $this->getAvailableTimeSlots($doctor);

        return view('doctors.show', compact('doctor', 'availableTimeSlots'));
    }

    /**
     * Logika utama untuk menghasilkan slot waktu yang tersedia.
     */
    private function getAvailableTimeSlots(Doctor $doctor)
    {
        $availableSlots = [];
        $schedules = $doctor->schedules->groupBy('day_of_week');
        $reservations = Reservation::where('doctor_id', $doctor->id)
            ->where('reservation_time', '>=', now())
            ->pluck('reservation_time')
            ->toArray();

        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(7); // Tampilkan jadwal untuk 7 hari ke depan

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dayOfWeek = $date->dayOfWeekIso; // 1 = Senin, 7 = Minggu

            if (isset($schedules[$dayOfWeek])) {
                $schedule = $schedules[$dayOfWeek][0];
                $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->start_time);
                $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $schedule->end_time);
                $duration = $schedule->duration_minutes;

                $slotsForDay = [];
                while ($startTime < $endTime) {
                    // Hanya tampilkan slot jika belum lewat dari waktu sekarang
                    if ($startTime > now()) {
                        $formattedSlot = $startTime->format('Y-m-d H:i');
                        // Cek apakah slot ini sudah di-booking
                        if (!in_array($formattedSlot, $reservations)) {
                            $slotsForDay[] = $startTime->copy();
                        }
                    }
                    $startTime->addMinutes($duration);
                }
                if (!empty($slotsForDay)) {
                    $availableSlots[$date->format('Y-m-d')] = $slotsForDay;
                }
            }
        }
        return $availableSlots;
    }
}