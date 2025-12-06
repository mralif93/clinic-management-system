<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointment months
     */
    public function index()
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $yearExpr = "strftime('%Y', appointment_date)";
            $monthExpr = "strftime('%m', appointment_date)";
        } else {
            $yearExpr = "YEAR(appointment_date)";
            $monthExpr = "MONTH(appointment_date)";
        }

        $months = Appointment::select(
            DB::raw("$yearExpr as year"),
            DB::raw("$monthExpr as month"),
            DB::raw('COUNT(*) as total_appointments'),
            DB::raw("SUM(CASE WHEN status = 'scheduled' THEN 1 ELSE 0 END) as scheduled_count"),
            DB::raw("SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_count"),
            DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count"),
            DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_count")
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.appointments.months', compact('months'));
    }

    /**
     * Display a listing of appointments for a specific month
     */
    public function byMonth(Request $request, $year, $month)
    {
        $query = Appointment::withTrashed()
            ->with(['patient', 'doctor.user', 'service'])
            ->whereYear('appointment_date', $year)
            ->whereMonth('appointment_date', $month);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date (within the month)
        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by deleted status
        if ($request->has('deleted') && $request->deleted === '1') {
            $query->onlyTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                              ->orderBy('appointment_time', 'desc')
                              ->paginate(15);

        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y');

        // Contextual stats for this month
        $stats = [
            'total' => Appointment::whereYear('appointment_date', $year)->whereMonth('appointment_date', $month)->count(),
            'scheduled' => Appointment::whereYear('appointment_date', $year)->whereMonth('appointment_date', $month)->where('status', 'scheduled')->count(),
            'completed' => Appointment::whereYear('appointment_date', $year)->whereMonth('appointment_date', $month)->where('status', 'completed')->count(),
        ];

        return view('admin.appointments.list', compact('appointments', 'year', 'month', 'monthName', 'stats'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::available()->orderBy('first_name')->get();
        $services = Service::active()->orderBy('name')->get();

        return view('admin.appointments.create', compact('patients', 'doctors', 'services'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'service_id' => 'nullable|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'notes' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:unpaid,paid,partial',
            'payment_method' => 'nullable|in:cash,card,online,insurance',
        ]);

        if (Appointment::hasConflict($validated['doctor_id'] ?? null, $validated['patient_id'], $validated['appointment_date'], $validated['appointment_time'])) {
            return back()
                ->withErrors(['appointment_time' => 'The selected time is already booked for this doctor or patient.'])
                ->withInput();
        }

        Appointment::create($validated);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully!');
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        $appointment = Appointment::withTrashed()
            ->with(['patient', 'doctor.user', 'service', 'user'])
            ->findOrFail($id);
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit($id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);
        
        if ($appointment->trashed()) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'Cannot edit a deleted appointment. Please restore it first.');
        }
        
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::available()->orderBy('first_name')->get();
        $services = Service::active()->orderBy('name')->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors', 'services'));
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);
        
        if ($appointment->trashed()) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'Cannot update a deleted appointment. Please restore it first.');
        }
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'service_id' => 'nullable|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'notes' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'prescription' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:unpaid,paid,partial',
            'payment_method' => 'nullable|in:cash,card,online,insurance',
        ]);

        if (Appointment::hasConflict($validated['doctor_id'] ?? null, $validated['patient_id'], $validated['appointment_date'], $validated['appointment_time'], $appointment->id)) {
            return back()
                ->withErrors(['appointment_time' => 'The selected time is already booked for this doctor or patient.'])
                ->withInput();
        }

        $appointment->update($validated);

        return redirect()->route('admin.appointments.by-month', [
            'year' => \Carbon\Carbon::parse($validated['appointment_date'])->year,
            'month' => \Carbon\Carbon::parse($validated['appointment_date'])->month
        ])->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment (soft delete)
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->back()
            ->with('success', 'Appointment deleted successfully!');
    }

    /**
     * Display trashed appointments
     */
    public function trash()
    {
        $appointments = Appointment::onlyTrashed()
            ->with(['patient', 'doctor', 'service'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('admin.appointments.trash', compact('appointments'));
    }

    /**
     * Restore a soft deleted appointment
     */
    public function restore($id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);
        $appointment->restore();

        return redirect()->route('admin.appointments.trash')
            ->with('success', 'Appointment restored successfully!');
    }

    /**
     * Permanently delete an appointment
     */
    public function forceDelete($id)
    {
        $appointment = Appointment::withTrashed()->findOrFail($id);
        $appointment->forceDelete();

        return redirect()->route('admin.appointments.trash')
            ->with('success', 'Appointment permanently deleted!');
    }

    /**
     * Display the invoice for an appointment
     */
    public function invoice($id)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'service', 'user'])
            ->findOrFail($id);

        return view('admin.appointments.invoice', compact('appointment'));
    }
}