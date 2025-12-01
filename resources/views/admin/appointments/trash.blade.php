@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Trashed Appointments</h1>
                <p class="text-gray-600 mt-1">View and manage deleted appointment records</p>
            </div>
            <a href="{{ route('admin.appointments.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Appointments
            </a>
        </div>

        <!-- Trashed Appointments List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($appointments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Patient</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Doctor</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Service</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Date & Time</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Deleted At</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($appointments as $appointment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-blue-100 p-2 rounded-full">
                                                <i class='bx bx-user text-xl text-blue-600'></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $appointment->patient->full_name }}</p>
                                                <p class="text-sm text-gray-500">{{ $appointment->patient->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $appointment->doctor->full_name ?? 'Not Assigned' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $appointment->service->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $appointment->appointment_date->format('M d, Y') }} <br>
                                        <span class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $appointment->deleted_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <!-- Restore -->
                                            <form action="{{ route('admin.appointments.restore', $appointment->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm"
                                                    title="Restore">
                                                    <i class='bx bx-undo text-base'></i>
                                                </button>
                                            </form>

                                            <!-- Force Delete -->
                                            <form action="{{ route('admin.appointments.force-delete', $appointment->id) }}" method="POST"
                                                class="inline force-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                    title="Permanently Delete">
                                                    <i class='bx bx-trash text-base'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class='bx bx-trash text-6xl text-gray-300'></i>
                    <p class="text-gray-500 mt-4 text-lg">No trashed appointments found</p>
                    <a href="{{ route('admin.appointments.index') }}"
                        class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Back to Appointments
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Force Delete Confirmation
        document.querySelectorAll('.force-delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Permanently Delete?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete permanently!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif
@endsection
