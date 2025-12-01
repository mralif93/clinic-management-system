@extends('layouts.doctor')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('doctor.leaves.index') }}"
                    class="bg-white p-2 rounded-full shadow-sm hover:shadow-md transition-shadow text-gray-600">
                    <i class='bx bx-arrow-back text-2xl'></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Apply for Leave</h1>
                    <p class="text-gray-600 mt-1">Submit a new leave request for approval</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('doctor.leaves.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Leave Type -->
                        <div>
                            <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">Leave Type <span
                                    class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(\App\Models\Leave::getLeaveTypes() as $value => $label)
                                    <label
                                        class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors focus-within:ring-2 focus-within:ring-blue-500">
                                        <input type="radio" name="leave_type" value="{{ $value }}"
                                            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" {{ old('leave_type') == $value ? 'checked' : '' }} required>
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
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Duration Calculation (Auto-updated via JS) -->
                        <div id="duration_display"
                            class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center gap-3">
                            <i class='bx bx-time-five text-blue-600 text-xl'></i>
                            <p class="text-blue-800 font-medium">Total Duration: <span id="total_days">0</span> days</p>
                        </div>

                        <!-- Reason -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason <span
                                    class="text-red-500">*</span></label>
                            <textarea name="reason" id="reason" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Please provide a detailed reason for your leave request..."
                                required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Attachment -->
                        <div>
                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">Attachment
                                (Optional)</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <i class='bx bx-cloud-upload text-4xl text-gray-400'></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="attachment"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
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
                            <a href="{{ route('doctor.leaves.index') }}"
                                class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Cancel</a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                                <i class='bx bx-send'></i>
                                Submit Request
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