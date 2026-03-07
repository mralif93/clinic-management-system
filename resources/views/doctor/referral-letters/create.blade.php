@extends('layouts.doctor')
@section('title', 'New Referral Letter')
@section('page-title', 'New Referral Letter')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('doctor.referral-letters.index') }}"
           class="w-9 h-9 bg-white border border-gray-200 rounded-xl flex items-center justify-center shadow-sm hover:shadow-md transition text-gray-600">
            <i class='bx bx-arrow-back text-lg'></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-gray-900">New Referral Letter</h2>
            <p class="text-sm text-gray-500">Create a formal referral for your patient</p>
        </div>
    </div>

    {{-- Context banner --}}
    @if($appointment)
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-center gap-3">
        <i class='bx bx-calendar-check text-blue-500 text-lg'></i>
        <p class="text-sm text-blue-800">
            Pre-filled from appointment on <strong>{{ $appointment->appointment_date->format('d M Y') }}</strong>
            — <strong>{{ $appointment->service->name ?? 'N/A' }}</strong>
        </p>
    </div>
    @endif

    <form action="{{ route('doctor.referral-letters.store') }}" method="POST" class="space-y-4">
        @csrf

        {{-- ── Patient & Appointment ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-user text-emerald-500'></i> Patient
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Patient <span class="text-red-500">*</span></label>
                        <select name="patient_id" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="">Select patient…</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}"
                                    {{ (old('patient_id', $patient?->id) == $p->id) ? 'selected' : '' }}>
                                    {{ $p->full_name }} ({{ $p->patient_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Linked Appointment <span class="text-gray-400">(optional)</span></label>
                        <input type="text" name="appointment_id"
                            value="{{ old('appointment_id', $appointment?->id) }}"
                            placeholder="Appointment ID (if applicable)"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Referred To ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-transfer text-blue-500'></i> Referred To
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Doctor / Specialist Name <span class="text-red-500">*</span></label>
                        <input type="text" name="referred_to_name" value="{{ old('referred_to_name') }}"
                            placeholder="e.g. Dr. Ahmad Ibrahim"
                            required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('referred_to_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Specialty <span class="text-red-500">*</span></label>
                        <input type="text" name="referred_to_specialty" value="{{ old('referred_to_specialty') }}"
                            placeholder="e.g. Cardiology"
                            required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('referred_to_specialty')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Hospital / Facility <span class="text-red-500">*</span></label>
                        <input type="text" name="referred_to_facility" value="{{ old('referred_to_facility') }}"
                            placeholder="e.g. Hospital Pantai KL"
                            required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('referred_to_facility')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Clinical Content ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-notepad text-purple-500'></i> Clinical Details
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Reason for Referral <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="5" required placeholder="Provide a clear clinical reason for the referral…"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('reason') }}</textarea>
                    @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Clinical Notes / Patient History <span class="text-gray-400">(optional)</span></label>
                    <textarea name="clinical_notes" rows="4" placeholder="Relevant history, investigations, medications…"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('clinical_notes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── Meta ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-info-circle text-amber-500'></i> Urgency & Validity
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">Urgency Level <span class="text-red-500">*</span></label>
                        <div class="flex gap-3 flex-wrap">
                            @foreach($urgencies as $key => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="urgency" value="{{ $key }}"
                                    {{ old('urgency', 'routine') === $key ? 'checked' : '' }}
                                    class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm font-medium
                                    {{ $key === 'routine' ? 'text-emerald-700' : ($key === 'urgent' ? 'text-amber-700' : 'text-red-700') }}">
                                    {{ $label }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Valid Until <span class="text-gray-400">(optional)</span></label>
                        <input type="date" name="valid_until" value="{{ old('valid_until') }}"
                            min="{{ now()->addDay()->format('Y-m-d') }}"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('valid_until')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Submit ── --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('doctor.referral-letters.index') }}"
               class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30">
                <i class='bx bx-save'></i> Save as Draft
            </button>
        </div>
    </form>
</div>
@endsection
