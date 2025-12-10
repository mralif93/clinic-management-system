@php
    $defaultAboutValues = json_encode([
        ['icon' => 'bx-heart', 'title' => 'Compassion first', 'description' => 'We listen deeply, respect every story, and treat people—never just symptoms.', 'accent' => 'text-rose-600 bg-rose-50'],
        ['icon' => 'bx-shield', 'title' => 'Safety and trust', 'description' => 'Privacy, clinical standards, and secure systems keep care safe and reliable.', 'accent' => 'text-blue-600 bg-blue-50'],
        ['icon' => 'bx-bar-chart', 'title' => 'Results with data', 'description' => 'Evidence-based protocols paired with outcomes tracking to guide every decision.', 'accent' => 'text-emerald-600 bg-emerald-50'],
        ['icon' => 'bx-group', 'title' => 'Teamwork', 'description' => 'Doctors, therapists, and support staff collaborate to give seamless care.', 'accent' => 'text-indigo-600 bg-indigo-50'],
    ], JSON_PRETTY_PRINT);

    $defaultAboutTimeline = json_encode([
        ['year' => '2018', 'title' => 'Clinic founded', 'description' => 'Started as a small practice focused on patient-centered care.'],
        ['year' => '2020', 'title' => 'Digital transformation', 'description' => 'Rolled out online appointments, telehealth, and integrated records.'],
        ['year' => '2022', 'title' => 'Specialty expansion', 'description' => 'Added homeopathy, psychotherapy, and preventive wellness programs.'],
        ['year' => '2024', 'title' => 'Community impact', 'description' => 'Launched outreach clinics and mental health awareness initiatives.'],
    ], JSON_PRETTY_PRINT);

    $defaultTeamLeads = json_encode([
        ['name' => 'Dr. Aisha Rahman', 'role' => 'Medical Director', 'focus' => 'Integrative care & patient safety', 'bio' => 'Guides clinical standards and quality programs across all specialties.'],
        ['name' => 'Dr. Marcus Lee', 'role' => 'Head of Psychology', 'focus' => 'Trauma-informed therapy', 'bio' => 'Leads multidisciplinary therapy programs with measurable outcomes.'],
        ['name' => 'Dr. Priya Menon', 'role' => 'Lead Homeopath', 'focus' => 'Holistic wellness', 'bio' => 'Designs personalized care plans blending traditional and modern approaches.'],
        ['name' => 'Sofia Gomez, RN', 'role' => 'Patient Experience', 'focus' => 'Care coordination', 'bio' => 'Ensures every visit is smooth—from scheduling to follow-up.'],
    ], JSON_PRETTY_PRINT);

    $defaultTeamCare = json_encode([
        ['title' => 'Psychologists & Therapists', 'members' => ['Clinical psychologists', 'Family & couples therapists', 'Child and adolescent specialists', 'Trauma and EMDR practitioners'], 'color' => 'border-blue-100 bg-blue-50'],
        ['title' => 'Homeopathy & Wellness', 'members' => ['Certified homeopaths', 'Lifestyle and nutrition coaches', 'Sleep and stress specialists', 'Preventive wellness educators'], 'color' => 'border-emerald-100 bg-emerald-50'],
        ['title' => 'Clinical Support', 'members' => ['Registered nurses', 'Care coordinators', 'Laboratory & diagnostics', 'Patient outreach and education'], 'color' => 'border-indigo-100 bg-indigo-50'],
    ], JSON_PRETTY_PRINT);
@endphp

@php
    $isAbout = !isset($mode) || $mode === 'about';
    $isTeam = !isset($mode) || $mode === 'team';

    $aboutValuesArr = json_decode(get_setting('about_values', $defaultAboutValues), true) ?? [];
    $aboutTimelineArr = json_decode(get_setting('about_timeline', $defaultAboutTimeline), true) ?? [];
    $teamLeadershipArr = json_decode(get_setting('team_leadership', $defaultTeamLeads), true) ?? [];
    $teamCareArr = json_decode(get_setting('team_care_teams', $defaultTeamCare), true) ?? [];
@endphp

