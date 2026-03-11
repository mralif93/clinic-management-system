@extends('layouts.admin')

@section('title', 'Staff Digital Card')
@section('page-title', 'Staff Digital Card')

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
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-8">
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
                    <h2 class="text-2xl font-bold">Staff Digital Card</h2>
                    <p class="text-amber-100 text-sm mt-1">Viewing: <span class="font-bold text-white">{{ $staff->full_name }}</span></p>
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
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-100 text-amber-700 font-medium rounded-xl hover:bg-amber-200 transition text-sm">
                    <i class='hgi-stroke hgi-rotate-left-01'></i> Flip Card
                </button>
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 transition text-sm shadow">
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
                                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center"><i
                                                class='hgi-stroke hgi-hospital-01 text-white text-sm'></i></div>
                                    @endif
                                    <span
                                        class="text-white font-extrabold text-xs tracking-wider uppercase">{{ $clinicName }}</span>
                                </div>
                                <span
                                    class="px-2 py-0.5 bg-amber-400/30 border border-amber-300/30 rounded-full text-[10px] font-bold text-amber-100 uppercase">Staff</span>
                            </div>
                            <div class="flex items-center gap-4 mt-2">
                                <div
                                    class="w-16 h-16 rounded-xl overflow-hidden bg-white/20 flex items-center justify-center flex-shrink-0 border-2 border-white/30">
                                    @if($staff->profile_photo)<img src="{{ asset('storage/' . $staff->profile_photo) }}"
                                    class="w-full h-full object-cover">@else<span
                                        class="text-white text-2xl font-black">{{ strtoupper(substr($staff->first_name, 0, 1)) }}</span>@endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-black text-sm leading-tight truncate">{{ $staff->full_name }}
                                    </p>
                                    <p class="text-amber-200 text-xs mt-0.5">{{ $staff->position ?? 'Staff' }}</p>
                                    @if($staff->department)
                                    <p class="text-amber-300/80 text-[10px] mt-0.5">{{ $staff->department }}</p>@endif
                                    @if($staff->clinic_location)
                                    <p class="text-amber-100 text-[10px] mt-1">{{ $staff->clinic_location }}</p>@endif
                                </div>
                            </div>
                            <div class="flex items-end justify-between mt-2">
                                <div>
                                    <p class="text-amber-300/60 text-[9px] uppercase tracking-widest">Staff ID</p>
                                    <p class="text-white font-black text-sm font-mono">{{ $staff->staff_id }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-amber-300/60 text-[9px] uppercase tracking-widest">Phone</p>
                                    <p class="text-white text-[10px]">{{ $staff->phone ?? 'N/A' }}</p>
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
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(56)->margin(0)->generate(route('public.verify.staff', $staff->staff_id)) !!}
                                </div>
                                <div class="flex-1 space-y-1.5 text-white">
                                    @if($staff->nric)
                                        <div>
                                            <p class="text-white/40 text-[9px] uppercase">IC / NRIC</p>
                                            <p class="text-xs font-bold font-mono">{{ $staff->nric }}</p>
                                    </div>@endif
                                    @if($staff->blood_type)
                                        <div>
                                            <p class="text-white/40 text-[9px] uppercase">Blood Type</p>
                                            <p class="text-xs font-bold text-red-300">{{ $staff->blood_type }}</p>
                                    </div>@endif
                                    @if($staff->emergency_contact_name)
                                        <div>
                                            <p class="text-white/40 text-[9px] uppercase">Emergency</p>
                                            <p class="text-xs">{{ $staff->emergency_contact_name }} ·
                                                {{ $staff->emergency_contact_phone }}</p>
                                    </div>@endif
                                </div>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-white/40 text-[9px] uppercase">Issued</p>
                                    <p class="text-white text-[10px]">
                                        {{ $staff->card_issued_at?->format('d M Y') ?? 'N/A' }}</p>
                                </div>
                                @if($staff->card_expires_at)
                                    <div class="text-right">
                                        <p class="text-white/40 text-[9px] uppercase">Valid Until</p>
                                        <p class="text-white text-[10px]">{{ $staff->card_expires_at->format('d M Y') }}</p>
                                </div>@endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-gray-400 text-xs">Click <strong>Flip Card</strong> to see the back.</p>

            <div class="w-full max-w-sm bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-information-circle text-amber-500'></i> Card Information
                    </h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach([
                            ['Phone', $staff->phone ?? 'N/A', 'hgi-call'],
                            ['Position', $staff->position ?? 'N/A', 'hgi-briefcase-01'],
                            ['Department', $staff->department ?? 'N/A', 'hgi-building-01'],
                            ['IC / NRIC', $staff->nric ?? 'N/A', 'hgi-identity-card'],
                            ['Blood Type', $staff->blood_type ?? 'N/A', 'hgi-pulse-01'],
                            ['Emergency Contact', $staff->emergency_contact_name ?? 'N/A', 'hgi-call'],
                            ['Emergency Phone', $staff->emergency_contact_phone ?? 'N/A', 'hgi-call'],
                            ['Hire Date', $staff->hire_date ? $staff->hire_date->format('d M Y') : 'N/A', 'hgi-calendar-03'],
                            ['Location', $staff->clinic_location ?? 'N/A', 'hgi-location-01'],
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
