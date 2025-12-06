@extends('layouts.admin')

@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.appointments.update', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Patient -->
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Patient <span class="text-red-500">*</span>
                    </label>
                    <select id="patient_id"
                            name="patient_id"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('patient_id') border-red-500 @enderror">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }} @if($patient->phone)({{ $patient->phone }})@endif
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Doctor -->
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                    <select id="doctor_id"
                            name="doctor_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Not Assigned</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->full_name }} - {{ $doctor->specialization ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Service -->
                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                    <select id="service_id"
                            name="service_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Not Selected</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} ({{ ucfirst($service->type) }}) - {{ get_setting('currency', '$') }}{{ number_format($service->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Appointment Date -->
                <div>
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Appointment Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           id="appointment_date"
                           name="appointment_date"
                           value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('appointment_date') border-red-500 @enderror">
                    @error('appointment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Appointment Time -->
                <div>
                    <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Appointment Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time"
                           id="appointment_time"
                           name="appointment_time"
                           value="{{ old('appointment_time', \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i')) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('appointment_time') border-red-500 @enderror">
                    @error('appointment_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status"
                            name="status"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no_show" {{ old('status', $appointment->status) == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                </div>

                <!-- Fee -->
                <div>
                    <label for="fee" class="block text-sm font-medium text-gray-700 mb-2">Fee</label>
                    <input type="number"
                           id="fee"
                           name="fee"
                           value="{{ old('fee', $appointment->fee) }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Discount Type -->
                <div>
                    <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-2">Discount Type</label>
                    <select id="discount_type"
                            name="discount_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">No Discount</option>
                        <option value="percentage" {{ old('discount_type', $appointment->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('discount_type', $appointment->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                </div>

                <!-- Discount Value -->
                <div>
                    <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">Discount Value</label>
                    <input type="number"
                           id="discount_value"
                           name="discount_value"
                           value="{{ old('discount_value', $appointment->discount_value) }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Payment Status -->
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <select id="payment_status"
                            name="payment_status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @foreach(\App\Models\Appointment::getPaymentStatuses() as $value => $label)
                            <option value="{{ $value }}" {{ old('payment_status', $appointment->payment_status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="payment_method"
                            name="payment_method"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Method</option>
                        @foreach(\App\Models\Appointment::getPaymentMethods() as $value => $label)
                            <option value="{{ $value }}" {{ old('payment_method', $appointment->payment_method) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <input type="hidden" name="notes" id="notes-input" value="{{ old('notes', $appointment->notes) }}">
                    <div id="notes-editor" class="tiptap-editor"></div>
                </div>

                <!-- Diagnosis -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis</label>
                    <input type="hidden" name="diagnosis" id="diagnosis-input" value="{{ old('diagnosis', $appointment->diagnosis) }}">
                    <div id="diagnosis-editor" class="tiptap-editor"></div>
                </div>

                <!-- Prescription -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prescription</label>
                    <input type="hidden" name="prescription" id="prescription-input" value="{{ old('prescription', $appointment->prescription) }}">
                    <div id="prescription-editor" class="tiptap-editor"></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.appointments.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class='bx bx-save mr-2 text-base'></i>
                    Update Appointment
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .quill-wrapper {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        overflow: hidden;
        background: white;
    }
    .quill-wrapper .ql-toolbar {
        border: none;
        border-bottom: 1px solid #d1d5db;
        background: #f9fafb;
        padding: 8px;
    }
    .quill-wrapper .ql-container {
        border: none;
        font-family: 'Poppins', sans-serif;
        font-size: 0.875rem;
    }
    .quill-wrapper .ql-editor {
        min-height: 120px;
        padding: 12px;
        line-height: 1.6;
    }
    .quill-wrapper .ql-editor.ql-blank::before {
        font-style: normal;
        color: #9ca3af;
    }
    .quill-wrapper:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .ql-toolbar.ql-snow .ql-formats {
        margin-right: 10px;
    }
    .ql-snow .ql-picker {
        font-family: 'Poppins', sans-serif;
    }
    .ql-snow.ql-toolbar button:hover,
    .ql-snow .ql-toolbar button:hover,
    .ql-snow.ql-toolbar button:focus,
    .ql-snow .ql-toolbar button:focus,
    .ql-snow.ql-toolbar button.ql-active,
    .ql-snow .ql-toolbar button.ql-active {
        color: #3b82f6;
    }
    .ql-snow.ql-toolbar button:hover .ql-stroke,
    .ql-snow .ql-toolbar button:hover .ql-stroke,
    .ql-snow.ql-toolbar button:focus .ql-stroke,
    .ql-snow .ql-toolbar button:focus .ql-stroke,
    .ql-snow.ql-toolbar button.ql-active .ql-stroke,
    .ql-snow .ql-toolbar button.ql-active .ql-stroke {
        stroke: #3b82f6;
    }
    .ql-snow.ql-toolbar button:hover .ql-fill,
    .ql-snow .ql-toolbar button:hover .ql-fill,
    .ql-snow.ql-toolbar button:focus .ql-fill,
    .ql-snow .ql-toolbar button:focus .ql-fill,
    .ql-snow.ql-toolbar button.ql-active .ql-fill,
    .ql-snow .ql-toolbar button.ql-active .ql-fill {
        fill: #3b82f6;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['blockquote', 'code-block'],
        ['clean']
    ];

    function initQuillEditor(editorId, inputId) {
        const container = document.getElementById(editorId);
        const input = document.getElementById(inputId);

        // Wrap in styled container
        const wrapper = document.createElement('div');
        wrapper.className = 'quill-wrapper';
        container.parentNode.insertBefore(wrapper, container);
        wrapper.appendChild(container);

        const quill = new Quill(container, {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Enter content...'
        });

        // Load initial content
        if (input.value) {
            quill.root.innerHTML = input.value;
        }

        // Sync content to hidden input
        quill.on('text-change', function() {
            input.value = quill.root.innerHTML;
        });

        return quill;
    }

    // Initialize editors
    initQuillEditor('notes-editor', 'notes-input');
    initQuillEditor('diagnosis-editor', 'diagnosis-input');
    initQuillEditor('prescription-editor', 'prescription-input');
});
</script>
@endpush
@endsection

