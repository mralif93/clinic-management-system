<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display list of doctors for schedule management
     */
    public function index()
    {
        $doctors = Doctor::with('user')->orderBy('first_name')->get();

        return view('admin.schedule.index', compact('doctors'));
    }

    /**
     * Display schedule management for a specific doctor
     */
    public function manage($doctorId)
    {
        $doctor = Doctor::with('user')->findOrFail($doctorId);

        // Get all existing schedules for this doctor
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)->get()->keyBy('day_of_week');

        // Create array for all days (0-6) with either existing schedule or empty data
        $scheduleData = [];
        for ($day = 0; $day <= 6; $day++) {
            if (isset($schedules[$day])) {
                $scheduleData[$day] = $schedules[$day];
            } else {
                // Create empty schedule object with default values for display only
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

        return view('admin.schedule.manage', compact('doctor', 'scheduleData'));
    }

    /**
     * Save doctor schedule settings (admin override)
     */
    public function saveSettings(Request $request, $doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        // Validate the request
        try {
            $validated = $request->validate([
                'schedules' => 'required|array',
                'schedules.*.day_of_week' => 'required|integer|between:0,6',
                'schedules.*.is_active' => 'boolean',
                'schedules.*.start_time' => 'nullable|date_format:H:i',
                'schedules.*.break_start' => 'nullable|date_format:H:i',
                'schedules.*.break_end' => 'nullable|date_format:H:i',
                'schedules.*.end_time' => 'nullable|date_format:H:i',
                'schedules.*.slot_duration' => 'nullable|integer|in:15,30,45,60',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.schedules.manage', $doctorId)
                ->withErrors($e->validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            foreach ($validated['schedules'] as $scheduleData) {
                DoctorSchedule::updateOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $scheduleData['day_of_week'],
                    ],
                    [
                        'is_active' => (bool) ($scheduleData['is_active'] ?? false),
                        'start_time' => $scheduleData['start_time'] ?? null,
                        'break_start' => $scheduleData['break_start'] ?? null,
                        'break_end' => $scheduleData['break_end'] ?? null,
                        'end_time' => $scheduleData['end_time'] ?? null,
                        'slot_duration' => $scheduleData['slot_duration'] ?? 30,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.schedules.manage', $doctorId)
                ->with('success', 'Schedule settings saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            \Log::error('Failed to save doctor schedule (admin): ' . $e->getMessage());
            \Log::error('Exception: ' . $e->getTraceAsString());

            return redirect()->route('admin.schedules.manage', $doctorId)
                ->with('error', 'Failed to save schedule settings. Please try again.');
        }
    }

    /**
     * View doctor schedule (read-only)
     */
    public function view($doctorId)
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

        return view('admin.schedule.view', compact('doctor', 'scheduleData'));
    }
}
