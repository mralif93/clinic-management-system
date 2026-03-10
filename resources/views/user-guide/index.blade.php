@php
    $layout = 'layouts.public';
    if ($role === 'admin') $layout = 'layouts.admin';
    elseif ($role === 'doctor') $layout = 'layouts.doctor';
    elseif ($role === 'staff') $layout = 'layouts.staff';
    $isAdmin = $role === 'admin';
@endphp
@extends($layout)

@section('title', 'System User Guide')
@section('page-title', 'User Guide')

@section('content')
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 20px; }
    </style>
    <!-- Alpine x-data with search query state -->
    <div class="min-h-screen pb-10" x-data="{ activeSection: 'introduction', searchQuery: '', faqOpen: null }">
        <!-- Hero Header -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-3xl p-8 mb-8 text-white shadow-xl   flex flex-col md:flex-row items-center justify-between gap-6 ring-1 ring-white/10 shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute right-0 top-0 w-80 h-80 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
            <div class="absolute left-0 bottom-0 w-40 h-40 bg-blue-400/20 rounded-full blur-2xl -ml-10 -mb-10 pointer-events-none"></div>
            
            <div class="relative z-10 w-full">
                <div class="flex items-center gap-3 mb-2 opacity-80 text-sm font-bold uppercase tracking-widest">
                    <i class='hgi-stroke hgi-help-circle'></i> Support & Training Center
                </div>
                <h1 class="text-4xl lg:text-5xl font-extrabold mb-4 flex items-center gap-3 drop-shadow-md">
                    System User Guide
                </h1>
                <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
                    Master your daily workflows instantly. Find step-by-step instructions, module relationships, and pro tips tailored for your role.
                </p>
            </div>
            
            <div class="relative z-10 shrink-0">
                <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20 text-center shadow-inner">
                    <p class="text-blue-100 text-sm mb-1 uppercase tracking-wider font-semibold">Currently Viewing As</p>
                    <div class="text-2xl font-bold flex items-center justify-center gap-2">
                        <i class='hgi-stroke hgi-user-circle'></i> {{ ucfirst($role) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 sticky top-24 max-h-[calc(100vh-8rem)] overflow-y-auto custom-scrollbar flex flex-col">
                    
                    <!-- Search Bar -->
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class='hgi-stroke hgi-search-01 text-gray-400 text-lg'></i>
                        </div>
                        <input x-model="searchQuery" type="text" placeholder="Search documentation..." 
                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-inner text-sm">
                        <button x-show="searchQuery !== ''" @click="searchQuery = ''" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                            <i class='hgi-stroke hgi-cancel-circle text-xl'></i>
                        </button>
                    </div>

                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 px-2 flex items-center gap-2">
                        <i class='hgi-stroke hgi-book-open-02'></i> Knowledge Base
                    </h3>
                    
                    <nav class="space-y-1.5 font-medium flex-1">
                        <!-- Introduction Button -->
                        <button @click="activeSection = 'introduction'"
                            x-show="searchQuery === '' || 'introduction'.includes(searchQuery.toLowerCase())"
                            :class="activeSection === 'introduction' ? 'bg-blue-50 text-blue-600 shadow-sm border-blue-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                            class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 rounded-r-full transition-transform duration-300"
                                :class="activeSection === 'introduction' ? 'scale-y-100' : 'scale-y-0'"></div>
                            <i class='hgi-stroke hgi-home-01 text-xl' :class="activeSection === 'introduction' ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-400'"></i>
                            Getting Started
                        </button>

                        @if($isAdmin || $role === 'admin')
                            <div x-show="searchQuery === '' || 'Dashboard & Overview'.toLowerCase().includes(searchQuery.toLowerCase()) || 'User Management'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Appointment Management'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Services & Packages'.toLowerCase().includes(searchQuery.toLowerCase()) || 'HR, Payroll & Leave'.toLowerCase().includes(searchQuery.toLowerCase()) || 'System Settings'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Reports'.toLowerCase().includes(searchQuery.toLowerCase())" class="px-4 pt-5 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Admin Modules</div>

                            <button @click="activeSection = 'admin_dashboard'"
                                x-show="searchQuery === '' || 'Dashboard & Overview'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'admin_dashboard' ? 'bg-blue-50 text-blue-600 shadow-sm border-blue-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'admin_dashboard' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-home-05 text-xl' :class="activeSection === 'admin_dashboard' ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-400'"></i>
                                Dashboard & Overview
                            </button>
                            <button @click="activeSection = 'admin_users'"
                                x-show="searchQuery === '' || 'User Management'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'admin_users' ? 'bg-purple-50 text-purple-600 shadow-sm border-purple-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-purple-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'admin_users' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-user-group text-xl' :class="activeSection === 'admin_users' ? 'text-purple-500' : 'text-gray-400 group-hover:text-purple-400'"></i>
                                User Management
                            </button>
                            <button @click="activeSection = 'admin_appointments'"
                                x-show="searchQuery === '' || 'Appointment Management'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'admin_appointments' ? 'bg-green-50 text-green-600 shadow-sm border-green-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'admin_appointments' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-calendar-03 text-xl' :class="activeSection === 'admin_appointments' ? 'text-green-500' : 'text-gray-400 group-hover:text-green-400'"></i>
                                Appointment Management
                            </button>
                            <button @click="activeSection = 'admin_cms'"
                                x-show="searchQuery === '' || 'Services & Packages'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'admin_cms' ? 'bg-indigo-50 text-indigo-600 shadow-sm border-indigo-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'admin_cms' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-layers-01 text-xl' :class="activeSection === 'admin_cms' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-400'"></i>
                                Services & Packages
                            </button>
                            <button @click="activeSection = 'admin_hr'"
                                x-show="searchQuery === '' || 'HR, Payroll & Leave'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'admin_hr' ? 'bg-orange-50 text-orange-600 shadow-sm border-orange-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'admin_hr' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-briefcase-01 text-xl' :class="activeSection === 'admin_hr' ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-400'"></i>
                                HR, Payroll & Leave
                            </button>
                            <button @click="activeSection = 'admin_settings'"
                                x-show="searchQuery === '' || 'System Settings'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'admin_settings' ? 'bg-teal-50 text-teal-600 shadow-sm border-teal-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'admin_settings' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-settings-01 text-xl' :class="activeSection === 'admin_settings' ? 'text-teal-500' : 'text-gray-400 group-hover:text-teal-400'"></i>
                                System Settings
                            </button>
                            <button @click="activeSection = 'admin_reports'"
                                x-show="searchQuery === '' || 'Reports'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'admin_reports' ? 'bg-red-50 text-red-600 shadow-sm border-red-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'admin_reports' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-chart-increase text-xl' :class="activeSection === 'admin_reports' ? 'text-red-500' : 'text-gray-400 group-hover:text-red-400'"></i>
                                Reports
                            </button>                        @endif

                        @if($isAdmin || $role === 'doctor')
                            <div x-show="searchQuery === '' || 'My Appointments'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Patient Records'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Waiting Patients'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Referral Letters'.toLowerCase().includes(searchQuery.toLowerCase()) || 'My Schedule'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Tasks & Leaves'.toLowerCase().includes(searchQuery.toLowerCase())" class="px-4 pt-5 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Doctor Modules</div>

                            <button @click="activeSection = 'doctor_appointments'"
                                x-show="searchQuery === '' || 'My Appointments'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'doctor_appointments' ? 'bg-green-50 text-green-600 shadow-sm border-green-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'doctor_appointments' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-calendar-03 text-xl' :class="activeSection === 'doctor_appointments' ? 'text-green-500' : 'text-gray-400 group-hover:text-green-400'"></i>
                                My Appointments
                            </button>
                            <button @click="activeSection = 'doctor_patients'"
                                x-show="searchQuery === '' || 'Patient Records'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'doctor_patients' ? 'bg-amber-50 text-amber-600 shadow-sm border-amber-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'doctor_patients' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-user-circle text-xl' :class="activeSection === 'doctor_patients' ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-400'"></i>
                                Patient Records
                            </button>
                            <button @click="activeSection = 'doctor_waiting'"
                                x-show="searchQuery === '' || 'Waiting Patients'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'doctor_waiting' ? 'bg-pink-50 text-pink-600 shadow-sm border-pink-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-pink-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'doctor_waiting' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-loading-01 text-xl' :class="activeSection === 'doctor_waiting' ? 'text-pink-500' : 'text-gray-400 group-hover:text-pink-400'"></i>
                                Waiting Patients
                            </button>
                            <button @click="activeSection = 'doctor_referrals'"
                                x-show="searchQuery === '' || 'Referral Letters'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'doctor_referrals' ? 'bg-blue-50 text-blue-600 shadow-sm border-blue-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'doctor_referrals' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-mail-01 text-xl' :class="activeSection === 'doctor_referrals' ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-400'"></i>
                                Referral Letters
                            </button>
                            <button @click="activeSection = 'doctor_schedule'"
                                x-show="searchQuery === '' || 'My Schedule'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'doctor_schedule' ? 'bg-indigo-50 text-indigo-600 shadow-sm border-indigo-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'doctor_schedule' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-clock-02 text-xl' :class="activeSection === 'doctor_schedule' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-400'"></i>
                                My Schedule
                            </button>
                            <button @click="activeSection = 'doctor_hr'"
                                x-show="searchQuery === '' || 'Tasks & Leaves'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'doctor_hr' ? 'bg-orange-50 text-orange-600 shadow-sm border-orange-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'doctor_hr' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-task-01 text-xl' :class="activeSection === 'doctor_hr' ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-400'"></i>
                                Tasks & Leaves
                            </button>                        @endif

                        @if($isAdmin || $role === 'staff')
                            <div x-show="searchQuery === '' || 'Front Desk Duties'.toLowerCase().includes(searchQuery.toLowerCase()) || 'QR Scanner'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Appointments & Invoices'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Patients & Doctors'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Attendance & Leaves'.toLowerCase().includes(searchQuery.toLowerCase())" class="px-4 pt-5 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Staff Modules</div>

                            <button @click="activeSection = 'staff_frontdesk'"
                                x-show="searchQuery === '' || 'Front Desk Duties'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'staff_frontdesk' ? 'bg-indigo-50 text-indigo-600 shadow-sm border-indigo-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'staff_frontdesk' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-computer text-xl' :class="activeSection === 'staff_frontdesk' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-400'"></i>
                                Front Desk Duties
                            </button>
                            <button @click="activeSection = 'staff_qr'"
                                x-show="searchQuery === '' || 'QR Scanner'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'staff_qr' ? 'bg-emerald-50 text-emerald-600 shadow-sm border-emerald-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'staff_qr' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-qr-code text-xl' :class="activeSection === 'staff_qr' ? 'text-emerald-500' : 'text-gray-400 group-hover:text-emerald-400'"></i>
                                QR Scanner
                            </button>
                            <button @click="activeSection = 'staff_appointments'"
                                x-show="searchQuery === '' || 'Appointments & Invoices'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'staff_appointments' ? 'bg-green-50 text-green-600 shadow-sm border-green-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'staff_appointments' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-calendar-03 text-xl' :class="activeSection === 'staff_appointments' ? 'text-green-500' : 'text-gray-400 group-hover:text-green-400'"></i>
                                Appointments & Invoices
                            </button>
                            <button @click="activeSection = 'staff_directory'"
                                x-show="searchQuery === '' || 'Patients & Doctors'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'staff_directory' ? 'bg-amber-50 text-amber-600 shadow-sm border-amber-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'staff_directory' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-user-group text-xl' :class="activeSection === 'staff_directory' ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-400'"></i>
                                Patients & Doctors
                            </button>
                            <button @click="activeSection = 'staff_hr'"
                                x-show="searchQuery === '' || 'Attendance & Leaves'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'staff_hr' ? 'bg-orange-50 text-orange-600 shadow-sm border-orange-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'staff_hr' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-clock-02 text-xl' :class="activeSection === 'staff_hr' ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-400'"></i>
                                Attendance & Leaves
                            </button>                        @endif

                        @if($isAdmin || $role === 'patient')
                            <div x-show="searchQuery === '' || 'Booking Appointments'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Using QR Check-in'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Medical Records'.toLowerCase().includes(searchQuery.toLowerCase()) || 'Profile Settings'.toLowerCase().includes(searchQuery.toLowerCase())" class="px-4 pt-5 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Patient Modules</div>

                            <button @click="activeSection = 'patient_booking'"
                                x-show="searchQuery === '' || 'Booking Appointments'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'patient_booking' ? 'bg-green-50 text-green-600 shadow-sm border-green-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'patient_booking' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-calendar-03 text-xl' :class="activeSection === 'patient_booking' ? 'text-green-500' : 'text-gray-400 group-hover:text-green-400'"></i>
                                Booking Appointments
                            </button>
                            <button @click="activeSection = 'patient_qr'"
                                x-show="searchQuery === '' || 'Using QR Check-in'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'patient_qr' ? 'bg-indigo-50 text-indigo-600 shadow-sm border-indigo-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'patient_qr' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-qr-code text-xl' :class="activeSection === 'patient_qr' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-400'"></i>
                                Using QR Check-in
                            </button>
                            <button @click="activeSection = 'patient_records'"
                                x-show="searchQuery === '' || 'Medical Records'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'patient_records' ? 'bg-emerald-50 text-emerald-600 shadow-sm border-emerald-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'patient_records' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-folder-01 text-xl' :class="activeSection === 'patient_records' ? 'text-emerald-500' : 'text-gray-400 group-hover:text-emerald-400'"></i>
                                Medical Records
                            </button>
                            <button @click="activeSection = 'patient_profile'"
                                x-show="searchQuery === '' || 'Profile Settings'.toLowerCase().includes(searchQuery.toLowerCase())"
                                :class="activeSection === 'patient_profile' ? 'bg-blue-50 text-blue-600 shadow-sm border-blue-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                                class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 rounded-r-full transition-transform duration-300"
                                    :class="activeSection === 'patient_profile' ? 'scale-y-100' : 'scale-y-0'"></div>
                                <i class='hgi-stroke hgi-user text-xl' :class="activeSection === 'patient_profile' ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-400'"></i>
                                Profile Settings
                            </button>                        @endif

                        <!-- FAQ Button -->
                        <div x-show="searchQuery === '' || 'faq questions answers'.includes(searchQuery.toLowerCase())" class="px-4 pt-5 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Help & Support</div>
                        <button @click="activeSection = 'faq'"
                            x-show="searchQuery === '' || 'faq frequently asked questions'.includes(searchQuery.toLowerCase())"
                            :class="activeSection === 'faq' ? 'bg-indigo-50 text-indigo-600 shadow-sm border-indigo-100' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                            class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-full transition-transform duration-300"
                                :class="activeSection === 'faq' ? 'scale-y-100' : 'scale-y-0'"></div>
                            <i class='hgi-stroke hgi-question text-xl bg-gray-100 rounded-full p-0.5' :class="activeSection === 'faq' ? 'text-indigo-500 bg-indigo-100' : 'text-gray-400 group-hover:text-indigo-400'"></i>
                            Frequently Asked Questions
                        </button>

                        <button @click="activeSection = 'settings'"
                            x-show="searchQuery === '' || 'account settings profile password'.includes(searchQuery.toLowerCase())"
                            :class="activeSection === 'settings' ? 'bg-gray-800 text-white shadow-sm border-gray-700' : 'text-gray-600 hover:bg-gray-50 border-transparent'"
                            class="w-full flex items-center gap-3 px-4 py-3 border rounded-xl transition-all duration-200 text-left relative overflow-hidden group mt-2">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-400 rounded-r-full transition-transform duration-300"
                                :class="activeSection === 'settings' ? 'scale-y-100' : 'scale-y-0'"></div>
                            <i class='hgi-stroke hgi-settings-01 text-xl' :class="activeSection === 'settings' ? 'text-white' : 'text-gray-400 group-hover:text-gray-600'"></i>
                            Account Preferences
                        </button>
                    </nav>
                    
                    <!-- Empty Search State -->
                    <div x-show="searchQuery !== '' && !document.querySelector('nav button[style*=\'display: block\']') && !document.querySelector('nav button:not([style*=\'display: none\'])')" x-cloak class="text-center py-8">
                        <i class='hgi-stroke hgi-search-01 text-4xl text-gray-300 mb-2'></i>
                        <p class="text-gray-500 text-sm">No topics found matching your search.</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-10 min-h-[600px] relative">
                
                <!-- INTRODUCTION SECTION -->
                <div x-show="activeSection === 'introduction'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <div class="mb-10 text-center max-w-2xl mx-auto">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-500 mb-4 shadow-inner">
                            <i class='hgi-stroke hgi-rocket-01 text-3xl'></i>
                        </div>
                        @if($isAdmin)
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">Welcome to the Admin Command Center</h2>
                        <p class="text-gray-500 text-lg">You have complete visibility into the user guides for all roles. Select a module from the sidebar or jump directly into common admin tasks below.</p>
                        @else
                        <h2 class="text-3xl font-bold text-gray-900 mb-3">Hi there, {{ ucfirst($role) }}!</h2>
                        <p class="text-gray-500 text-lg">Welcome to your dedicated knowledge base. Use the search bar to find answers quickly or explore your most common tasks below.</p>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2"><i class='hgi-stroke hgi-flash text-amber-500'></i> Quick Access</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">

                        @if($isAdmin)

        <button @click="activeSection = 'admin_dashboard'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-blue-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-home-01'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">Dashboard</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Review clinic analytics.</p>
        </button>

        <button @click="activeSection = 'admin_appointments'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-green-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-calendar-03'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">Appointments</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Manage overall schedules.</p>
        </button>

        <button @click="activeSection = 'admin_hr'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-orange-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-briefcase-01'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">HR & Payroll</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Process staff leaves & pay.</p>
        </button>

                        @elseif($role === 'doctor')

        <button @click="activeSection = 'doctor_appointments'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-green-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-calendar-03'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">Consultations</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Finalize patient visits.</p>
        </button>

        <button @click="activeSection = 'doctor_waiting'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-pink-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-loading-01'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">Live Queue</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Call your next patient.</p>
        </button>

        <button @click="activeSection = 'doctor_patients'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-amber-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-folder-01'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">Records</h3>
            <p class="text-sm text-gray-500 line-clamp-2">View diagnostic histories.</p>
        </button>

                        @elseif($role === 'staff')

        <button @click="activeSection = 'staff_frontdesk'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-indigo-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-computer'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">Front Desk</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Manage patient check-ins.</p>
        </button>

        <button @click="activeSection = 'staff_qr'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-emerald-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-qr-code'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">QR Check-in</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Scan patient booking codes.</p>
        </button>

        <button @click="activeSection = 'staff_appointments'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-green-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-invoice-01'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">Invoices</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Generate out-bound bills.</p>
        </button>

                        @elseif($role === 'patient')

        <button @click="activeSection = 'patient_booking'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-green-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-calendar-03'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">Book Now</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Schedule a new appointment.</p>
        </button>

        <button @click="activeSection = 'patient_qr'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-indigo-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-qr-code'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">My QR Code</h3>
            <p class="text-sm text-gray-500 line-clamp-2">Get code for check-in.</p>
        </button>

        <button @click="activeSection = 'patient_records'; window.scrollTo({top: 0, behavior: 'smooth'})" 
            class="bg-white border hover:border-emerald-300 hover:shadow-lg transition-all p-5 rounded-2xl text-left group">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                <i class='hgi-stroke hgi-folder-open'></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-1">History</h3>
            <p class="text-sm text-gray-500 line-clamp-2">View previous notes.</p>
        </button>

                        @endif
                    </div>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 flex flex-col md:flex-row gap-6 items-center">
                        <div class="w-16 h-16 shrink-0 bg-white rounded-xl shadow-sm flex items-center justify-center text-4xl text-gray-400">
                            <i class='hgi-stroke hgi-search-01'></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">Can't find what you're looking for?</h4>
                            <p class="text-sm text-gray-600 mb-3">Try using the new search bar at the top of the menu, or check our Frequently Asked Questions section.</p>
                            <button @click="activeSection = 'faq'; window.scrollTo({top: 0})" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-lg transition-colors">
                                View FAQs &rarr;
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ADMIN SECTIONS -->

                <div x-show="activeSection === 'admin_dashboard'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-3xl shadow-sm border border-blue-100">
                                <i class='hgi-stroke hgi-home-05'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Dashboard & Overview</h2>
                                <p class="text-blue-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-blue max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>The Dashboard gives you an immediate bird's-eye view of total appointments, revenue, and clinic performance metrics.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-blue-200 rounded-2xl p-6 shadow-sm hover:border-blue-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-blue-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Log into the Admin Portal.</li><li class="mb-3 pl-2">Review the summary cards at the top for quick stats (Patients, Appointments, Revenue).</li><li class="mb-3 pl-2">Check the recent appointments table for the latest bookings.</li><li class="mb-3 pl-2">Review the monthly revenue chart for financial trends.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">Use the dashboard to quickly spot any unassigned appointments or revenue dips without having to dig into reports.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-blue-50/30 border border-blue-200 rounded-2xl p-6 hover:border-blue-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-blue-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-blue-500 mt-1"></i><span><strong>Appointments:</strong> Feeds data into the total appointments count and recent list.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-blue-500 mt-1"></i><span><strong>Invoices/Payments:</strong> Feeds data into the daily revenue calculations.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'admin_users'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center text-3xl shadow-sm border border-purple-100">
                                <i class='hgi-stroke hgi-user-group'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">User Management</h2>
                                <p class="text-purple-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-purple max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Manage Staff, Doctors, and Patients all from one place. You can create accounts, view profiles, and manage access.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-purple-200 rounded-2xl p-6 shadow-sm hover:border-purple-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-purple-100 text-purple-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-purple-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Management > Users</strong>.</li><li class="mb-3 pl-2">Select the category (Staff, Doctor, or Patient).</li><li class="mb-3 pl-2">Click <strong>Add New</strong> to register a new user.</li><li class="mb-3 pl-2">Fill in the required personal and contact information.</li><li class="mb-3 pl-2">Click <strong>Save</strong>. The user can now log in with their IC.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">You can temporarily disable a user's account by changing their Status to Inactive, rather than deleting their record entirely.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-purple-50/30 border border-purple-200 rounded-2xl p-6 hover:border-purple-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-purple-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-purple-100 text-purple-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-purple-500 mt-1"></i><span><strong>Doctors:</strong> Linked to Appointments and Services.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-purple-500 mt-1"></i><span><strong>Patients:</strong> Linked to Appointments and Medical Records.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-purple-500 mt-1"></i><span><strong>Staff:</strong> Linked to Attendance and Front Desk operations.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'admin_appointments'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-3xl shadow-sm border border-green-100">
                                <i class='hgi-stroke hgi-calendar-03'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Appointment Management</h2>
                                <p class="text-green-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-green max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>View and edit all upcoming, past, and cancelled clinic appointments manually to resolve bottlenecks.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-green-200 rounded-2xl p-6 shadow-sm hover:border-green-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-green-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Appointments</strong> from the sidebar.</li><li class="mb-3 pl-2">Use the search or filter options to find a specific appointment.</li><li class="mb-3 pl-2">Click the <strong>Edit</strong> icon (pencil) to modify details.</li><li class="mb-3 pl-2">Change the date, time, assigned doctor, or status as needed.</li><li class="mb-3 pl-2">Click <strong>Update</strong> to save changes.</li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-green-50/30 border border-green-200 rounded-2xl p-6 hover:border-green-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-green-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Patients:</strong> Identifies who the appointment is for.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Doctors:</strong> Identifies who is providing the consultation.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Invoices:</strong> Generated automatically when an appointment is completed.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'admin_cms'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-3xl shadow-sm border border-indigo-100">
                                <i class='hgi-stroke hgi-layers-01'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Services & Packages</h2>
                                <p class="text-indigo-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-indigo max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Update the public-facing Services, Modules, and Packages dynamically to change what patients see.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-indigo-200 rounded-2xl p-6 shadow-sm hover:border-indigo-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-indigo-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>CMS > Services</strong> or <strong>Packages</strong>.</li><li class="mb-3 pl-2">Click <strong>Add New</strong> to create a new offering.</li><li class="mb-3 pl-2">Provide a Title, Description, Icon, and Price.</li><li class="mb-3 pl-2">Toggle the <strong>Visibility</strong> switch to show/hide it on the public site.</li><li class="mb-3 pl-2">Drag and drop items to reorder how they appear publicly.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">If a service is temporarily unavailable (e.g. equipment breakdown), simply toggle its Visibility to Hidden instead of deleting it.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-indigo-50/30 border border-indigo-200 rounded-2xl p-6 hover:border-indigo-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-indigo-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-indigo-500 mt-1"></i><span><strong>Public Homepage:</strong> Determines which services/packages are displayed to anonymous users.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-indigo-500 mt-1"></i><span><strong>Patient Booking:</strong> Determines which services patients can select when booking.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'admin_hr'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-3xl shadow-sm border border-orange-100">
                                <i class='hgi-stroke hgi-briefcase-01'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">HR, Payroll & Leave</h2>
                                <p class="text-orange-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-orange max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Manage Staff and Doctor attendance records, approve leave requests, and process monthly payroll.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-orange-200 rounded-2xl p-6 shadow-sm hover:border-orange-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-orange-100 text-orange-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-orange-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>HR > Attendance</strong> to view daily clock-ins.</li><li class="mb-3 pl-2">Navigate to <strong>HR > Leaves</strong> to review pending leave applications.</li><li class="mb-3 pl-2">Click <strong>Approve</strong> or <strong>Reject</strong> on leave requests.</li><li class="mb-3 pl-2">At the end of the month, go to <strong>HR > Payroll</strong>.</li><li class="mb-3 pl-2">Click <strong>Generate Payroll</strong> to calculate salaries based on attendance and leaves.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">Make sure to approve or reject all pending leaves before clicking Generate Payroll, otherwise the calculations might be inaccurate.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-orange-50/30 border border-orange-200 rounded-2xl p-6 hover:border-orange-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-orange-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-orange-100 text-orange-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-orange-500 mt-1"></i><span><strong>Users (Staff/Doctors):</strong> Associates attendance and leaves with specific employees.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-orange-500 mt-1"></i><span><strong>Payroll:</strong> Uses attendance (lates, absences) and unpaid leaves to calculate final salary.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'admin_settings'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-500 flex items-center justify-center text-3xl shadow-sm border border-teal-100">
                                <i class='hgi-stroke hgi-settings-01'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">System Settings</h2>
                                <p class="text-teal-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-teal max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Update clinic details, logos, working hours, and other core parameters from System Settings.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-teal-200 rounded-2xl p-6 shadow-sm hover:border-teal-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-teal-100 text-teal-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-teal-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Settings</strong> from the bottom of the sidebar.</li><li class="mb-3 pl-2">Update General Info (Clinic Name, Address, Contact, Map URL).</li><li class="mb-3 pl-2">Upload a new Logo or Favicon if needed.</li><li class="mb-3 pl-2">Configure Business Hours (opening and closing times for each day, or mark as closed).</li><li class="mb-3 pl-2">Click <strong>Save Changes</strong>.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">If your clinic is closed on public holidays, manually mark those days as "Closed" in the Custom Holidays tab to prevent online bookings.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-teal-50/30 border border-teal-200 rounded-2xl p-6 hover:border-teal-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-teal-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-teal-100 text-teal-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-teal-500 mt-1"></i><span><strong>Public Site:</strong> Populates the header, footer, and contact pages.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-teal-500 mt-1"></i><span><strong>Booking System:</strong> Restricts available appointment slots based on operating hours.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'admin_reports'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center text-3xl shadow-sm border border-red-100">
                                <i class='hgi-stroke hgi-chart-increase'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Reports</h2>
                                <p class="text-red-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-red max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Export financial data and attendance ledgers for specific date ranges in CSV/JSON format.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-red-200 rounded-2xl p-6 shadow-sm hover:border-red-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-red-100 text-red-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-red-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Reports</strong>.</li><li class="mb-3 pl-2">Select the type of report (e.g., Revenue, Appointments, Attendance).</li><li class="mb-3 pl-2">Set the Date Range (Start Date and End Date).</li><li class="mb-3 pl-2">Click <strong>Generate Report</strong> to view on screen.</li><li class="mb-3 pl-2">Click <strong>Export CSV</strong> or <strong>Export JSON</strong> to download the raw data.</li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-red-50/30 border border-red-200 rounded-2xl p-6 hover:border-red-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-red-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-red-100 text-red-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-red-500 mt-1"></i><span><strong>Appointments/Invoices:</strong> Aggregated for financial and operational reports.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-red-500 mt-1"></i><span><strong>Attendance:</strong> Aggregated for HR reports.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DOCTOR SECTIONS -->

                <div x-show="activeSection === 'doctor_appointments'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-3xl shadow-sm border border-green-100">
                                <i class='hgi-stroke hgi-calendar-03'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">My Appointments</h2>
                                <p class="text-green-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-green max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>View and manage all appointments assigned to you. Mark them complete, add clinical notes, and approve records.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-green-200 rounded-2xl p-6 shadow-sm hover:border-green-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-green-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>My Appointments</strong>.</li><li class="mb-3 pl-2">Click on a patient's name to open the consultation view.</li><li class="mb-3 pl-2">Review their history and input your clinical notes.</li><li class="mb-3 pl-2">If applicable, prescribe medicine or request follow-ups.</li><li class="mb-3 pl-2">Change the status to <strong>Completed</strong>.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">Always ensure you press "Completed" only after the patient leaves your room, as it instantly signals the Front Desk to prepare the invoice.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-green-50/30 border border-green-200 rounded-2xl p-6 hover:border-green-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-green-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Patients:</strong> Links consultation to the patient's permanent medical record.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Invoices:</strong> Triggers the front desk to generate a bill upon completion.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'doctor_patients'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-3xl shadow-sm border border-amber-100">
                                <i class='hgi-stroke hgi-user-circle'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Patient Records</h2>
                                <p class="text-amber-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-amber max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Access full diagnostic histories, past prescriptions, and medical records for patients under your care.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-amber-200 rounded-2xl p-6 shadow-sm hover:border-amber-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-amber-100 text-amber-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-amber-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Patient Records</strong>.</li><li class="mb-3 pl-2">Use the search bar to find a specific patient by Name or IC.</li><li class="mb-3 pl-2">Click <strong>View Details</strong> to open their file.</li><li class="mb-3 pl-2">Browse through tabs for Past Appointments, Diagnoses, and Uploaded Documents.</li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-amber-50/30 border border-amber-200 rounded-2xl p-6 hover:border-amber-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-amber-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-amber-100 text-amber-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-amber-500 mt-1"></i><span><strong>Appointments:</strong> Shows the entire timeline of visits.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-amber-500 mt-1"></i><span><strong>Referrals:</strong> Shows any past referral letters issued.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'doctor_waiting'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center text-3xl shadow-sm border border-pink-100">
                                <i class='hgi-stroke hgi-loading-01'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Waiting Patients</h2>
                                <p class="text-pink-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-pink max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Accept or reject patients waiting in the queue to maintain an orderly clinic flow.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-pink-200 rounded-2xl p-6 shadow-sm hover:border-pink-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-pink-100 text-pink-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-pink-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Waiting Patients</strong>.</li><li class="mb-3 pl-2">Observe the live queue of patients checked in by the front desk.</li><li class="mb-3 pl-2">Click <strong>Accept</strong> to call the next patient into your room.</li><li class="mb-3 pl-2">The patient's status changes from Waiting to In-Consultation.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">If an accepted patient steps away for the bathroom and misses their call, you can temporarily return them to the Waiting queue without canceling.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-pink-50/30 border border-pink-200 rounded-2xl p-6 hover:border-pink-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-pink-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-pink-100 text-pink-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-pink-500 mt-1"></i><span><strong>Staff Front Desk:</strong> Staff check patients into this queue.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-pink-500 mt-1"></i><span><strong>Appointments:</strong> Accepting a patient opens their active appointment record.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'doctor_referrals'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-3xl shadow-sm border border-blue-100">
                                <i class='hgi-stroke hgi-mail-01'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Referral Letters</h2>
                                <p class="text-blue-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-blue max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Generate formal referral letters to specialist centers for your patients directly from the dashboard.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-blue-200 rounded-2xl p-6 shadow-sm hover:border-blue-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-blue-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Referral Letters</strong>.</li><li class="mb-3 pl-2">Click <strong>Create New Referral</strong>.</li><li class="mb-3 pl-2">Select the Patient and specify the Hospital/Specialist name.</li><li class="mb-3 pl-2">Write the clinical findings, diagnosis, and reason for referral.</li><li class="mb-3 pl-2">Click <strong>Generate PDF</strong> to print and sign.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">You can save referral templates for frequently used specialist centers to save typing time.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-blue-50/30 border border-blue-200 rounded-2xl p-6 hover:border-blue-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-blue-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-blue-500 mt-1"></i><span><strong>Patients:</strong> Attaches the letter to the patient's permanent record.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'doctor_schedule'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-3xl shadow-sm border border-indigo-100">
                                <i class='hgi-stroke hgi-clock-02'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">My Schedule</h2>
                                <p class="text-indigo-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-indigo max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Update your recurring weekly availability and breaks so the patient booking system shows correct slots.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-indigo-200 rounded-2xl p-6 shadow-sm hover:border-indigo-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-indigo-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>My Schedule</strong>.</li><li class="mb-3 pl-2">Select your working days (e.g., Monday-Friday).</li><li class="mb-3 pl-2">Set the Start Time and End Time for each active day.</li><li class="mb-3 pl-2">Add Break Times (e.g., Lunch 1PM-2PM).</li><li class="mb-3 pl-2">Click <strong>Save Schedule</strong>.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">Your schedule is recurring. If you need a one-off day off, apply for Leave in the HR module instead of changing this master schedule.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-indigo-50/30 border border-indigo-200 rounded-2xl p-6 hover:border-indigo-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-indigo-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-indigo-500 mt-1"></i><span><strong>Patient Bookings:</strong> Automatically limits online bookings to your defined available slots.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'doctor_hr'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-3xl shadow-sm border border-orange-100">
                                <i class='hgi-stroke hgi-task-01'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Tasks & Leaves</h2>
                                <p class="text-orange-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-orange max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Clock in and out, apply for annual leave, track your assigned To-Do tasks, and view your monthly payslips.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-orange-200 rounded-2xl p-6 shadow-sm hover:border-orange-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-orange-100 text-orange-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-orange-500 marker:font-bold">
                                    <li class="mb-3 pl-2">To Clock In: Click the large <strong>Clock In</strong> button on your dashboard upon arrival.</li><li class="mb-3 pl-2">To Apply Leave: Go to <strong>Tasks & Leaves > Leaves</strong>, select dates, and submit.</li><li class="mb-3 pl-2">To View Payslip: Go to <strong>Tasks & Leaves > Payslips</strong> to download PDF.</li><li class="mb-3 pl-2">To Mark Task Done: Go to <strong>Tasks & Leaves > To-Do</strong> and check off items.</li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-orange-50/30 border border-orange-200 rounded-2xl p-6 hover:border-orange-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-orange-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-orange-100 text-orange-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-orange-500 mt-1"></i><span><strong>Admin HR:</strong> Sends attendance logs, leave requests, and task statuses to administration for review.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STAFF SECTIONS -->

                <div x-show="activeSection === 'staff_frontdesk'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-3xl shadow-sm border border-indigo-100">
                                <i class='hgi-stroke hgi-computer'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Front Desk Duties</h2>
                                <p class="text-indigo-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-indigo max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Monitor live patient flow. Add walk-in appointments, update queues, and prepare patients for doctors.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-indigo-200 rounded-2xl p-6 shadow-sm hover:border-indigo-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-indigo-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Front Desk Duties</strong>.</li><li class="mb-3 pl-2">For walk-ins, click <strong>Quick Book</strong> and enter basic patient info.</li><li class="mb-3 pl-2">When a patient arrives, click <strong>Check-In</strong> to move them to the Waiting Queue.</li><li class="mb-3 pl-2">Monitor the status tags (Waiting, With Doctor, Completed).</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">Keep this page open on a secondary monitor. It automatically refreshes when patients book online or doctors complete consultations.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-indigo-50/30 border border-indigo-200 rounded-2xl p-6 hover:border-indigo-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-indigo-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-indigo-500 mt-1"></i><span><strong>Doctor Waiting List:</strong> Checking a patient in immediately alerts the assigned doctor.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-indigo-500 mt-1"></i><span><strong>Appointments:</strong> Real-time status updates of today's bookings.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'staff_qr'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-3xl shadow-sm border border-emerald-100">
                                <i class='hgi-stroke hgi-qr-code'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">QR Scanner</h2>
                                <p class="text-emerald-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-emerald max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Use the QR Scanner tool to quickly check patients in when they arrive, skipping the manual lookup.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-emerald-200 rounded-2xl p-6 shadow-sm hover:border-emerald-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-emerald-100 text-emerald-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-emerald-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>QR Scanner</strong>.</li><li class="mb-3 pl-2">Ask the patient to show their Appointment QR Code on their phone.</li><li class="mb-3 pl-2">Click <strong>Start Scanner</strong> (requires camera permission) or manually enter the Code.</li><li class="mb-3 pl-2">Once scanned, confirm the details and click <strong>Check-in</strong>.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">For desktop computers without webcams, you can use a USB barcode scanner to read patients' phones directly.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-emerald-50/30 border border-emerald-200 rounded-2xl p-6 hover:border-emerald-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-emerald-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-emerald-100 text-emerald-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-emerald-500 mt-1"></i><span><strong>Patient Portal:</strong> Reads the QR code generated from the patient's booking.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-emerald-500 mt-1"></i><span><strong>Front Desk Flow:</strong> Automates the Check-in step.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'staff_appointments'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-3xl shadow-sm border border-green-100">
                                <i class='hgi-stroke hgi-calendar-03'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Appointments & Invoices</h2>
                                <p class="text-green-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-green max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Manage all clinic appointments, update their status, and generate or process invoices post-consultation.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-green-200 rounded-2xl p-6 shadow-sm hover:border-green-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-green-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Appointments & Invoices</strong>.</li><li class="mb-3 pl-2">Filter by status "Completed" to find patients who just finished with the doctor.</li><li class="mb-3 pl-2">Click <strong>Generate Invoice</strong>.</li><li class="mb-3 pl-2">Add any additional charges (e.g., Pharmacy items, vaccines).</li><li class="mb-3 pl-2">Record the payment method (Cash/Card/Transfer) and mark as <strong>Paid</strong>.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">If a patient hasn't finished paying, keep the invoice status as "Pending". It will remain highlighted so it isn't forgotten at the end of the shift.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-green-50/30 border border-green-200 rounded-2xl p-6 hover:border-green-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-green-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Doctor Appointments:</strong> Invoices are generated based on the doctor's completion.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Admin Reports:</strong> Paid invoices feed directly into the daily revenue ledger.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'staff_directory'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-3xl shadow-sm border border-amber-100">
                                <i class='hgi-stroke hgi-user-group'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Patients & Doctors</h2>
                                <p class="text-amber-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-amber max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Look up patient histories or doctor schedules easily from the directory to assist with phone enquiries.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-amber-200 rounded-2xl p-6 shadow-sm hover:border-amber-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-amber-100 text-amber-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-amber-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Patients & Doctors</strong>.</li><li class="mb-3 pl-2">Use the Search bar to find a specific record.</li><li class="mb-3 pl-2">Click on a Patient to verify their contact details or upcoming bookings.</li><li class="mb-3 pl-2">Click on a Doctor to view their weekly working hours.</li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-amber-50/30 border border-amber-200 rounded-2xl p-6 hover:border-amber-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-amber-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-amber-100 text-amber-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-amber-500 mt-1"></i><span><strong>Admin Data:</strong> Read-only access to the central user database.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'staff_hr'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-3xl shadow-sm border border-orange-100">
                                <i class='hgi-stroke hgi-clock-02'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Attendance & Leaves</h2>
                                <p class="text-orange-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-orange max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Submit attendance clock-ins, apply for leaves, manage assigned tasks, and download current salary slips.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-orange-200 rounded-2xl p-6 shadow-sm hover:border-orange-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-orange-100 text-orange-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-orange-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Use the <strong>Clock In / Clock Out</strong> buttons on your Dashboard daily.</li><li class="mb-3 pl-2">Navigate to <strong>Attendance & Leaves > Leave Requests</strong> to apply for time off.</li><li class="mb-3 pl-2">Check the <strong>To-Do List</strong> to complete tasks assigned by Admin.</li><li class="mb-3 pl-2">Visit the <strong>Payslips</strong> tab to download your monthly salary statements.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">Submit annual leave requests at least 2 weeks in advance to ensure the admin has time to adjust the clinic schedule.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-orange-50/30 border border-orange-200 rounded-2xl p-6 hover:border-orange-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-orange-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-orange-100 text-orange-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-orange-500 mt-1"></i><span><strong>Admin HR:</strong> All actions here push data to the Admin for approval and payroll calculation.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PATIENT SECTIONS -->

                <div x-show="activeSection === 'patient_booking'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center text-3xl shadow-sm border border-green-100">
                                <i class='hgi-stroke hgi-calendar-03'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Booking Appointments</h2>
                                <p class="text-green-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-green max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Browse available services, choose your preferred doctor, and book convenient time slots directly online.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-green-200 rounded-2xl p-6 shadow-sm hover:border-green-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-green-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Booking Appointments</strong>.</li><li class="mb-3 pl-2">Select a <strong>Service</strong> or Package from the list.</li><li class="mb-3 pl-2">Choose an available <strong>Date</strong> and a preferred <strong>Doctor</strong>.</li><li class="mb-3 pl-2">Pick an open <strong>Time Slot</strong>.</li><li class="mb-3 pl-2">Review the details and click <strong>Confirm Booking</strong>.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">Cancel or reschedule your appointment at least 24 hours in advance if you cannot make it.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-green-50/30 border border-green-200 rounded-2xl p-6 hover:border-green-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-green-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-green-100 text-green-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Admin CMS:</strong> Draws available services from the CMS module.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Doctor Schedule:</strong> Only shows times the doctor is available.</span></li><li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-green-500 mt-1"></i><span><strong>Staff Frontdesk:</strong> Notifies staff of the new upcoming visit.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'patient_qr'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-3xl shadow-sm border border-indigo-100">
                                <i class='hgi-stroke hgi-qr-code'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Using QR Check-in</h2>
                                <p class="text-indigo-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-indigo max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Flash the generated QR Code from your appointment screen at the front desk for instant, contactless registration.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-indigo-200 rounded-2xl p-6 shadow-sm hover:border-indigo-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-indigo-500 marker:font-bold">
                                    <li class="mb-3 pl-2">On the day of your visit, log in and view your Upcoming Appointment.</li><li class="mb-3 pl-2">Click <strong>View QR Code</strong>.</li><li class="mb-3 pl-2">Show your phone screen to the Front Desk staff when you arrive at the clinic.</li><li class="mb-3 pl-2">Wait for your name/queue number to be called.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">You can take a screenshot of the QR code before you leave home in case you don't have internet access at the clinic.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-indigo-50/30 border border-indigo-200 rounded-2xl p-6 hover:border-indigo-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-indigo-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-indigo-500 mt-1"></i><span><strong>Staff QR Scanner:</strong> The code is scanned by staff to automate check-in.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'patient_records'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-3xl shadow-sm border border-emerald-100">
                                <i class='hgi-stroke hgi-folder-01'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Medical Records</h2>
                                <p class="text-emerald-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-emerald max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>View previous prescriptions, consultation summaries, and medical invoices submitted by your doctor.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-emerald-200 rounded-2xl p-6 shadow-sm hover:border-emerald-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-emerald-100 text-emerald-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-emerald-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Medical Records</strong>.</li><li class="mb-3 pl-2">Browse your history of past clinic visits.</li><li class="mb-3 pl-2">Click <strong>View Details</strong> on a specific date to read the doctor's notes.</li><li class="mb-3 pl-2">Download attached PDF invoices or Referral Letters if applicable.</li>
                                </ul>
                            </div>
                            
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-emerald-50/30 border border-emerald-200 rounded-2xl p-6 hover:border-emerald-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-emerald-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-emerald-100 text-emerald-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-emerald-500 mt-1"></i><span><strong>Doctor Appointments:</strong> Your records are written and finalized by the doctor.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="activeSection === 'patient_profile'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-3xl shadow-sm border border-blue-100">
                                <i class='hgi-stroke hgi-user'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Profile Settings</h2>
                                <p class="text-blue-600 font-medium">Module Guide</p>
                            </div>
                        </div>
                        <div class="prose prose-blue max-w-none text-gray-600 text-lg leading-relaxed">
                            <p>Keep your contact details up to date and manage your login password securely from the Profile tab.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                        <div class="lg:col-span-3">
                            <div class="bg-white border border-blue-200 rounded-2xl p-6 shadow-sm hover:border-blue-300 transition-colors">
                                <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                                    Step-by-Step Guide
                                </h3>
                                <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-blue-500 marker:font-bold">
                                    <li class="mb-3 pl-2">Navigate to <strong>Profile Settings</strong>.</li><li class="mb-3 pl-2">Update your Phone Number, Email, or Home Address.</li><li class="mb-3 pl-2">Provide an Emergency Contact if desired.</li><li class="mb-3 pl-2">To change your password, enter your current password and the new one, then save.</li>
                                </ul>
                            </div>
                            
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-6 flex gap-4 items-start">
                <div class="bg-amber-100 text-amber-600 rounded-full p-2"><i class='hgi-stroke hgi-idea text-xl'></i></div>
                <div>
                    <h4 class="font-bold text-amber-800 mb-1">Pro Tip</h4>
                    <p class="text-sm text-amber-700">Always use an active email address as this is where password reset links will be sent.</p>
                </div>
            </div>
        
                        </div>
                        <div class="lg:col-span-2">
                            <div class="bg-blue-50/30 border border-blue-200 rounded-2xl p-6 hover:border-blue-300 transition-colors">
                                <h3 class="text-lg font-bold text-gray-800 border-b border-blue-100 pb-3 mb-5 flex items-center gap-2">
                                    <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-hierarchy-square-06'></i></span> 
                                    Module Relationships
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="mb-2 flex items-start gap-2"><i class="hgi-stroke hgi-checkmark-circle-02 text-blue-500 mt-1"></i><span><strong>Admin/Staff Directory:</strong> Automatically updates your details in the clinic's central database.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ SECTION -->
                <div x-show="activeSection === 'faq'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-500 mb-4 shadow-sm border border-indigo-100">
                            <i class='hgi-stroke hgi-question text-3xl'></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Frequently Asked Questions</h2>
                        <p class="text-gray-500 text-lg">Quick answers to common blockers and system usage questions.</p>
                    </div>

                    <div class="max-w-3xl mx-auto space-y-4">
                        <!-- FAQ 1 -->
                        <div class="border border-indigo-100 rounded-xl bg-white shadow-sm overflow-hidden transition-all duration-200 hover:border-indigo-300">
                            <button @click="faqOpen = faqOpen === 1 ? null : 1" class="w-full text-left px-6 py-4 flex justify-between items-center bg-indigo-50/50 flex-col sm:flex-row focus:outline-none">
                                <span class="font-bold text-gray-800">I forgot my password, how do I reset it?</span>
                                <i class='hgi-stroke text-xl text-indigo-400 transition-transform duration-300' :class="faqOpen === 1 ? 'hgi-arrow-up-01' : 'hgi-arrow-down-01'"></i>
                            </button>
                            <div x-show="faqOpen === 1" x-collapse x-cloak>
                                <div class="px-6 py-4 text-gray-600 border-t bg-white leading-relaxed">
                                    If you cannot log in, click the "Forgot Password" link on the login page. An email will be sent to your registered email address with a secure reset link. If you are an employee, you can also ask your Administrator to reset it from the User Management module.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="border border-indigo-100 rounded-xl bg-white shadow-sm overflow-hidden transition-all duration-200 hover:border-indigo-300">
                            <button @click="faqOpen = faqOpen === 2 ? null : 2" class="w-full text-left px-6 py-4 flex justify-between items-center bg-indigo-50/50 flex-col sm:flex-row focus:outline-none">
                                <span class="font-bold text-gray-800">What do I do if a patient's QR code won't scan?</span>
                                <i class='hgi-stroke text-xl text-indigo-400 transition-transform duration-300' :class="faqOpen === 2 ? 'hgi-arrow-up-01' : 'hgi-arrow-down-01'"></i>
                            </button>
                            <div x-show="faqOpen === 2" x-collapse x-cloak>
                                <div class="px-6 py-4 text-gray-600 border-t bg-white leading-relaxed">
                                    Sometimes screen brightness or cracks prevent scanning. If the camera fails, simply look at the alphanumeric code printed beneath the QR code on the patient's phone, and manually type it into the text box on the Staff "QR Scanner" page.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="border border-indigo-100 rounded-xl bg-white shadow-sm overflow-hidden transition-all duration-200 hover:border-indigo-300">
                            <button @click="faqOpen = faqOpen === 3 ? null : 3" class="w-full text-left px-6 py-4 flex justify-between items-center bg-indigo-50/50 flex-col sm:flex-row focus:outline-none">
                                <span class="font-bold text-gray-800">How do I fix a mistake made on an invoice?</span>
                                <i class='hgi-stroke text-xl text-indigo-400 transition-transform duration-300' :class="faqOpen === 3 ? 'hgi-arrow-up-01' : 'hgi-arrow-down-01'"></i>
                            </button>
                            <div x-show="faqOpen === 3" x-collapse x-cloak>
                                <div class="px-6 py-4 text-gray-600 border-t bg-white leading-relaxed">
                                    As long as an invoice is marked "Pending", staff can still amend the contents. However, once an invoice is marked as "Paid", it is locked into the financial reports. You must contact a system Administrator to void or amend a finalized invoice from the Reports module.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="border border-indigo-100 rounded-xl bg-white shadow-sm overflow-hidden transition-all duration-200 hover:border-indigo-300">
                            <button @click="faqOpen = faqOpen === 4 ? null : 4" class="w-full text-left px-6 py-4 flex justify-between items-center bg-indigo-50/50 flex-col sm:flex-row focus:outline-none">
                                <span class="font-bold text-gray-800">Why am I seeing "No Slots Available" when booking?</span>
                                <i class='hgi-stroke text-xl text-indigo-400 transition-transform duration-300' :class="faqOpen === 4 ? 'hgi-arrow-up-01' : 'hgi-arrow-down-01'"></i>
                            </button>
                            <div x-show="faqOpen === 4" x-collapse x-cloak>
                                <div class="px-6 py-4 text-gray-600 border-t bg-white leading-relaxed">
                                    This happens if the doctor's weekly schedule is full, if they are on approved leave, or if the clinic is marked as "Closed" in the master System Settings for that day. Try choosing another available date or a different primary physician.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACCOUNT PREFERENCES -->
                <div x-show="activeSection === 'settings'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="border-b pb-6 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center text-3xl shadow-sm border border-gray-200">
                                <i class='hgi-stroke hgi-settings-01'></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Account Preferences</h2>
                                <p class="text-gray-500 font-medium">Global Settings</p>
                            </div>
                        </div>
                        <div class="text-gray-600 text-lg leading-relaxed">
                            Keep your personal profile and system password updated through the Profile settings menu located top-right.
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 hover:border-gray-300 transition-colors rounded-2xl p-6 shadow-sm mb-6">
                        <h3 class="text-xl font-bold text-gray-800 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                            <span class="bg-gray-100 text-gray-600 p-1.5 rounded-lg inline-flex"><i class='hgi-stroke hgi-list-view'></i></span> 
                            Step-by-Step Guide
                        </h3>
                        <ul class="list-decimal list-outside pl-4 text-gray-700 marker:text-gray-500 marker:font-bold space-y-3">
                            <li>Click your Profile Picture in the top right corner.</li>
                            <li>Select <strong>My Profile</strong> or <strong>Settings</strong>.</li>
                            <li>Update your forms (Password, Contact, Address).</li>
                            <li>Click Save.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
