@extends('layouts.admin')
@section('title', 'Edit Referral Letter')
@section('page-title', 'Edit Referral Letter')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-8">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                    <i class='hgi-stroke hgi-pencil-edit-01 text-2xl'></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Edit Referral Letter</h2>
                    <p class="text-blue-100 text-sm mt-1">Ref No: {{ $letter->referral_number }}</p>
                </div>
            </div>
            <a href="{{ route('admin.referral-letters.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                <i class='hgi-stroke hgi-arrow-left-01'></i>
                Back to List
            </a>
        </div>
    </div>

    @if($letter->isIssued())
    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-3">
        <i class='hgi-stroke hgi-information-circle text-amber-500 text-lg'></i>
        <p class="text-sm text-amber-800">
            <strong>Note:</strong> This letter has already been issued. Some details may be locked or discouraged from changing once issued.
        </p>
    </div>
    @endif

    <form action="{{ route('admin.referral-letters.update', $letter->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- ── Patient & Doctor selection ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-user-group text-blue-500'></i> Patient & Referring Doctor
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Patient <span class="text-red-500">*</span></label>
                        <select name="patient_id" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select patient…</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" {{ old('patient_id', $letter->patient_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->full_name }} ({{ $p->patient_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Referring Doctor <span class="text-red-500">*</span></label>
                        <select name="doctor_id" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select doctor…</option>
                            @foreach($doctors as $d)
                                <option value="{{ $d->id }}" {{ old('doctor_id', $letter->doctor_id) == $d->id ? 'selected' : '' }}>
                                    Dr. {{ $d->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Linked Appointment <span class="text-gray-400">(optional)</span></label>
                        <input type="number" name="appointment_id" value="{{ old('appointment_id', $letter->appointment_id) }}"
                            placeholder="Enter Appointment ID if applicable"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('appointment_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Referred To ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-arrow-data-transfer-horizontal text-indigo-500'></i> Referred To
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Doctor / Specialist Name <span class="text-red-500">*</span></label>
                        <input type="text" name="referred_to_name" value="{{ old('referred_to_name', $letter->referred_to_name) }}"
                            placeholder="e.g. Dr. Ahmad Ibrahim" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('referred_to_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Specialty <span class="text-red-500">*</span></label>
                        <input type="text" name="referred_to_specialty" value="{{ old('referred_to_specialty', $letter->referred_to_specialty) }}"
                            placeholder="e.g. Cardiology" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('referred_to_specialty')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Hospital / Facility <span class="text-red-500">*</span></label>
                        <input type="text" name="referred_to_facility" value="{{ old('referred_to_facility', $letter->referred_to_facility) }}"
                            placeholder="e.g. Hospital Pantai KL" required
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('referred_to_facility')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Clinical Content ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-note-01 text-purple-500'></i> Clinical Details
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Reason for Referral <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="5" required placeholder="Provide a clear clinical reason for the referral…"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('reason', $letter->reason) }}</textarea>
                    @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Clinical Notes / Patient History <span class="text-gray-400">(optional)</span></label>
                    <textarea name="clinical_notes" rows="4" placeholder="Relevant history, investigations, medications…"
                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('clinical_notes', $letter->clinical_notes) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── Meta ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-information-circle text-amber-500'></i> Urgency & Validity
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
                                    {{ old('urgency', $letter->urgency) === $key ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500">
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
                        <input type="date" name="valid_until" value="{{ old('valid_until', $letter->valid_until ? $letter->valid_until->format('Y-m-d') : '') }}"
                            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('valid_until')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Submit ── --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.referral-letters.index') }}"
               class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">
                <i class='hgi-stroke hgi-floppy-disk'></i> Update Letter
            </button>
        </div>
    </form>
</div>
@endsection