@if($isAbout)
    <div class="space-y-4">
        <div class="space-y-3">
            <div>
                <label class="text-xs font-semibold text-gray-700">Hero title</label>
                <input type="text" value="{{ get_setting('about_hero_title', 'Compassionate care powered by smart systems.') }}"
                    data-key="about_hero_title" onchange="autoSave('about_hero_title', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-700">Hero subtitle</label>
                <textarea rows="3" data-key="about_hero_subtitle"
                    onchange="autoSave('about_hero_subtitle', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                    placeholder="Short paragraph for the hero section">{{ get_setting('about_hero_subtitle', 'We built this clinic platform to make healthcare simpler—for patients booking visits, clinicians coordinating care, and teams running the day-to-day. Every feature is designed with safety, clarity, and trust in mind.') }}</textarea>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-700">Values</p>
                        <p class="text-[11px] text-gray-500">Icon, title, description, accent</p>
                    </div>
                    <button type="button" onclick="pageEditor.addItem('about-values')"
                        class="inline-flex items-center gap-2 px-3 h-9 rounded-full bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 text-xs font-semibold transition">
                        <span class="w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center text-[11px]">+</span>
                        Add value
                    </button>
                </div>
                <div id="about-values" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($aboutValuesArr as $idx => $item)
                        <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="{{ $idx }}">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-semibold">{{ $idx + 1 }}</span>
                                    <div class="text-sm font-semibold text-gray-800">Value</div>
                                </div>
                                <button type="button"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                                    onclick="pageEditor.removeItem('about-values', {{ $idx }})">×</button>
                            </div>
                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Title</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        value="{{ $item['title'] ?? '' }}" data-field="title" oninput="pageEditor.sync('about-values')">
                                </div>
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Icon (Boxicons)</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        value="{{ $item['icon'] ?? '' }}" data-field="icon" oninput="pageEditor.sync('about-values')">
                                </div>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Description</label>
                                <textarea rows="2" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    data-field="description" oninput="pageEditor.sync('about-values')">{{ $item['description'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Accent classes</label>
                                <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    value="{{ $item['accent'] ?? '' }}" data-field="accent" oninput="pageEditor.sync('about-values')"
                                    placeholder="e.g., text-rose-600 bg-rose-50">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-700">Timeline</p>
                        <p class="text-[11px] text-gray-500">Year, title, description</p>
                    </div>
                    <button type="button" onclick="pageEditor.addItem('about-timeline')"
                        class="inline-flex items-center gap-2 px-3 h-9 rounded-full bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-100 text-xs font-semibold transition">
                        <span class="w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center text-[11px]">+</span>
                        Add timeline
                    </button>
                </div>
                <div id="about-timeline" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($aboutTimelineArr as $idx => $item)
                        <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="{{ $idx }}">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-semibold">{{ $idx + 1 }}</span>
                                    <div class="text-sm font-semibold text-gray-800">Milestone</div>
                                </div>
                                <button type="button"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                                    onclick="pageEditor.removeItem('about-timeline', {{ $idx }})">×</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Year</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        value="{{ $item['year'] ?? '' }}" data-field="year" oninput="pageEditor.sync('about-timeline')">
                                </div>
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Title</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                        value="{{ $item['title'] ?? '' }}" data-field="title" oninput="pageEditor.sync('about-timeline')">
                                </div>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Description</label>
                                <textarea rows="2" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                    data-field="description" oninput="pageEditor.sync('about-timeline')">{{ $item['description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="text-xs font-semibold text-gray-700">CTA title</label>
                    <input type="text" value="{{ get_setting('about_cta_title', 'See how our team can help') }}"
                        data-key="about_cta_title" onchange="autoSave('about_cta_title', this.value)"
                        class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700">CTA subtitle</label>
                    <textarea rows="2" data-key="about_cta_subtitle"
                        onchange="autoSave('about_cta_subtitle', this.value)"
                        class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                        placeholder="Supporting line under the CTA title">{{ get_setting('about_cta_subtitle', 'Explore services tailored to your needs, or meet the clinicians behind your care.') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-2">
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Primary button label</label>
                            <input type="text" value="{{ get_setting('about_cta_primary_label', 'Meet our team') }}"
                                data-key="about_cta_primary_label" onchange="autoSave('about_cta_primary_label', this.value)"
                                class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                placeholder="e.g., Meet our team">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Primary button link</label>
                            <input type="text" value="{{ get_setting('about_cta_primary_link', route('team')) }}"
                                data-key="about_cta_primary_link" onchange="autoSave('about_cta_primary_link', this.value)"
                                class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                placeholder="URL or path, e.g., {{ route('team') }}">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Secondary button label</label>
                            <input type="text" value="{{ get_setting('about_cta_secondary_label', 'View services') }}"
                                data-key="about_cta_secondary_label" onchange="autoSave('about_cta_secondary_label', this.value)"
                                class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                placeholder="e.g., View services">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Secondary button link</label>
                            <input type="text" value="{{ get_setting('about_cta_secondary_link', route('services.index')) }}"
                                data-key="about_cta_secondary_link" onchange="autoSave('about_cta_secondary_link', this.value)"
                                class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                placeholder="URL or path, e.g., {{ route('services.index') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if($isTeam)
    <div class="space-y-4">
        <div class="space-y-3">
            <div>
                <label class="text-xs font-semibold text-gray-700">Hero title</label>
                <input type="text" value="{{ get_setting('team_hero_title', 'People-first clinicians. Coordinated, informed, and ready to help.') }}"
                    data-key="team_hero_title" onchange="autoSave('team_hero_title', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-700">Hero subtitle</label>
                <textarea rows="3" data-key="team_hero_subtitle"
                    onchange="autoSave('team_hero_subtitle', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all resize-none"
                    placeholder="Short paragraph for the hero section">{{ get_setting('team_hero_subtitle', 'Meet the multidisciplinary team that delivers continuous, connected care—combining psychology, homeopathy, and coordinated support.') }}</textarea>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-700">Leadership</p>
                        <p class="text-[11px] text-gray-500">Name, role, focus, bio</p>
                    </div>
                    <button type="button" onclick="pageEditor.addItem('team-leadership')"
                        class="inline-flex items-center gap-2 px-3 h-9 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100 text-xs font-semibold transition">
                        <span class="w-5 h-5 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[11px]">+</span>
                        Add leader
                    </button>
                </div>
                <div id="team-leadership" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($teamLeadershipArr as $idx => $item)
                        <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="{{ $idx }}">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold">{{ $idx + 1 }}</span>
                                    <div class="text-sm font-semibold text-gray-800">Leader</div>
                                </div>
                                <button type="button"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                                    onclick="pageEditor.removeItem('team-leadership', {{ $idx }})">×</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Name</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                        value="{{ $item['name'] ?? '' }}" data-field="name" oninput="pageEditor.sync('team-leadership')">
                                </div>
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Role</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                        value="{{ $item['role'] ?? '' }}" data-field="role" oninput="pageEditor.sync('team-leadership')">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Focus</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                        value="{{ $item['focus'] ?? '' }}" data-field="focus" oninput="pageEditor.sync('team-leadership')">
                                </div>
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Photo URL</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                        value="{{ $item['photo'] ?? '' }}" data-field="photo" oninput="pageEditor.sync('team-leadership')"
                                        placeholder="https://...">
                                </div>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Bio</label>
                                <textarea rows="2" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    data-field="bio" oninput="pageEditor.sync('team-leadership')">{{ $item['bio'] ?? '' }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-700">Care teams</p>
                        <p class="text-[11px] text-gray-500">Title, members, color classes</p>
                    </div>
                    <button type="button" onclick="pageEditor.addItem('team-care')"
                        class="inline-flex items-center gap-2 px-3 h-9 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100 text-xs font-semibold transition">
                        <span class="w-5 h-5 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[11px]">+</span>
                        Add care team
                    </button>
                </div>
                <div id="team-care" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($teamCareArr as $idx => $item)
                        @php
                            $members = isset($item['members']) && is_array($item['members']) ? implode("\n", $item['members']) : '';
                        @endphp
                        <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="{{ $idx }}">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold">{{ $idx + 1 }}</span>
                                    <div class="text-sm font-semibold text-gray-800">Care team</div>
                                </div>
                                <button type="button"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                                    onclick="pageEditor.removeItem('team-care', {{ $idx }})">×</button>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Title</label>
                                <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    value="{{ $item['title'] ?? '' }}" data-field="title" oninput="pageEditor.sync('team-care')">
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Members (one per line)</label>
                                <textarea rows="3" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    data-field="members" oninput="pageEditor.sync('team-care')">{{ $members }}</textarea>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Color classes</label>
                                <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    value="{{ $item['color'] ?? '' }}" data-field="color" oninput="pageEditor.sync('team-care')"
                                    placeholder="e.g., border-indigo-100 bg-indigo-50">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="text-xs font-semibold text-gray-700">CTA title</label>
                    <input type="text" value="{{ get_setting('team_cta_title', 'Book time with the right specialist') }}"
                        data-key="team_cta_title" onchange="autoSave('team_cta_title', this.value)"
                        class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-700">CTA subtitle</label>
                    <textarea rows="2" data-key="team_cta_subtitle"
                        onchange="autoSave('team_cta_subtitle', this.value)"
                        class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all resize-none"
                        placeholder="Supporting line under the CTA title">{{ get_setting('team_cta_subtitle', 'Choose from psychology, homeopathy, and care coordination services—all in one place.') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-2">
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Primary button label</label>
                            <input type="text" value="{{ get_setting('team_cta_primary_label', 'View services') }}"
                                data-key="team_cta_primary_label" onchange="autoSave('team_cta_primary_label', this.value)"
                                class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                                placeholder="e.g., View services">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Primary button link</label>
                            <input type="text" value="{{ get_setting('team_cta_primary_link', route('services.index')) }}"
                                data-key="team_cta_primary_link" onchange="autoSave('team_cta_primary_link', this.value)"
                                class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                                placeholder="URL or path, e.g., {{ route('services.index') }}">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Secondary button label</label>
                            <input type="text" value="{{ get_setting('team_cta_secondary_label', 'Create account') }}"
                                data-key="team_cta_secondary_label" onchange="autoSave('team_cta_secondary_label', this.value)"
                                class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                                placeholder="e.g., Create account">
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-700">Secondary button link</label>
                            <input type="text" value="{{ get_setting('team_cta_secondary_link', route('register')) }}"
                                data-key="team_cta_secondary_link" onchange="autoSave('team_cta_secondary_link', this.value)"
                                class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                                placeholder="URL or path, e.g., {{ route('register') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-center gap-2">
    <i class='bx bx-help-circle text-blue-500 text-lg'></i>
    <p class="text-xs text-blue-700 leading-relaxed">
        Use the add/remove buttons to manage items. Members accept one entry per line. Colors accept Tailwind classes like
        <span class="font-mono text-[11px] bg-white/60 px-1 rounded">border-blue-100 bg-blue-50</span>.
    </p>
</div>

@push('scripts')
<script>
if (!window.pageEditor) {
    window.pageEditor = {
        addItem(section) {
            const container = document.getElementById(section);
            if (!container) return;
            const idx = container.children.length;
            const template = this.templates[section] ? this.templates[section](idx) : null;
            if (template) {
                container.insertAdjacentHTML('beforeend', template);
                this.sync(section);
                this.toast('added', section);
            }
        },
        removeItem(section, idx) {
            const proceed = () => {
                const container = document.getElementById(section);
                if (!container) return;
                const el = container.querySelector(`[data-index="${idx}"]`);
                if (el) el.remove();
                this.sync(section);
                this.toast('removed', section);
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Remove this item?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, remove',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) proceed();
                });
            } else {
                if (window.confirm('Remove this item?')) {
                    proceed();
                }
            }
        },
        sync(section) {
            const container = document.getElementById(section);
            if (!container) return;
            const items = [];
            Array.from(container.children).forEach((el, i) => {
                el.setAttribute('data-index', i);
                const obj = {};
                el.querySelectorAll('[data-field]').forEach(input => {
                    const field = input.getAttribute('data-field');
                    if (field === 'members') {
                        const lines = input.value.split('\n').map(x => x.trim()).filter(Boolean);
                        obj[field] = lines;
                    } else {
                        obj[field] = input.value;
                    }
                });
                items.push(obj);
            });
            const keyMap = {
                'about-values': 'about_values',
                'about-timeline': 'about_timeline',
                'team-leadership': 'team_leadership',
                'team-care': 'team_care_teams'
            };
            const key = keyMap[section];
            if (key && typeof autoSave === 'function') {
                autoSave(key, JSON.stringify(items));
            }
        },
        templates: {
            'about-values': (idx) => `
                <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="${idx}">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-semibold">${idx + 1}</span>
                            <div class="text-sm font-semibold text-gray-800">Value</div>
                        </div>
                        <button type="button"
                            class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                            onclick="pageEditor.removeItem('about-values', ${idx})">×</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Title</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                data-field="title" oninput="pageEditor.sync('about-values')">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Icon (Boxicons)</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                data-field="icon" oninput="pageEditor.sync('about-values')">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-700">Description</label>
                        <textarea rows="2" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            data-field="description" oninput="pageEditor.sync('about-values')"></textarea>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-700">Accent classes</label>
                        <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            data-field="accent" oninput="pageEditor.sync('about-values')" placeholder="e.g., text-rose-600 bg-rose-50">
                    </div>
                </div>
            `,
            'about-timeline': (idx) => `
                <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="${idx}">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-semibold">${idx + 1}</span>
                            <div class="text-sm font-semibold text-gray-800">Milestone</div>
                        </div>
                        <button type="button"
                            class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                            onclick="pageEditor.removeItem('about-timeline', ${idx})">×</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Year</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                data-field="year" oninput="pageEditor.sync('about-timeline')">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Title</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                data-field="title" oninput="pageEditor.sync('about-timeline')">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-700">Description</label>
                        <textarea rows="2" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            data-field="description" oninput="pageEditor.sync('about-timeline')"></textarea>
                    </div>
                </div>
            `,
            'team-leadership': (idx) => `
                <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="${idx}">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold">${idx + 1}</span>
                            <div class="text-sm font-semibold text-gray-800">Leader</div>
                        </div>
                        <button type="button"
                            class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                            onclick="pageEditor.removeItem('team-leadership', ${idx})">×</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Name</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                data-field="name" oninput="pageEditor.sync('team-leadership')">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Role</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                data-field="role" oninput="pageEditor.sync('team-leadership')">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Focus</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                data-field="focus" oninput="pageEditor.sync('team-leadership')">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Photo URL</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                data-field="photo" oninput="pageEditor.sync('team-leadership')" placeholder="https://...">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-700">Bio</label>
                        <textarea rows="2" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            data-field="bio" oninput="pageEditor.sync('team-leadership')"></textarea>
                    </div>
                </div>
            `,
            'team-care': (idx) => `
                <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="${idx}">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold">${idx + 1}</span>
                            <div class="text-sm font-semibold text-gray-800">Care team</div>
                        </div>
                        <button type="button"
                            class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                            onclick="pageEditor.removeItem('team-care', ${idx})">×</button>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-700">Title</label>
                        <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            data-field="title" oninput="pageEditor.sync('team-care')">
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-700">Members (one per line)</label>
                        <textarea rows="3" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            data-field="members" oninput="pageEditor.sync('team-care')"></textarea>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-700">Color classes</label>
                        <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            data-field="color" oninput="pageEditor.sync('team-care')" placeholder="e.g., border-indigo-100 bg-indigo-50">
                    </div>
                </div>
            `,
        },
        toast(action, section) {
            const labels = {
                'about-values': 'Value',
                'about-timeline': 'Timeline item',
                'team-leadership': 'Leader',
                'team-care': 'Care team',
            };
            const label = labels[section] || 'Item';
            const title = `${label} ${action}`;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title,
                    showConfirmButton: false,
                    timer: 1800,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'swal-toast-fixed'
                    }
                });
                ensureToastStyle();
                return;
            }

            // Fallback inline toast if SweetAlert is unavailable
            const toast = document.createElement('div');
            toast.textContent = title;
            toast.style.position = 'fixed';
            toast.style.top = '16px';
            toast.style.right = '16px';
            toast.style.zIndex = '9999';
            toast.style.padding = '10px 14px';
            toast.style.borderRadius = '10px';
            toast.style.background = '#2563eb';
            toast.style.color = '#fff';
            toast.style.fontSize = '14px';
            toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.2s ease';
            document.body.appendChild(toast);
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
            });
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 250);
            }, 1600);
        }
    };

    // Ensure toast positioning mimics SweetAlert top-end placement
    function ensureToastStyle() {
        const id = 'swal-toast-fixed-style';
        if (document.getElementById(id)) return;
        const style = document.createElement('style');
        style.id = id;
        style.textContent = `
            .swal-toast-fixed {
                margin-top: 8px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            }
            .swal2-container.swal2-top-end {
                padding: 12px;
            }
        `;
        document.head.appendChild(style);
    }
}
</script>
@endpush
