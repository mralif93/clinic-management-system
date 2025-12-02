<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DoctorSchedule;

class ScheduleController extends Controller
{
    /**
     * Display the clinic schedule
     */
    public function index(Request $request)
    {
        // Get selected date or default to today
        $selectedDate = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();

        // Get appointments for the selected date
        $appointments = Appointment::whereDate('appointment_date', $selectedDate)
            ->with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Get appointments for the week
        $weekStart = $selectedDate->copy()->startOfWeek();
        $weekEnd = $selectedDate->copy()->endOfWeek();

        $weekAppointments = Appointment::whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get()
            ->groupBy(function ($appointment) {
                return $appointment->appointment_date->format('Y-m-d');
            });

        // Statistics
        $todayCount = Appointment::whereDate('appointment_date', Carbon::today())->count();
        $weekCount = Appointment::whereBetween('appointment_date', [$weekStart, $weekEnd])->count();
        $upcomingCount = Appointment::where('appointment_date', '>=', Carbon::today())
            ->where('status', '!=', 'completed')
            ->where('status', '!=', 'cancelled')
            ->count();

        // Get all doctors for filter
        $doctors = Doctor::available()->orderBy('first_name')->get();

        return view('staff.schedule.index', compact(
            'appointments',
            'selectedDate',
            'weekAppointments',
            'weekStart',
            'weekEnd',
            'todayCount',
            'weekCount',
            'upcomingCount',
            'doctors'
        ));
    }

    /**
     * View doctor schedule (read-only for staff)
     */
    public function viewDoctorSchedule($doctorId)
    {
        $doctor = Doctor::with('user')->findOrFail($doctorId);

        // Get all existing schedules for this doctor
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)->get()->keyBy('day_of_week');

        // Create array for all days (0-6)
        $scheduleData = [];
        for ($day = 0; $day <= 6; $day++) {
            if (isset($schedules[$day])) {
                $scheduleData[$day] = $schedules[$day];
            } else {
                $scheduleData[$day] = new DoctorSchedule([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'is_active' => false,
                    'start_time' => '09:00',
                    'break_start' => '12:00',
                    'break_end' => '13:00',
                    'end_time' => '17:00',
                    'slot_duration' => 30,
                ]);
            }
        }

        return view('staff.schedule.view-doctor', compact('doctor', 'scheduleData'));
    }

    /**
     * List doctors for schedule viewing
     */
    public function listDoctors(Request $request)
    {
        $query = Doctor::with('user');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('doctor_id', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by specialization
        if ($request->has('specialization') && $request->specialization) {
            $query->where('specialization', $request->specialization);
        }

        $doctors = $query->orderBy('first_name', 'asc')
            ->paginate(15);

        // Get all specializations for filter
        $specializations = Doctor::select('specialization')->distinct()->pluck('specialization');

        return view('staff.schedule.list-doctors', compact('doctors', 'specializations'));
    }
}

