@extends('layouts.admin')

@section('title', 'Doctor Digital Card')
@section('page-title', 'Doctor Digital Card')

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
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-8">
        <!-- Decorative background elements -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                    <i class='hgi-stroke hgi-identity-card text-2xl'></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Doctor Digital Card</h2>
                    <p class="text-emerald-100 text-sm mt-1">Viewing: <span class="font-bold text-white">{{ $doctor->full_name }}</span></p>
                </div>
            </div>
            <a href="{{ route('admin.digital-cards.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                <i class='hgi-stroke hgi-arrow-left-01'></i>
                Back to All Cards
            </a>
        </div>
    </div>

        <div class="flex flex-col items-center gap-6">
            <div class="w-full flex justify-center gap-3">
                <button onclick="document.getElementById('card-inner').classList.toggle('flipped')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-100 text-emerald-700 font-medium rounded-xl hover:bg-emerald-200 transition text-sm">
                    <i class='hgi-stroke hgi-rotate-left-01'></i> Flip Card
                </button>
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition text-sm shadow">
                    <i class='hgi-stroke hgi-printer'></i> Print
                </button>
            </div>

            <div id="card-print-area" class="card-flip w-full max-w-sm" style="height:220px;">
                <div id="card-inner" class="card-inner relative w-full h-full">
                    {{-- Front --}}
                    <div class="card-face absolute inset-0 rounded-2xl overflow-hidden shadow-2xl id-card-front">
                        <div
                            class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2">
                        </div>
                        <div class="relative p-5 h-full flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    @if($logoUrl)
                                        <img src="{{ $logoUrl }}" class="w-8 h-8 object-contain">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                            <i class='hgi-stroke hgi-hospital-01 text-white text-sm'></i>
                                        </div>
                                    @endif
                                    <span
                                        class="text-white font-extrabold text-xs tracking-wider uppercase">{{ $clinicName }}</span>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-emerald-400/30 border border-emerald-300/30 rounded-full text-[10px] font-bold text-emerald-100 uppercase">Doctor</span>
                            </div>
                            <div class="flex items-center gap-4 mt-2">
                                <div
                                    class="w-16 h-16 rounded-xl overflow-hidden bg-white/20 flex items-center justify-center flex-shrink-0 border-2 border-white/30">
                                    @if($doctor->profile_photo)
                                        <img src="{{ asset('storage/' . $doctor->profile_photo) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span
                                            class="text-white text-2xl font-black">{{ strtoupper(substr($doctor->first_name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-black text-sm leading-tight truncate">{{ $doctor->full_name }}
                                    </p>
                                    <p class="text-emerald-200 text-xs mt-0.5">
                                        {{ $doctor->specialization ?? ucfirst($doctor->type) }}</p>
                                    @if($doctor->qualification)
                                    <p class="text-emerald-300/80 text-[10px] mt-0.5">{{ $doctor->qualification }}</p>@endif
                                    @if($doctor->license_number)
                                        <p class="text-emerald-100 text-[10px] mt-1 font-mono">MMC:
                                    {{ $doctor->license_number }}</p>@endif
                                </div>
                            </div>
                            <div class="flex items-end justify-between mt-2">
                                <div>
                                    <p class="text-emerald-300/60 text-[9px] uppercase tracking-widest">Doctor ID</p>
                                    <p class="text-white font-black text-sm font-mono">{{ $doctor->doctor_id }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-emerald-300/60 text-[9px] uppercase tracking-widest">Phone</p>
                                    <p class="text-white text-[10px]">{{ $doctor->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-16 rounded-full opacity-40 card-hologram">
                        </div>
                    </div>
                    {{-- Back --}}
                    <div class="card-face card-back absolute inset-0 rounded-2xl overflow-hidden shadow-2xl id-card-back">
                        <div class="relative p-5 h-full flex flex-col justify-between">
                            <div class="flex justify-between"><span
                                    class="text-white/60 text-[10px] uppercase font-bold">Official Identity Card</span><span
                                    class="text-white/60 text-[10px]">{{ $clinicName }}</span></div>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center flex-shrink-0 p-1 overflow-hidden">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(56)->margin(0)->generate(route('public.verify.doctor', $doctor->doctor_id)) !!}
                                </div>
                                <div class="flex-1 space-y-1.5 text-white">
                                    @if($doctor->license_number)
                                        <div>
                                            <p class="text-white/40 text-[9px] uppercase">Licence</p>
                                            <p class="text-xs font-bold font-mono">{{ $doctor->license_number }}</p>
                                    </div>@endif
                                    @if($doctor->license_expiry)
                                        <div>
                                            <p class="text-white/40 text-[9px] uppercase">Expiry</p>
                                            <p
                                                class="text-xs font-bold {{ $doctor->license_expiry->isPast() ? 'text-red-400' : 'text-emerald-300' }}">
                                                {{ $doctor->license_expiry->format('d M Y') }}</p>
                                    </div>@endif
                                    @if($doctor->clinic_location)
                                        <div>
                                            <p class="text-white/40 text-[9px] uppercase">Location</p>
                                            <p class="text-xs">{{ $doctor->clinic_location }}</p>
                                    </div>@endif
                                </div>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-white/40 text-[9px] uppercase">Issued</p>
                                    <p class="text-white text-[10px]">
                                        {{ $doctor->card_issued_at?->format('d M Y') ?? 'N/A' }}</p>
                                </div>
                                @if($doctor->card_expires_at)
                                    <div class="text-right">
                                        <p class="text-white/40 text-[9px] uppercase">Valid Until</p>
                                        <p class="text-white text-[10px]">{{ $doctor->card_expires_at->format('d M Y') }}</p>
                                </div>@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-gray-400 text-xs">Click <strong>Flip Card</strong> to see the back.</p>

            {{-- Detail info --}}
            <div class="w-full max-w-sm bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-information-circle text-emerald-500'></i> Card Information
                    </h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach([
                            ['Email', $doctor->email, 'hgi-mail-01'],
                            ['Phone', $doctor->phone ?? 'N/A', 'hgi-call'],
                            ['Specialization', $doctor->specialization ?? 'N/A', 'hgi-stethoscope'],
                            ['Licence No.', $doctor->license_number ?? 'N/A', 'hgi-certificate-01'],
                            ['Experience', $doctor->years_of_experience ? $doctor->years_of_experience . ' years' : 'N/A', 'hgi-calendar-03'],
                            ['Languages', $doctor->languages_spoken ?? 'N/A', 'hgi-translate'],
                            ['Location', $doctor->clinic_location ?? 'N/A', 'hgi-location-01'],
                        ] as [$label, $value, $icon])
                        <div class="flex items-center justify-between px-5 py-3">
                            <span class="text-xs text-gray-500 flex items-center gap-1.5"><i class='hgi-stroke {{ $icon }} text-gray-400'></i>{{ $label }}</span>
                            <span class="text-xs font-semibold text-gray-800">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
