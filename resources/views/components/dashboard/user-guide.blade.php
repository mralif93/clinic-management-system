@props(['role' => auth()->user()->role ?? 'admin'])

<div class="mt-8 bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden"
    x-data="{ activeTab: '{{ $role }}', activeAccordion: null }">
    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class='bx bx-book-reader text-blue-600'></i>
                    System User Guide
                </h2>
                <p class="text-sm text-gray-600 mt-1">Comprehensive guide for all modules across different user roles.
                </p>
            </div>
        </div>

        <!-- Role Tabs -->
        <div class="flex flex-wrap gap-2 mt-6">
            @php
                $roles = [
                    'admin' => ['icon' => 'bx-shield', 'label' => 'Admin'],
                    'doctor' => ['icon' => 'bx-plus-medical', 'label' => 'Doctor'],
                    'staff' => ['icon' => 'bx-id-card', 'label' => 'Staff'],
                    'patient' => ['icon' => 'bx-user', 'label' => 'Patient']
                ];
            @endphp

            @foreach($roles as $key => $data)
                <button @click="activeTab = '{{ $key }}'; activeAccordion = null"
                    :class="activeTab === '{{ $key }}' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200'"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                    <i class='bx {{ $data['icon'] }}'></i>
                    {{ $data['label'] }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="p-6">
        @php
            $guides = [
                'admin' => [
                    'Dashboard' => 'Overview of clinic activities, revenue, upcoming appointments, and staff attendance. Use the charts to track performance.',
                    'User Management' => 'Manage administrators, staff, doctors, and patients. You can create, update, delete, and view details for all system users across different roles.',
                    'CMS (Pages/Announcements)' => 'Control the website content. Create and edit pages, edit standard services & packages, update team members, and broadcast announcements.',
                    'Appointments & Schedules' => 'View all booking requests, assign doctors, update appointment statuses, and configure doctor availability and working hours.',
                    'Human Resources' => 'Monitor staff attendance, approve or reject leave requests, and manage payroll records.',
                    'Tools & Reports' => 'Utilize the to-do list for task tracking and generate comprehensive system reports for analytics.'
                ],
                'doctor' => [
                    'Dashboard' => 'View your schedule for the day, active patients, and upcoming appointments.',
                    'Appointments' => 'Check your scheduled sessions. You can view patient details, medical history, and mark appointments as completed.',
                    'Patients' => 'Access profiles of patients assigned to you, including treating history, prescriptions, and notes.',
                    'Schedules' => 'Manage your availability. Block off time for breaks, personal time, or specific procedures.',
                    'Leave & HR' => 'Submit leave requests to administration and view your request statuses.'
                ],
                'staff' => [
                    'Dashboard' => 'Quickly see today\'s patient queue, pending tasks, and recent announcements.',
                    'Patients' => 'Register new patients, update their contact information, and assist with general inquiries.',
                    'Appointments' => 'Help patients book, reschedule, or cancel their appointments. Assist doctors in managing the daily queue.',
                    'Attendance & Leave' => 'Clock in and out daily to record attendance. Submit application for leaves or check your leave balances.'
                ],
                'patient' => [
                    'Dashboard' => 'Your personal health portal. See your next appointment and latest updates.',
                    'Appointments' => 'Book new appointments by choosing a doctor and available time slot. View history of your past visits.',
                    'Medical Records' => 'Access your consultation notes, diagnosis, and treatment plans from your doctors.',
                    'Prescriptions & Invoices' => 'View your prescribed medications and track your billing and payment history.'
                ]
            ];
        @endphp

        <!-- Content for each role -->
        @foreach($guides as $roleKey => $modules)
            <div x-show="activeTab === '{{ $roleKey }}'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                style="display: none;" class="space-y-4">

                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800 capitalize">{{ $roleKey }} Modules Guide</h3>
                    <p class="text-sm text-gray-500">Click on any module to see what you can do.</p>
                </div>

                <div class="space-y-3">
                    @foreach($modules as $moduleName => $description)
                        <div class="border border-gray-100 rounded-lg overflow-hidden transition-all duration-200"
                            :class="activeAccordion === '{{ $roleKey }}-{{ Str::slug($moduleName) }}' ? 'shadow-md ring-1 ring-blue-500/50' : 'hover:border-blue-200'">
                            <button
                                @click="activeAccordion = activeAccordion === '{{ $roleKey }}-{{ Str::slug($moduleName) }}' ? null : '{{ $roleKey }}-{{ Str::slug($moduleName) }}'"
                                class="w-full flex items-center justify-between p-4 bg-white text-left focus:outline-none">
                                <span class="font-semibold text-gray-800">{{ $moduleName }}</span>
                                <i class='bx bx-chevron-down text-xl text-gray-500 transition-transform duration-300'
                                    :class="activeAccordion === '{{ $roleKey }}-{{ Str::slug($moduleName) }}' ? 'rotate-180 text-blue-600' : ''"></i>
                            </button>
                            <div x-show="activeAccordion === '{{ $roleKey }}-{{ Str::slug($moduleName) }}'" x-collapse>
                                <div class="p-4 pt-0 text-gray-600 text-sm border-t border-gray-50 bg-slate-50">
                                    {{ $description }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>