@extends('layouts.staff')

@section('title', 'My Digital Card')
@section('page-title', 'My Digital Card')

@push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }

            #card-print-area,
            #card-print-area * {
                visibility: visible !important;
            }

            #card-print-area {
                position: fixed;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        .card-flip {
            perspective: 1000px;
        }

        .card-inner {
            transition: transform 0.7s cubic-bezier(.4, 2, .6, 1);
            transform-style: preserve-3d;
        }

        .card-inner.flipped {
            transform: rotateY(180deg);
        }

        .card-face {
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        .card-back {
            transform: rotateY(180deg);
        }

        .id-card-front {
            background: linear-gradient(135deg, #78350f 0%, #92400e 40%, #b45309 70%, #f59e0b 100%);
        }

        .id-card-back {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .card-hologram {
            background: conic-gradient(from 0deg, #fbbf24, #fde68a, #fbbf24, #fef3c7, #fbbf24);
            animation: spin 4s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div
            class="bg-gradient-to-br from-amber-500 via-orange-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='hgi-stroke hgi-identity-card text-3xl text-white'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">My Digital Card</h1>
                        <p class="text-amber-100 text-sm mt-1">Your official clinic identity card</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="document.getElementById('card-inner').classList.toggle('flipped')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white font-medium rounded-xl hover:bg-white/30 transition text-sm">
                        <i class='hgi-stroke hgi-rotate-left-01'></i> Flip Card
                    </button>
                    <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-amber-700 font-semibold rounded-xl hover:bg-amber-50 transition text-sm shadow">
                        <i class='hgi-stroke hgi-printer'></i> Print
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            {{-- Card Preview --}}
            <div class="lg:col-span-3 flex flex-col items-center gap-6">
                <div id="card-print-area" class="card-flip w-full max-w-sm" style="height:220px;">
                    <div id="card-inner" class="card-inner relative w-full h-full">

                        {{-- Front Face --}}
                        <div class="card-face absolute inset-0 rounded-2xl overflow-hidden shadow-2xl id-card-front">
                            <div
                                class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2">
                            </div>
                            <div
                                class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2">
                            </div>

                            <div class="relative p-5 h-full flex flex-col justify-between">
                                {{-- Top: Logo + Type --}}
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        @if($logoUrl)
                                            <img src="{{ $logoUrl }}" class="w-8 h-8 object-contain" alt="{{ $clinicName }}">
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                                <i class='hgi-stroke hgi-hospital-01 text-white text-sm'></i>
                                            </div>
                                        @endif
                                        <span
                                            class="text-white font-extrabold text-xs tracking-wider uppercase">{{ $clinicName }}</span>
                                    </div>
                                    <span
                                        class="px-2 py-0.5 bg-amber-400/30 backdrop-blur border border-amber-300/30 rounded-full text-[10px] font-bold text-amber-100 uppercase tracking-wider">
                                        Staff
                                    </span>
                                </div>

                                {{-- Middle: Photo + Info --}}
                                <div class="flex items-center gap-4 mt-2">
                                    <div
                                        class="w-16 h-16 rounded-xl overflow-hidden bg-white/20 flex items-center justify-center flex-shrink-0 border-2 border-white/30">
                                        @if($staff->profile_photo)
                                            <img src="{{ asset('storage/' . $staff->profile_photo) }}"
                                                class="w-full h-full object-cover" alt="{{ $staff->full_name }}">
                                        @else
                                            <span
                                                class="text-white text-2xl font-black">{{ strtoupper(substr($staff->first_name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white font-black text-sm leading-tight truncate">
                                            {{ $staff->full_name }}</p>
                                        <p class="text-amber-200 text-xs mt-0.5">{{ $staff->position ?? 'Staff' }}</p>
                                        @if($staff->department)
                                            <p class="text-amber-300/80 text-[10px] mt-0.5">{{ $staff->department }}</p>
                                        @endif
                                        @if($staff->clinic_location)
                                            <p class="text-amber-100 text-[10px] mt-1">{{ $staff->clinic_location }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Bottom: ID + Contact --}}
                                <div class="flex items-end justify-between mt-2">
                                    <div>
                                        <p class="text-amber-300/60 text-[9px] uppercase tracking-widest">Staff ID</p>
                                        <p class="text-white font-black text-sm font-mono">{{ $staff->staff_id }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-amber-300/60 text-[9px] uppercase tracking-widest">Phone</p>
                                        <p class="text-white text-[10px] font-medium">{{ $staff->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-16 rounded-full opacity-40 card-hologram">
                            </div>
                        </div>

                        {{-- Back Face --}}
                        <div
                            class="card-face card-back absolute inset-0 rounded-2xl overflow-hidden shadow-2xl id-card-back">
                            <div class="relative p-5 h-full flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <span class="text-white/60 text-[10px] uppercase tracking-widest font-bold">Official
                                        Identity Card</span>
                                    <span class="text-white/60 text-[10px]">{{ $clinicName }}</span>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center flex-shrink-0 p-1 overflow-hidden">
                                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(56)->margin(0)->generate(route('public.verify.staff', $staff->staff_id)) !!}
                                    </div>
                                    <div class="flex-1 space-y-1.5 text-white">
                                        @if($staff->nric)
                                            <div>
                                                <p class="text-white/40 text-[9px] uppercase tracking-widest">IC / NRIC</p>
                                                <p class="text-xs font-bold font-mono">{{ $staff->nric }}</p>
                                            </div>
                                        @endif
                                        @if($staff->blood_type)
                                            <div>
                                                <p class="text-white/40 text-[9px] uppercase tracking-widest">Blood Type</p>
                                                <p class="text-xs font-bold text-red-300">{{ $staff->blood_type }}</p>
                                            </div>
                                        @endif
                                        @if($staff->emergency_contact_name)
                                            <div>
                                                <p class="text-white/40 text-[9px] uppercase tracking-widest">Emergency</p>
                                                <p class="text-xs font-medium">{{ $staff->emergency_contact_name }} ·
                                                    {{ $staff->emergency_contact_phone }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-end justify-between">
                                    <div>
                                        <p class="text-white/40 text-[9px] uppercase tracking-widest">Issued</p>
                                        <p class="text-white text-[10px] font-medium">
                                            {{ $staff->card_issued_at ? $staff->card_issued_at->format('d M Y') : 'N/A' }}
                                        </p>
                                    </div>
                                    @if($staff->card_expires_at)
                                        <div class="text-right">
                                            <p class="text-white/40 text-[9px] uppercase tracking-widest">Valid Until</p>
                                            <p class="text-white text-[10px] font-medium">
                                                {{ $staff->card_expires_at->format('d M Y') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <p class="text-gray-400 text-xs text-center">Click <strong>Flip Card</strong> to see the back side.</p>
            </div>

            {{-- Edit Panel --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <i class='hgi-stroke hgi-edit-01 text-amber-500'></i> Card Details
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Update card information</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('staff.digital-card.update') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Profile Photo</label>
                                <input type="file" name="profile_photo" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition cursor-pointer border border-gray-200 rounded-xl p-2">
                                @if($staff->profile_photo)
                                    <p class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                                        <i class='hgi-stroke hgi-checkmark-circle-02'></i> Photo already uploaded
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">IC / NRIC Number</label>
                                <input type="text" name="nric" value="{{ old('nric', $staff->nric) }}"
                                    placeholder="e.g. 880101-14-5678"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Blood Type</label>
                                <select name="blood_type"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm bg-white">
                                    <option value="">-- Select --</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bt)
                                        <option value="{{ $bt }}" {{ old('blood_type', $staff->blood_type) == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Emergency Contact
                                    Name</label>
                                <input type="text" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name', $staff->emergency_contact_name) }}"
                                    placeholder="Full name"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Emergency Contact
                                    Phone</label>
                                <input type="text" name="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone', $staff->emergency_contact_phone) }}"
                                    placeholder="e.g. 012-3456789"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Room / Clinic
                                    Location</label>
                                <input type="text" name="clinic_location"
                                    value="{{ old('clinic_location', $staff->clinic_location) }}"
                                    placeholder="e.g. Reception, Main Branch"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Card Valid Until</label>
                                <input type="date" name="card_expires_at"
                                    value="{{ old('card_expires_at', $staff->card_expires_at?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition text-sm">
                            </div>

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 transition shadow-sm">
                                <i class='hgi-stroke hgi-floppy-disk'></i> Save Card Details
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <i class='hgi-stroke hgi-identity-card text-amber-600 text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Staff ID</p>
                    <p class="font-black text-gray-900 font-mono">{{ $staff->staff_id }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i class='hgi-stroke hgi-pulse-01 text-red-500 text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Blood Type</p>
                    <p class="font-black text-gray-900">{{ $staff->blood_type ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <i class='hgi-stroke hgi-calendar-03 text-purple-600 text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Card Issued</p>
                    <p class="font-bold text-gray-900 text-sm">
                        {{ $staff->card_issued_at ? $staff->card_issued_at->format('d M Y') : 'Today' }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                    <i class='hgi-stroke hgi-calendar-03 text-teal-600 text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Hire Date</p>
                    <p class="font-bold text-gray-900 text-sm">
                        {{ $staff->hire_date ? $staff->hire_date->format('d M Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection