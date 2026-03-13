@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="bg-gradient-to-r from-red-600 to-orange-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-6">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-delete-01 text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Trashed Payrolls</h2>
                        <p class="text-red-100 text-sm mt-1">View and manage deleted payroll records</p>
                    </div>
                </div>
                <a href="{{ route('admin.payrolls.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                    <i class='hgi-stroke hgi-arrow-left-01'></i>
                    Back to Payrolls
                </a>
            </div>
        </div>

        <!-- Trashed Payrolls List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($payrolls->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Employee</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Pay Period</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Gross Salary</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Net Salary</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Deleted At</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($payrolls as $payroll)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-red-100 p-2 rounded-full">
                                                <i class='hgi-stroke hgi-user text-xl text-red-600'></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $payroll->user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ ucfirst($payroll->user->role) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $payroll->pay_period }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        RM {{ number_format($payroll->gross_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">
                                        RM {{ number_format($payroll->net_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $payroll->deleted_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center gap-2">
                                            <!-- Restore -->
                                            <form action="{{ route('admin.payrolls.restore', $payroll->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm"
                                                    title="Restore">
                                                    <i class='hgi-stroke hgi-rotate-left-01 text-base'></i>
                                                </button>
                                            </form>

                                            <!-- Force Delete -->
                                            <form action="{{ route('admin.payrolls.force-delete', $payroll->id) }}" method="POST"
                                                class="inline force-delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center bg-red-500 text-white hover:bg-red-600 rounded-full transition shadow-sm"
                                                    title="Permanently Delete">
                                                    <i class='hgi-stroke hgi-cancel-circle text-base'></i>
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
                    {{ $payrolls->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class='hgi-stroke hgi-delete-01 text-6xl text-gray-300'></i>
                    <p class="text-gray-500 mt-4 text-lg">No trashed payrolls found</p>
                    <a href="{{ route('admin.payrolls.index') }}"
                        class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Back to Payrolls
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