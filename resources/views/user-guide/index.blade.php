@php
    $layout = 'layouts.public';
    if ($role === 'admin')
        $layout = 'layouts.admin';
    elseif ($role === 'doctor')
        $layout = 'layouts.doctor';
    elseif ($role === 'staff')
        $layout = 'layouts.staff';
@endphp
@extends($layout)

@section('title', 'System User Guide')
@section('page-title', 'User Guide')

@section('content')
    <div class="min-h-screen pb-10" x-data="{ activeSection: 'introduction' }">
        <!-- Header Banner -->
        <div
            class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 mb-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="absolute left-0 bottom-0 w-40 h-40 bg-blue-400/20 rounded-full blur-2xl -ml-10 -mb-10"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2 flex items-center gap-3">
                        <i class='bx bx-book-reader text-4xl text-blue-200'></i>
                        System User Guide
                    </h1>
                    <p class="text-blue-100 text-lg max-w-2xl">
                        Learn how to navigate and make the most out of your {{ ucfirst($role) }} portal. Click on any topic
                        in the sidebar to view step-by-step instructions.
                    </p>
                    <div
                        class="mt-4 inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium">
                        <i class='bx bx-user-circle'></i> Current Role: {{ ucfirst($role) }}
                    </div>
                </div>
                <div class="hidden md:block">
                    <i class='bx bx-compass text-8xl text-white/10 opacity-50 relative -right-4 -bottom-4'></i>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Interactive Sidebar Navigation -->
            <div class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 px-2">Knowledge Base</h3>

                    <nav class="space-y-1.5 font-medium">
                        <!-- Introduction -->
                        <button @click="activeSection = 'introduction'"
                            :class="activeSection === 'introduction' ? 'bg-blue-50 text-blue-600 shadow-sm border-blue-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                            class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 rounded-r-full transition-transform duration-300"
                                :class="activeSection === 'introduction' ? 'scale-y-100' : 'scale-y-0'"></div>
                            <i class='bx bx-info-circle text-xl'
                                :class="activeSection === 'introduction' ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-400'"></i>
                            Introduction
                        </button>

                        <!-- Nav items per role -->
                        @if(in_array($role, ['admin']))
                            <button @click="activeSection = 'dashboard'"
                                :class="activeSection === 'dashboard' ? 'bg-blue-50 text-blue-600 shadow-sm border-blue-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'dashboard' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-home-alt text-xl'
                                    :class="activeSection === 'dashboard' ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-400'"></i>
                                Dashboard & Overview
                            </button>

                            <button @click="activeSection = 'users'"
                                :class="activeSection === 'users' ? 'bg-purple-50 text-purple-600 shadow-sm border-purple-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-purple-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'users' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-group text-xl'
                                    :class="activeSection === 'users' ? 'text-purple-500' : 'text-gray-400 group-hover:text-purple-400'"></i>
                                User Management
                            </button>

                            <button @click="activeSection = 'appointments'"
                                :class="activeSection === 'appointments' ? 'bg-green-50 text-green-600 shadow-sm border-green-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'appointments' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-calendar-event text-xl'
                                    :class="activeSection === 'appointments' ? 'text-green-500' : 'text-gray-400 group-hover:text-green-400'"></i>
                                Appointments
                            </button>
                        @endif

                        @if(in_array($role, ['doctor']))
                            <button @click="activeSection = 'appointments'"
                                :class="activeSection === 'appointments' ? 'bg-green-50 text-green-600 shadow-sm border-green-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'appointments' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-calendar-check text-xl'
                                    :class="activeSection === 'appointments' ? 'text-green-500' : 'text-gray-400 group-hover:text-green-400'"></i>
                                My Appointments
                            </button>

                            <button @click="activeSection = 'patients'"
                                :class="activeSection === 'patients' ? 'bg-amber-50 text-amber-600 shadow-sm border-amber-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'patients' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-user-circle text-xl'
                                    :class="activeSection === 'patients' ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-400'"></i>
                                Patient Records
                            </button>

                            <button @click="activeSection = 'schedule'"
                                :class="activeSection === 'schedule' ? 'bg-indigo-50 text-indigo-600 shadow-sm border-indigo-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'schedule' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-time text-xl'
                                    :class="activeSection === 'schedule' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-400'"></i>
                                My Schedule
                            </button>
                        @endif

                        @if(in_array($role, ['staff']))
                            <button @click="activeSection = 'frontdesk'"
                                :class="activeSection === 'frontdesk' ? 'bg-indigo-50 text-indigo-600 shadow-sm border-indigo-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'frontdesk' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-laptop text-xl'
                                    :class="activeSection === 'frontdesk' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-400'"></i>
                                Front Desk Duties
                            </button>

                            <button @click="activeSection = 'attendance'"
                                :class="activeSection === 'attendance' ? 'bg-orange-50 text-orange-600 shadow-sm border-orange-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'attendance' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-time-five text-xl'
                                    :class="activeSection === 'attendance' ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-400'"></i>
                                Clock In / Out
                            </button>
                        @endif

                        @if(in_array($role, ['patient']))
                            <button @click="activeSection = 'booking'"
                                :class="activeSection === 'booking' ? 'bg-green-50 text-green-600 shadow-sm border-green-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'booking' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-calendar-plus text-xl'
                                    :class="activeSection === 'booking' ? 'text-green-500' : 'text-gray-400 group-hover:text-green-400'"></i>
                                Booking Appointments
                            </button>

                            <button @click="activeSection = 'qr_checkin'"
                                :class="activeSection === 'qr_checkin' ? 'bg-indigo-50 text-indigo-600 shadow-sm border-indigo-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'qr_checkin' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-qr-scan text-xl'
                                    :class="activeSection === 'qr_checkin' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-400'"></i>
                                Using QR Check-in
                            </button>

                            <button @click="activeSection = 'records'"
                                :class="activeSection === 'records' ? 'bg-emerald-50 text-emerald-600 shadow-sm border-emerald-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'records' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='bx bx-folder text-xl'
                                    :class="activeSection === 'records' ? 'text-emerald-500' : 'text-gray-400 group-hover:text-emerald-400'"></i>
                                Medical Records
                            </button>
                        @endif

                        <!-- Common -->
                        <button @click="activeSection = 'settings'"
                            :class="activeSection === 'settings' ? 'bg-gray-800 text-white shadow-sm border-gray-700' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                            class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-400 rounded-r-full transition-transform duration-300"
                                :class="activeSection === 'settings' ? 'scale-y-100' : 'scale-y-0'"></div>
                            <i class='bx bx-cog text-xl'
                                :class="activeSection === 'settings' ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'"></i>
                            Account Settings
                        </button>
                    </nav>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="flex items-center gap-3 text-sm text-gray-500">
                            <i class='bx bx-help-circle text-2xl text-blue-300'></i>
                            <p>Need more help? Contact your administrator.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Content Panel -->
            <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-8 min-h-[600px]">

                <!-- Default Introduction Section -->
                <div x-show="activeSection === 'introduction'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <div class="mb-8">
                        <span
                            class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3 inline-block">Overview</span>
                        <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3 mb-2">
                            Welcome to Clinic Management
                        </h2>
                        <p class="text-gray-500 text-lg">Use the navigation menu on the left to explore specific features
                            and tutorials for your role.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-md transition-shadow">
                            <div
                                class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mb-4 text-blue-600">
                                <i class='bx bx-mouse text-2xl'></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Easy Navigation</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Everything is organized by roles. The sidebar
                                contains your main navigation menu. It changes dynamically depending on the module you're
                                currently visiting.</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-md transition-shadow">
                            <div
                                class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mb-4 text-blue-600">
                                <i class='bx bx-mobile-alt text-2xl'></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Mobile Friendly</h4>
                            <p class="text-gray-600 text-sm leading-relaxed">Access the portal from your smartphone or
                                tablet anywhere. The layout responsively scales to ensure you can work on the go.</p>
                        </div>
                    </div>
                </div>

                <!-- ADMIN SECTIONS -->
                <!-- Admin Dashboard -->
                <div x-show="activeSection === 'dashboard'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3"><i
                            class='bx bx-home-alt text-blue-500 bg-blue-50 p-2 rounded-xl'></i>Dashboard Overview</h2>

                    <div class="space-y-8">
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                                1</div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Check the summary cards</h4>
                                <p class="text-gray-600 mb-4">The top cards provide a quick view of your clinic's pulse:
                                    <strong>Total Patients, Appointments, and Daily Revenue.</strong> Hovering over them
                                    reveals details.</p>
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex items-center gap-3">
                                    <i class='bx bx-line-chart text-2xl text-blue-600'></i>
                                    <span class="text-sm font-medium text-gray-700">Look out for automated indicators
                                        (up/down arrows) displaying month-over-month growth.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin User Management -->
                <div x-show="activeSection === 'users'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3"><i
                            class='bx bx-group text-purple-500 bg-purple-50 p-2 rounded-xl'></i>User Management</h2>

                    <div
                        class="space-y-8 relative before:absolute before:left-4 before:top-2 before:bottom-0 before:w-0.5 before:bg-gray-100">
                        <div class="flex gap-4 relative">
                            <div
                                class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold z-10 ring-4 ring-white">
                                1</div>
                            <div class="pb-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2 cursor-pointer">Navigating to Users</h4>
                                <p class="text-gray-600">On the sidebar banner, click <span
                                        class="px-2 py-0.5 bg-gray-100 rounded text-sm font-mono border">Management >
                                        Users</span> to expand the menu. You can manage <strong>Staff, Doctors, or
                                        Patients</strong>.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 relative">
                            <div
                                class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold z-10 ring-4 ring-white">
                                2</div>
                            <div class="pb-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Adding a New Doctor or Staff</h4>
                                <p class="text-gray-600 mb-4">To integrate a new employee into the system:</p>
                                <ul class="space-y-3 mb-4">
                                    <li class="flex items-start gap-2 text-sm text-gray-600">
                                        <i class='bx bx-check-circle text-green-500 mt-0.5'></i> Go to their respective list
                                        (e.g. Doctors) and click <span
                                            class="text-blue-600 font-semibold px-2 py-0.5 bg-blue-50 rounded mx-1 text-xs">New
                                            Doctor</span>
                                    </li>
                                    <li class="flex items-start gap-2 text-sm text-gray-600">
                                        <i class='bx bx-check-circle text-green-500 mt-0.5'></i> Fill in their personal
                                        information, contact info, and specializations.
                                    </li>
                                    <li class="flex items-start gap-2 text-sm text-gray-600">
                                        <i class='bx bx-check-circle text-green-500 mt-0.5'></i> Once saved, they will
                                        receive login credentials to access their portal.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Appointments -->
                <div x-show="activeSection === 'appointments'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3"><i
                            class='bx bx-calendar text-green-500 bg-green-50 p-2 rounded-xl'></i>Handling Appointments</h2>
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 rounded-r-xl">
                        <p class="text-sm text-amber-800 font-medium">As an administrator, you have the full power to
                            override and edit appointment statuses manually to resolve clinic bottlenecks.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div
                            class="border border-green-100 bg-white shadow-sm p-5 rounded-2xl relative overflow-hidden group">
                            <div
                                class="absolute -right-4 -top-4 w-16 h-16 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500 opacity-50">
                            </div>
                            <i class='bx bx-edit text-3xl text-green-500 mb-3 relative z-10'></i>
                            <h4 class="font-bold text-gray-900 mb-2 relative z-10">Editing Appointments</h4>
                            <p class="text-sm text-gray-600 relative z-10">You can reschedule, change the assigned doctor,
                                or assign multiple services from the booking management window.</p>
                        </div>
                        <div
                            class="border border-red-100 bg-white shadow-sm p-5 rounded-2xl relative overflow-hidden group">
                            <div
                                class="absolute -right-4 -top-4 w-16 h-16 bg-red-50 rounded-full group-hover:scale-150 transition-transform duration-500 opacity-50">
                            </div>
                            <i class='bx bx-x-circle text-3xl text-red-500 mb-3 relative z-10'></i>
                            <h4 class="font-bold text-gray-900 mb-2 relative z-10">Cancellations</h4>
                            <p class="text-sm text-gray-600 relative z-10">If a patient requests cancellation, locate the
                                record and click the <strong>Cancel</strong> badge. The time slot will immediately be freed
                                up.</p>
                        </div>
                    </div>
                </div>

                <!-- STAFF FRONTDESK -->
                <div x-show="activeSection === 'frontdesk'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3"><i
                            class='bx bx-laptop text-indigo-500 bg-indigo-50 p-2 rounded-xl'></i>Front Desk Duties</h2>
                    <p class="text-gray-600 text-lg mb-8">As the first point of contact, navigating patient flow effectively
                        ensures a smooth clinic operation.</p>

                    <div class="space-y-6">
                        <div class="p-6 bg-white border rounded-2xl shadow-sm">
                            <div class="flex items-center gap-4 mb-4">
                                <i class='bx bx-calendar-plus text-3xl text-blue-500'></i>
                                <h4 class="text-xl font-bold text-gray-900">1. Walk-in Registration</h4>
                            </div>
                            <p class="text-gray-600 ml-12 text-sm leading-relaxed">
                                Navigate to <span class="bg-gray-100 px-2 py-0.5 rounded font-mono text-xs border">Patients
                                    > Add New</span>.
                                Collect minimal mandatory fields (Name, IC, Contact) to accelerate the onboarding process.
                                Book their immediate appointment right after saving.
                            </p>
                        </div>

                        <div class="p-6 bg-white border rounded-2xl shadow-sm">
                            <div class="flex items-center gap-4 mb-4">
                                <i class='bx bx-certification text-3xl text-green-500'></i>
                                <h4 class="text-xl font-bold text-gray-900">2. Processing Payments/Invoices</h4>
                            </div>
                            <p class="text-gray-600 ml-12 text-sm leading-relaxed">
                                Once a doctor marks an appointment complete, an invoice is generated. Head to the <span
                                    class="bg-gray-100 px-2 py-0.5 rounded font-mono text-xs border">Appointments >
                                    Finalized</span> list and process the receipt marking it as "Paid".
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ATTENDANCE (STAFF/DOCTOR) -->
                <div x-show="activeSection === 'attendance'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3"><i
                            class='bx bx-time text-orange-500 bg-orange-50 p-2 rounded-xl'></i>Attendance & Time Tracking
                    </h2>
                    <p class="text-gray-600 text-lg mb-8">Manage your shifts efficiently using the automated system.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-100">
                            <i class='bx bx-play-circle text-4xl text-green-500 mb-4 block'></i>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Clocking In</h4>
                            <p class="text-sm text-gray-600">You will see a large "CLOCK IN" button on your main Dashboard
                                when arriving. Pressing it timestamps your exact start time.</p>
                        </div>
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 rounded-2xl border border-amber-100">
                            <i class='bx bx-coffee text-4xl text-amber-500 mb-4 block'></i>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Taking Breaks</h4>
                            <p class="text-sm text-gray-600">Once clocked in, the button turns into "Start Break". Use it
                                during lunches, and don't forget to press "End Break" upon return!</p>
                        </div>
                    </div>
                </div>

                <!-- PATIENT QR CHECKIN -->
                <div x-show="activeSection === 'qr_checkin'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3"><i
                            class='bx bx-qr-scan text-indigo-500 bg-indigo-50 p-2 rounded-xl'></i>Checking in with QR
                        Connect</h2>

                    <div class="bg-gray-50 border rounded-2xl p-6 mb-8 text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=example"
                            alt="QR Illustration" class="mx-auto border bg-white p-2 rounded-xl shadow-sm mb-4">
                        <p class="text-gray-500 font-medium">Fast, contactless queue management</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex gap-4 items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                1</div>
                            <p class="text-gray-700">Open your appointment details page</p>
                        </div>
                        <div class="flex gap-4 items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                2</div>
                            <p class="text-gray-700">Show the automated QR Code snippet visibly on your screen to the
                                reception</p>
                        </div>
                        <div class="flex gap-4 items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                3</div>
                            <p class="text-gray-700">The counter staff will scan it to immediately place you directly into
                                the doctor's queue!</p>
                        </div>
                    </div>
                </div>

                <!-- PATIENT BOOKING -->
                <div x-show="activeSection === 'booking'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3"><i
                            class='bx bx-calendar-plus text-green-500 bg-green-50 p-2 rounded-xl'></i>Booking an Appointment
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div
                            class="text-center p-4 border rounded-2xl bg-white shadow-sm hover:-translate-y-2 transition-transform duration-300">
                            <div
                                class="mx-auto w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4">
                                <i class='bx bx-briefcase text-3xl'></i>
                            </div>
                            <h4 class="font-bold mb-2">1. Choose Service</h4>
                            <p class="text-xs text-gray-500">Pick from consultations or specialist services.</p>
                        </div>

                        <div
                            class="text-center p-4 border rounded-2xl bg-white shadow-sm hover:-translate-y-2 transition-transform duration-300">
                            <div
                                class="mx-auto w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4">
                                <i class='bx bx-time text-3xl'></i>
                            </div>
                            <h4 class="font-bold mb-2">2. Pick Slot</h4>
                            <p class="text-xs text-gray-500">Select a convenient date and an available doctor.</p>
                        </div>

                        <div
                            class="text-center p-4 border rounded-2xl bg-white shadow-sm hover:-translate-y-2 transition-transform duration-300">
                            <div
                                class="mx-auto w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4">
                                <i class='bx bx-check-circle text-3xl'></i>
                            </div>
                            <h4 class="font-bold mb-2">3. Confirm</h4>
                            <p class="text-xs text-gray-500">Review details and confirm appointment.</p>
                        </div>
                    </div>

                    <div class="mt-8 bg-blue-50 rounded-2xl p-6 border border-blue-100 text-blue-800 text-sm">
                        <strong>Tip:</strong> If you face an emergency or wish to change your timeslot, kindly select
                        "Cancel" and make a new booking to save confusion!
                    </div>
                </div>

                <!-- SETTINGS / COMMON -->
                <div x-show="activeSection === 'settings'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    style="display: none;">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3"><i
                            class='bx bx-cog text-gray-600 bg-gray-100 p-2 rounded-xl'></i>Account Settings</h2>
                    <div class="space-y-6">
                        <p class="text-gray-600 text-lg">Maintaining an updated profile ensures optimal clinic
                            communication.</p>

                        <ul class="space-y-4">
                            <li class="flex items-center gap-4 p-4 border rounded-xl hover:bg-gray-50 transition">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex-shrink-0 flex items-center justify-center">
                                    <i class='bx bx-user'></i></div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Updating Personal Info</h4>
                                    <p class="text-xs text-gray-500">Go to My Profile to update your phone number, address,
                                        or emergency contacts.</p>
                                </div>
                            </li>
                            <li class="flex items-center gap-4 p-4 border rounded-xl hover:bg-gray-50 transition">
                                <div
                                    class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex-shrink-0 flex items-center justify-center">
                                    <i class='bx bx-lock-alt'></i></div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Changing Password</h4>
                                    <p class="text-xs text-gray-500">Modify your login credentials regularly in the Profile
                                        settings to assure top security.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection