@extends('layouts.doctor')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative">
                <a href="{{ route('doctor.leaves.index') }}" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                    <i class='bx bx-arrow-back'></i> Back to Leave Requests
                </a>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-edit text-xl'></i>
                    </div>
                    Edit Leave Request
                </h1>
                <p class="text-emerald-100 mt-2">Update your pending leave request</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-file text-emerald-500'></i> Leave Request Details
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('doctor.leaves.update', $leave->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Leave Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Leave Type <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(\App\Models\Leave::getLeaveTypes() as $value => $label)
                                <label class="relative flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-emerald-300 transition-all focus-within:ring-2 focus-within:ring-emerald-500">
                                    <input type="radio" name="leave_type" value="{{ $value }}"
                                        class="h-4 w-4 text-emerald-600 border-gray-300 focus:ring-emerald-500" {{ (old('leave_type') ?? $leave->leave_type) == $value ? 'checked' : '' }} required>
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
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date') ?? $leave->start_date->format('Y-m-d') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" required>
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date') ?? $leave->end_date->format('Y-m-d') }}"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" required>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Duration Calculation (Auto-updated via JS) -->
                    <div id="duration_display" class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <i class='bx bx-time text-emerald-600 text-xl'></i>
                        </div>
                        <p class="text-emerald-800 font-medium">Total Duration: <span id="total_days" class="font-bold">{{ $leave->total_days }}</span> days</p>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">Reason <span class="text-red-500">*</span></label>
                        <textarea name="reason" id="reason" rows="4"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                            placeholder="Please provide a detailed reason for your leave request..." required>{{ old('reason') ?? $leave->reason }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Attachment -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Attachment (Optional)</label>

                        @if($leave->attachment)
                            <div class="mb-4 flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class='bx bx-file text-xl text-blue-600'></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">Current File</p>
                                    <a href="{{ Storage::url($leave->attachment) }}" target="_blank"
                                        class="text-xs text-emerald-600 hover:underline">View Attachment</a>
                                </div>
                            </div>
                        @endif

                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:border-emerald-400 transition-colors bg-gray-50/50">
                            <div class="space-y-2 text-center">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mx-auto">
                                    <i class='bx bx-cloud-upload text-2xl text-emerald-600'></i>
                                </div>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="attachment" class="relative cursor-pointer font-medium text-emerald-600 hover:text-emerald-500">
                                        <span>Upload a new file</span>
                                        <input id="attachment" name="attachment" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                                <p id="file_name" class="text-sm text-emerald-600 font-medium"></p>
                            </div>
                        </div>
                        @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('doctor.leaves.index') }}"
                            class="px-5 py-2.5 border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                            <i class='bx bx-save mr-2'></i> Update Request
                        </button>
                    </div>
                </form>
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