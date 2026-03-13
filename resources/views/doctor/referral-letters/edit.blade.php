@extends('layouts.doctor', ['hideLayoutTitle' => true])
@section('title', 'Edit Referral Letter — ' . $letter->referral_number)
@section('page-title', 'Edit Referral Letter')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Page Header -->
    <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-6">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
        <div class="relative">
            <a href="{{ route('doctor.referral-letters.show', $letter->id) }}" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                <i class='hgi-stroke hgi-arrow-left-01'></i> Back to Details
            </a>
            <h1 class="text-2xl font-bold flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                    <i class='hgi-stroke hgi-pencil-edit-01 text-xl'></i>
                </div>
                Edit {{ $letter->referral_number }}
            </h1>
            <p class="text-emerald-100 mt-2 flex items-center gap-1">
                <span class="px-2 py-0.5 text-xs bg-white/20 text-white rounded-lg font-semibold backdrop-blur">Draft</span>
                <span>&bull; Changes will overwrite the draft</span>
            </p>
        </div>
    </div>

    <form action="{{ route('doctor.referral-letters.update', $letter->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Patient --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-user text-emerald-500'></i> Patient
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Patient <span class="text-red-500">*</span></label>
                        <select name="patient_id" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" {{ old('patient_id', $letter->patient_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->full_name }} ({{ $p->patient_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Linked Appointment ID <span class="text-gray-400">(optional)</span></label>
                        <input type="text" name="appointment_id" value="{{ old('appointment_id', $letter->appointment_id) }}"
                            placeholder="Appointment ID"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- Referred To --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-arrow-data-transfer-horizontal text-blue-500'></i> Referred To
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Doctor / Specialist Name <span class="text-red-500">*</span></label>
                        <input type="text" name="referred_to_name" value="{{ old('referred_to_name', $letter->referred_to_name) }}" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Specialty <span class="text-red-500">*</span></label>
                        <input type="text" name="referred_to_specialty" value="{{ old('referred_to_specialty', $letter->referred_to_specialty) }}" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Hospital / Facility <span class="text-red-500">*</span></label>
                        <input type="text" name="referred_to_facility" value="{{ old('referred_to_facility', $letter->referred_to_facility) }}" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- Clinical --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-note-01 text-purple-500'></i> Clinical Details
                </h3>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Reason for Referral <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="5" required
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('reason', $letter->reason) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Clinical Notes <span class="text-gray-400">(optional)</span></label>
                    <textarea name="clinical_notes" rows="4"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('clinical_notes', $letter->clinical_notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Meta --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-information-circle text-amber-500'></i> Urgency & Validity
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">Urgency Level <span class="text-red-500">*</span></label>
                        <div class="flex gap-3 flex-wrap">
                            @foreach($urgencies as $key => $label)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="urgency" value="{{ $key }}"
                                    {{ old('urgency', $letter->urgency) === $key ? 'checked' : '' }}
                                    class="w-4 h-4 text-emerald-600">
                                <span class="text-sm font-medium {{ $key === 'routine' ? 'text-emerald-700' : ($key === 'urgent' ? 'text-amber-700' : 'text-red-700') }}">
                                    {{ $label }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Valid Until <span class="text-gray-400">(optional)</span></label>
                        <input type="date" name="valid_until"
                            value="{{ old('valid_until', $letter->valid_until?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('doctor.referral-letters.show', $letter->id) }}"
               class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30">
                <i class='hgi-stroke hgi-floppy-disk'></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
