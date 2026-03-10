@extends('layouts.admin')

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
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 70%, #6366f1 100%);
        }

        .id-card-back {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }

        .card-hologram {
            background: conic-gradient(from 0deg, #818cf8, #c7d2fe, #818cf8, #e0e7ff, #818cf8);
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
            class="bg-gradient-to-br from-indigo-600 via-purple-600 to-violet-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='hgi-stroke hgi-identity-card text-3xl text-white'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">My Digital Card</h1>
                        <p class="text-indigo-100 text-sm mt-1">Your official admin identity card</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.digital-cards.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white font-medium rounded-xl hover:bg-white/30 transition text-sm">
                        <i class='hgi-stroke hgi-user-group'></i> All Cards
                    </a>
                    <button onclick="document.getElementById('card-inner').classList.toggle('flipped')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white font-medium rounded-xl hover:bg-white/30 transition text-sm">
                        <i class='hgi-stroke hgi-rotate-left-01'></i> Flip
                    </button>
                    <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-indigo-700 font-semibold rounded-xl hover:bg-indigo-50 transition text-sm shadow">
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

                        {{-- Front --}}
                        <div class="card-face absolute inset-0 rounded-2xl overflow-hidden shadow-2xl id-card-front">
                            <div
                                class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2">
                            </div>
                            <div
                                class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2">
                            </div>
                            <div class="relative p-5 h-full flex flex-col justify-between">
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
                                        class="px-2 py-0.5 bg-indigo-400/30 backdrop-blur border border-indigo-300/30 rounded-full text-[10px] font-bold text-indigo-100 uppercase tracking-wider">Admin</span>
                                </div>

                                <div class="flex items-center gap-4 mt-2">
                                    <div
                                        class="w-16 h-16 rounded-xl overflow-hidden bg-white/20 flex items-center justify-center flex-shrink-0 border-2 border-white/30">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                                class="w-full h-full object-cover" alt="{{ $user->name }}">
                                        @else
                                            <span
                                                class="text-white text-2xl font-black">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white font-black text-sm leading-tight truncate">{{ $user->name }}
                                        </p>
                                        <p class="text-indigo-200 text-xs mt-0.5">{{ $user->position ?? 'Administrator' }}
                                        </p>
                                        <p class="text-indigo-300/60 text-[10px] mt-0.5">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <div class="flex items-end justify-between mt-2">
                                    <div>
                                        <p class="text-indigo-300/60 text-[9px] uppercase tracking-widest">Role</p>
                                        <p class="text-white font-black text-sm">Admin</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-indigo-300/60 text-[9px] uppercase tracking-widest">Phone</p>
                                        <p class="text-white text-[10px] font-medium">{{ $user->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-16 rounded-full opacity-40 card-hologram">
                            </div>
                        </div>

                        {{-- Back --}}
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
                                        {{-- Note: Admin doesn't have a verify route yet, so we just generate a generic QR or skip. We'll link to staff verify with their ID if applicable, or generic clinic URL --}}
                                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(56)->margin(0)->generate(url('/')) !!}
                                    </div>
                                    <div class="flex-1 space-y-1.5 text-white">
                                        @if($user->nric)
                                            <div>
                                                <p class="text-white/40 text-[9px] uppercase tracking-widest">IC / NRIC</p>
                                                <p class="text-xs font-bold font-mono">{{ $user->nric }}</p>
                                            </div>
                                        @endif
                                        @if($user->blood_type)
                                            <div>
                                                <p class="text-white/40 text-[9px] uppercase tracking-widest">Blood Type</p>
                                                <p class="text-xs font-bold text-red-300">{{ $user->blood_type }}</p>
                                            </div>
                                        @endif
                                        @if($user->emergency_contact_name)
                                            <div>
                                                <p class="text-white/40 text-[9px] uppercase tracking-widest">Emergency</p>
                                                <p class="text-xs font-medium">{{ $user->emergency_contact_name }} ·
                                                    {{ $user->emergency_contact_phone }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-end justify-between">
                                    <div>
                                        <p class="text-white/40 text-[9px] uppercase tracking-widest">Issued</p>
                                        <p class="text-white text-[10px] font-medium">
                                            {{ $user->card_issued_at ? $user->card_issued_at->format('d M Y') : 'N/A' }}</p>
                                    </div>
                                    @if($user->card_expires_at)
                                        <div class="text-right">
                                            <p class="text-white/40 text-[9px] uppercase tracking-widest">Valid Until</p>
                                            <p class="text-white text-[10px] font-medium">
                                                {{ $user->card_expires_at->format('d M Y') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-gray-400 text-xs text-center">Click <strong>Flip</strong> to see the back side.</p>
            </div>

            {{-- Edit Panel --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                            <i class='hgi-stroke hgi-edit-01 text-indigo-500'></i> Card Details
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Update card information</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.digital-card.update') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Profile Photo</label>
                                <input type="file" name="profile_photo" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer border border-gray-200 rounded-xl p-2">
                                @if($user->profile_photo)
                                    <p class="text-xs text-indigo-600 mt-1 flex items-center gap-1">
                                        <i class='hgi-stroke hgi-checkmark-circle-02'></i> Photo already uploaded
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">IC / NRIC Number</label>
                                <input type="text" name="nric" value="{{ old('nric', $user->nric) }}"
                                    placeholder="e.g. 880101-14-5678"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Blood Type</label>
                                <select name="blood_type"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm bg-white">
                                    <option value="">-- Select --</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bt)
                                        <option value="{{ $bt }}" {{ old('blood_type', $user->blood_type) == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Emergency Contact
                                    Name</label>
                                <input type="text" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}"
                                    placeholder="Full name"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Emergency Contact
                                    Phone</label>
                                <input type="text" name="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}"
                                    placeholder="e.g. 012-3456789"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Card Valid Until</label>
                                <input type="date" name="card_expires_at"
                                    value="{{ old('card_expires_at', $user->card_expires_at?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm">
                            </div>

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                                <i class='hgi-stroke hgi-floppy-disk'></i> Save Card Details
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection