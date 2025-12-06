@extends('layouts.public')

@section('title', 'Book Appointment')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Book Appointment</h1>
                <p class="text-gray-600 mt-1">Schedule a new appointment with our doctors</p>
            </div>

            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <form action="{{ route('patient.appointments.store') }}" method="POST">
                    @csrf

                    <!-- Doctor Selection -->
                    <div class="mb-6">
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-user-plus mr-1'></i>
                            Select Doctor
                        </label>
                        <select name="doctor_id" id="doctor_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Choose a doctor...</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->user->name }} - {{ $doctor->specialization }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Selection -->
                    <div class="mb-6">
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-list-ul mr-1'></i>
                            Select Service
                        </label>
                        <select name="service_id" id="service_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Choose a service...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - ${{ number_format($service->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Selection -->
                    <div class="mb-6">
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-calendar mr-1'></i>
                            Appointment Date
                        </label>
                        <input type="date" name="appointment_date" id="appointment_date" required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('appointment_date') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('appointment_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Selection -->
                    <div class="mb-6">
                        <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-time mr-1'></i>
                            Appointment Time
                        </label>
                        <input type="time" name="appointment_time" id="appointment_time" required
                            value="{{ old('appointment_time') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('appointment_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class='bx bx-note mr-1'></i>
                            Notes (Optional)
                        </label>
                        <input type="hidden" name="notes" id="notes-input" value="{{ old('notes') }}">
                        <div id="notes-editor" class="quill-editor"></div>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('patient.dashboard') }}"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                            <i class='bx bx-check mr-2'></i>
                            Book Appointment
                        </button>
                    </div>
                </form>
            </div>
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
        font-family: system-ui, sans-serif;
        font-size: 0.875rem;
    }
    .quill-wrapper .ql-editor {
        min-height: 100px;
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
        ['blockquote'],
        ['clean']
    ];

    function initQuillEditor(editorId, inputId, placeholder) {
        const container = document.getElementById(editorId);
        const input = document.getElementById(inputId);

        const wrapper = document.createElement('div');
        wrapper.className = 'quill-wrapper';
        container.parentNode.insertBefore(wrapper, container);
        wrapper.appendChild(container);

        const quill = new Quill(container, {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: placeholder || 'Enter content...'
        });

        if (input.value) {
            quill.root.innerHTML = input.value;
        }

        quill.on('text-change', function() {
            input.value = quill.root.innerHTML;
        });

        return quill;
    }

    initQuillEditor('notes-editor', 'notes-input', 'Any additional information or special requests...');
});
</script>
@endpush
@endsection