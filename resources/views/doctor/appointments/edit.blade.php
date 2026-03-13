@extends('layouts.doctor', ['hideLayoutTitle' => true])

@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div
            class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative">
                <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                    class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                    <i class='hgi-stroke hgi-arrow-left-01'></i> Back to Details
                </a>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='hgi-stroke hgi-pencil-edit-01 text-xl'></i>
                    </div>
                    Edit Appointment
                </h1>
            </div>
        </div>

        <!-- Appointment Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">{{ $appointment->patient->full_name }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $appointment->appointment_date->format('M d, Y') }} at
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                @if($appointment->service)
                                    <span class="text-gray-400">•</span> {{ $appointment->service->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'scheduled' => 'bg-blue-100 text-blue-700',
                                'confirmed' => 'bg-emerald-100 text-emerald-700',
                                'completed' => 'bg-purple-100 text-purple-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                'no_show' => 'bg-amber-100 text-amber-700',
                            ];
                            $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-lg {{ $statusColor }}">
                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('doctor.appointments.update', $appointment->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('status') border-red-500 @enderror">
                        <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>
                            Scheduled</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>
                            Checked In</option>
                        <option value="in_progress" {{ old('status', $appointment->status) == 'in_progress' ? 'selected' : '' }}>In Consultation</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>
                            Completed</option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>
                            Cancelled</option>
                        <option value="no_show" {{ old('status', $appointment->status) == 'no_show' ? 'selected' : '' }}>No
                            Show</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Diagnosis -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='hgi-stroke hgi-search-01 text-gray-400 mr-1'></i> Diagnosis
                    </label>
                    <input type="hidden" name="diagnosis" id="diagnosis-input"
                        value="{{ old('diagnosis', $appointment->diagnosis) }}">
                    <div id="diagnosis-editor" class="quill-editor"></div>
                    @error('diagnosis')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prescription -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='hgi-stroke hgi-medicine-01 text-gray-400 mr-1'></i> Prescription
                    </label>
                    <input type="hidden" name="prescription" id="prescription-input"
                        value="{{ old('prescription', $appointment->prescription) }}">
                    <div id="prescription-editor" class="quill-editor"></div>
                    @error('prescription')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='hgi-stroke hgi-note-01 text-gray-400 mr-1'></i> Notes
                    </label>
                    <input type="hidden" name="notes" id="notes-input" value="{{ old('notes', $appointment->notes) }}">
                    <div id="notes-editor" class="quill-editor"></div>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                        class="px-5 py-2.5 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 transition font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-medium shadow-sm">
                        <i class='hgi-stroke hgi-floppy-disk mr-2'></i>
                        Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet">
        <style>
            .quill-wrapper {
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                overflow: hidden;
                background: white;
            }

            .quill-wrapper .ql-toolbar {
                border: none;
                border-bottom: 1px solid #e5e7eb;
                background: #f9fafb;
                padding: 8px;
            }

            .quill-wrapper .ql-container {
                border: none;
                font-family: 'Inter', system-ui, sans-serif;
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
                border-color: #10b981;
                box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
                color: #10b981;
            }

            .ql-snow.ql-toolbar button:hover .ql-stroke,
            .ql-snow .ql-toolbar button:hover .ql-stroke,
            .ql-snow.ql-toolbar button:focus .ql-stroke,
            .ql-snow .ql-toolbar button:focus .ql-stroke,
            .ql-snow.ql-toolbar button.ql-active .ql-stroke,
            .ql-snow .ql-toolbar button.ql-active .ql-stroke {
                stroke: #10b981;
            }

            .ql-snow.ql-toolbar button:hover .ql-fill,
            .ql-snow .ql-toolbar button:hover .ql-fill,
            .ql-snow.ql-toolbar button:focus .ql-fill,
            .ql-snow .ql-toolbar button:focus .ql-fill,
            .ql-snow.ql-toolbar button.ql-active .ql-fill,
            .ql-snow .ql-toolbar button.ql-active .ql-fill {
                fill: #10b981;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('js/quill.min.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toolbarOptions = [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['blockquote', 'code-block'],
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

                    quill.on('text-change', function () {
                        input.value = quill.root.innerHTML;
                    });

                    return quill;
                }

                initQuillEditor('diagnosis-editor', 'diagnosis-input', 'Enter diagnosis...');
                initQuillEditor('prescription-editor', 'prescription-input', 'Enter prescription...');
                initQuillEditor('notes-editor', 'notes-input', 'Enter notes...');
            });
        </script>
    @endpush
@endsection