@extends('layouts.staff')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('staff.leaves.index') }}"
                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-white/30 transition">
                            <i class='bx bx-arrow-back text-white text-xl'></i>
                        </a>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Edit Leave Request</h1>
                            <p class="text-purple-100 text-sm mt-1">Update your pending leave request</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto">
            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="p-8">
                    <form action="{{ route('staff.leaves.update', $leave->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Leave Type -->
                        <div>
                            <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">Leave Type <span
                                    class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(\App\Models\Leave::getLeaveTypes() as $value => $label)
                                    <label
                                        class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors focus-within:ring-2 focus-within:ring-blue-500">
                                        <input type="radio" name="leave_type" value="{{ $value }}"
                                            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" {{ (old('leave_type') ?? $leave->leave_type) == $value ? 'checked' : '' }} required>
                                        <span class="ml-3 block text-sm font-medium text-gray-900">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('leave_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date
                                    <span class="text-red-500">*</span></label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date') ?? $leave->start_date->format('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ old('end_date') ?? $leave->end_date->format('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Duration Calculation (Auto-updated via JS) -->
                        <div id="duration_display"
                            class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center gap-3">
                            <i class='bx bx-time-five text-blue-600 text-xl'></i>
                            <p class="text-blue-800 font-medium">Total Duration: <span
                                    id="total_days">{{ $leave->total_days }}</span> days</p>
                        </div>

                        <!-- Reason -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason <span
                                    class="text-red-500">*</span></label>
                            <textarea name="reason" id="reason" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Please provide a detailed reason for your leave request..."
                                required>{{ old('reason') ?? $leave->reason }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Attachment -->
                        <div>
                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Attachment
                                (Optional)</label>

                            @if($leave->attachment)
                                <div class="mb-4 flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class='bx bx-file text-2xl text-gray-400'></i>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-700">Current File</p>
                                        <a href="{{ Storage::url($leave->attachment) }}" target="_blank"
                                            class="text-xs text-blue-600 hover:underline">View Attachment</a>
                                    </div>
                                </div>
                            @endif

                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class='bx bx-cloud-upload text-4xl text-gray-400'></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="attachment"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a new file</span>
                                            <input id="attachment" name="attachment" type="file" class="sr-only"
                                                accept=".jpg,.jpeg,.png,.pdf">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                                    <p id="file_name" class="text-sm text-blue-600 font-medium mt-2"></p>
                                </div>
                            </div>
                            @error('attachment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('staff.leaves.index') }}"
                                class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">Cancel</a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg shadow-purple-500/30 flex items-center gap-2 font-semibold">
                                <i class='bx bx-save'></i>
                                Update Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calculate duration
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const durationDisplay = document.getElementById('duration_display');
        const totalDaysSpan = document.getElementById('total_days');

        function calculateDuration() {
            if (startDateInput.value && endDateInput.value) {
                const start = new Date(startDateInput.value);
                const end = new Date(endDateInput.value);

                if (end >= start) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    totalDaysSpan.textContent = diffDays;
                    durationDisplay.classList.remove('hidden');
                } else {
                    durationDisplay.classList.add('hidden');
                }
            } else {
                durationDisplay.classList.add('hidden');
            }
        }

        startDateInput.addEventListener('change', calculateDuration);
        endDateInput.addEventListener('change', calculateDuration);

        // File name display
        const fileInput = document.getElementById('attachment');
        const fileNameDisplay = document.getElementById('file_name');

        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                fileNameDisplay.textContent = this.files[0].name;
            } else {
                fileNameDisplay.textContent = '';
            }
        });
    </script>
@endsection