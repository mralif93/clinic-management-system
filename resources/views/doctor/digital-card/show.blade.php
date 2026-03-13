@extends('layouts.doctor', ['hideLayoutTitle' => true])

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
            background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 70%, #10b981 100%);
        }

        .id-card-back {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .card-hologram {
            background: conic-gradient(from 0deg, #34d399, #6ee7b7, #34d399, #a7f3d0, #34d399);
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
            class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='hgi-stroke hgi-identity-card text-3xl text-white'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">My Digital Card</h1>
                        <p class="text-emerald-100 text-sm mt-1">Your official clinic identity card</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="document.getElementById('card-inner').classList.toggle('flipped')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white font-medium rounded-xl hover:bg-white/30 transition text-sm">
                        <i class='hgi-stroke hgi-rotate-left-01'></i> Flip Card
                    </button>
                    <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-emerald-700 font-semibold rounded-xl hover:bg-emerald-50 transition text-sm shadow">
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
                            {{-- Decorative Elements --}}
                            <div
                                class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2">
                            </div>
                            <div
                                class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2">
                            </div>

                            <div class="relative p-5 h-full flex flex-col justify-between">
                                {{-- Top Row: Logo + Clinic --}}
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
                                        class="px-2 py-0.5 bg-emerald-400/30 backdrop-blur border border-emerald-300/30 rounded-full text-[10px] font-bold text-emerald-100 uppercase tracking-wider">
                                        Doctor
                                    </span>
                                </div>

                                {{-- Middle: Photo + Info --}}
                                <div class="flex items-center gap-4 mt-2">
                                    <div
                                        class="w-16 h-16 rounded-xl overflow-hidden bg-white/20 flex items-center justify-center flex-shrink-0 border-2 border-white/30">
                                        @if($doctor->profile_photo)
                                            <img src="{{ asset('storage/' . $doctor->profile_photo) }}"
                                                class="w-full h-full object-cover" alt="{{ $doctor->full_name }}">
                                        @else
                                            <span
                                                class="text-white text-2xl font-black">{{ strtoupper(substr($doctor->first_name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white font-black text-sm leading-tight truncate">
                                            {{ $doctor->full_name }}</p>
                                        <p class="text-emerald-200 text-xs mt-0.5">
                                            {{ $doctor->specialization ?? ucfirst($doctor->type) }}</p>
                                        @if($doctor->qualification)
                                            <p class="text-emerald-300/80 text-[10px] mt-0.5">{{ $doctor->qualification }}</p>
                                        @endif
                                        @if($doctor->license_number)
                                            <p class="text-emerald-100 text-[10px] mt-1 font-mono">MMC:
                                                {{ $doctor->license_number }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Bottom Row: ID + Contact --}}
                                <div class="flex items-end justify-between mt-2">
                                    <div>
                                        <p class="text-emerald-300/60 text-[9px] uppercase tracking-widest">Doctor ID</p>
                                        <p class="text-white font-black text-sm font-mono">{{ $doctor->doctor_id }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-emerald-300/60 text-[9px] uppercase tracking-widest">Phone</p>
                                        <p class="text-white text-[10px] font-medium">{{ $doctor->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Hologram strip --}}
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-16 rounded-full opacity-40 card-hologram">
                            </div>
                        </div>

                        {{-- Back Face --}}
                        <div
                            class="card-face card-back absolute inset-0 rounded-2xl overflow-hidden shadow-2xl id-card-back">
                            <div class="relative p-5 h-full flex flex-col justify-between">
                                {{-- Top: Clinic header --}}
                                <div class="flex items-center justify-between">
                                    <span class="text-white/60 text-[10px] uppercase tracking-widest font-bold">Official
                                        Identity Card</span>
                                    <span class="text-white/60 text-[10px]">{{ $clinicName }}</span>
                                </div>

                                {{-- Middle: QR + Details --}}
                                <div class="flex items-center gap-4">
                                    {{-- QR Placeholder --}}
                                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center flex-shrink-0 p-1 overflow-hidden">
                                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(56)->margin(0)->generate(route('public.verify.doctor', $doctor->doctor_id)) !!}
                                    </div>
                                    <div class="flex-1 space-y-1.5 text-white">
                                        @if($doctor->license_number)
                                            <div>
                                                <p class="text-white/40 text-[9px] uppercase tracking-widest">Licence No.</p>
                                                <p class="text-xs font-bold font-mono">{{ $doctor->license_number }}</p>
                                            </div>
                                        @endif
                                        @if($doctor->license_expiry)
                                            <div>
                                                <p class="text-white/40 text-[9px] uppercase tracking-widest">Licence Expiry</p>
                                                <p
                                                    class="text-xs font-bold {{ $doctor->license_expiry->isPast() ? 'text-red-400' : 'text-emerald-300' }}">
                                                    {{ $doctor->license_expiry->format('d M Y') }}
                                                </p>
                                            </div>
                                        @endif
                                        @if($doctor->clinic_location)
                                            <div>
                                                <p class="text-white/40 text-[9px] uppercase tracking-widest">Location</p>
                                                <p class="text-xs font-medium">{{ $doctor->clinic_location }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Bottom: issued/expiry --}}
                                <div class="flex items-end justify-between">
                                    <div>
                                        <p class="text-white/40 text-[9px] uppercase tracking-widest">Issued</p>
                                        <p class="text-white text-[10px] font-medium">
                                            {{ $doctor->card_issued_at ? $doctor->card_issued_at->format('d M Y') : 'N/A' }}
                                        </p>
                                    </div>
                                    @if($doctor->card_expires_at)
                                        <div class="text-right">
                                            <p class="text-white/40 text-[9px] uppercase tracking-widest">Valid Until</p>
                                            <p class="text-white text-[10px] font-medium">
                                                {{ $doctor->card_expires_at->format('d M Y') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <p class="text-gray-400 text-xs text-center">Click <strong>Flip Card</strong> to see the back side.</p>
            </div>

            {{-- Edit Card Details Panel --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <i class='hgi-stroke hgi-edit-01 text-emerald-500'></i> Card Details
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Update card information</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('doctor.digital-card.update') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf
                            @method('PUT')

                            {{-- Profile Photo --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Profile Photo</label>
                                <input type="file" name="profile_photo" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition cursor-pointer border border-gray-200 rounded-xl p-2">
                                @if($doctor->profile_photo)
                                    <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1">
                                        <i class='hgi-stroke hgi-checkmark-circle-02'></i> Photo already uploaded
                                    </p>
                                @endif
                            </div>

                            {{-- License Number --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Licence / MMC Number</label>
                                <input type="text" name="license_number"
                                    value="{{ old('license_number', $doctor->license_number) }}"
                                    placeholder="e.g. MMC 12345"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm">
                            </div>

                            {{-- License Expiry --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Licence Expiry Date</label>
                                <input type="date" name="license_expiry"
                                    value="{{ old('license_expiry', $doctor->license_expiry?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm">
                            </div>

                            {{-- Years of Experience --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Years of Experience</label>
                                <input type="number" name="years_of_experience" min="0" max="70"
                                    value="{{ old('years_of_experience', $doctor->years_of_experience) }}"
                                    placeholder="e.g. 5"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm">
                            </div>

                            {{-- Languages --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Languages Spoken</label>
                                <input type="text" name="languages_spoken"
                                    value="{{ old('languages_spoken', $doctor->languages_spoken) }}"
                                    placeholder="e.g. English, Malay, Mandarin"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm">
                            </div>

                            {{-- Clinic Location --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Room / Clinic
                                    Location</label>
                                <input type="text" name="clinic_location"
                                    value="{{ old('clinic_location', $doctor->clinic_location) }}"
                                    placeholder="e.g. Room 3, Main Branch"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm">
                            </div>

                            {{-- Card Expiry --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Card Valid Until</label>
                                <input type="date" name="card_expires_at"
                                    value="{{ old('card_expires_at', $doctor->card_expires_at?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm">
                            </div>

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition shadow-sm">
                                <i class='hgi-stroke hgi-floppy-disk'></i> Save Card Details
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <i class='hgi-stroke hgi-identity-card text-emerald-600 text-xl'></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">Doctor ID</p>
                    <p class="font-black text-gray-900 font-mono">{{ $doctor->doctor_id }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <i class='hgi-stroke hgi-certificate-01 text-blue-600 text-xl'></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">Experience</p>
                    <p class="font-black text-gray-900">
                        {{ $doctor->years_of_experience ? $doctor->years_of_experience . ' yrs' : 'N/A' }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <i class='hgi-stroke hgi-calendar-03 text-purple-600 text-xl'></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">Card Issued</p>
                    <p class="font-bold text-gray-900 text-sm">
                        {{ $doctor->card_issued_at ? $doctor->card_issued_at->format('d M Y') : 'Today' }}</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div
                    class="w-11 h-11 rounded-xl {{ $doctor->license_expiry && $doctor->license_expiry->isPast() ? 'bg-red-100' : 'bg-teal-100' }} flex items-center justify-center flex-shrink-0">
                    <i
                        class='hgi-stroke hgi-certificate-01 text-{{ $doctor->license_expiry && $doctor->license_expiry->isPast() ? "red" : "teal" }}-600 text-xl'></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">Licence Status</p>
                    <p
                        class="font-bold text-sm {{ $doctor->license_expiry && $doctor->license_expiry->isPast() ? 'text-red-600' : 'text-teal-600' }}">
                        @if($doctor->license_expiry)
                            {{ $doctor->license_expiry->isPast() ? 'Expired' : 'Valid' }}
                        @else
                            Not Set
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection