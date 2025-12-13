@php
    $defaultAboutValues = json_encode([], JSON_PRETTY_PRINT);
@endphp

@php
    $isAbout = !isset($mode) || $mode === 'about';
    $isTeam = !isset($mode) || $mode === 'team';
    $isPackages = !isset($mode) || $mode === 'packages';

    $aboutValuesArr = json_decode(get_setting('about_values', $defaultAboutValues), true) ?? [];
    $teamMembersArr = json_decode(get_setting('team_members', json_encode([], JSON_PRETTY_PRINT)), true) ?? [];
    $packagesArr = json_decode(get_setting('packages', json_encode([], JSON_PRETTY_PRINT)), true) ?? [];
@endphp

@if($isAbout)
    <div class="space-y-4">
        <!-- Hero Section -->
        <div class="space-y-3">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Hero Section</h3>
            <div>
                <label class="text-xs font-semibold text-gray-700">Hero Title</label>
                <input type="text" data-key="about_hero_title"
                    onchange="autoSave('about_hero_title', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    placeholder="Main title for the hero section" value="{{ get_setting('about_hero_title', '') }}">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-700">Hero Subtitle</label>
                <textarea rows="3" data-key="about_hero_subtitle"
                    onchange="autoSave('about_hero_subtitle', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                    placeholder="Subtitle/description for the hero section">{{ get_setting('about_hero_subtitle', '') }}</textarea>
            </div>
        </div>

        <!-- Who We Are Section -->
        <div class="space-y-3 border-t border-gray-200 pt-4 mt-4">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Who We Are</h3>
            <div>
                <label class="text-xs font-semibold text-gray-700">Short Story</label>
                <textarea rows="3" data-key="about_story_short"
                    onchange="autoSave('about_story_short', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                    placeholder="Short description about your organization">{{ get_setting('about_story_short', '') }}</textarea>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-700">Long Story</label>
                <textarea rows="5" data-key="about_story_long"
                    onchange="autoSave('about_story_long', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                    placeholder="Detailed description about your organization">{{ get_setting('about_story_long', '') }}</textarea>
            </div>
        </div>

        <!-- Vision Section -->
        <div class="space-y-3 border-t border-gray-200 pt-4 mt-4">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Our Vision</h3>
            <div>
                <label class="text-xs font-semibold text-gray-700">Vision Statement</label>
                <textarea rows="3" data-key="about_vision"
                    onchange="autoSave('about_vision', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                    placeholder="Your organization's vision">{{ get_setting('about_vision', '') }}</textarea>
            </div>
        </div>

        <!-- Mission Section -->
        <div class="space-y-3 border-t border-gray-200 pt-4 mt-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-800">Our Mission</h3>
                <button type="button" onclick="pageEditor.addItem('about-mission')"
                    class="inline-flex items-center gap-2 px-3 h-9 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100 text-xs font-semibold transition">
                    <span class="w-5 h-5 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[11px]">+</span>
                    Add mission item
                </button>
            </div>
            <div id="about-mission" class="space-y-3">
                @php
                    $aboutMissionItemsArr = json_decode(get_setting('about_mission_items', json_encode([])), true) ?? [];
                @endphp
                @foreach($aboutMissionItemsArr as $idx => $item)
                    <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow p-3" data-index="{{ $idx }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold text-gray-600">Mission Item {{ $idx + 1 }}</span>
                            <button type="button"
                                class="flex items-center justify-center w-7 h-7 rounded-full bg-red-50 text-red-600 border border-red-100 text-xs font-semibold"
                                onclick="pageEditor.removeItem('about-mission', {{ $idx }})">×</button>
                        </div>
                        <input type="text" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            value="{{ $item }}" data-field="text" oninput="pageEditor.sync('about-mission')"
                            placeholder="Enter mission item">
                    </div>
                @endforeach
            </div>
            </div>

        <!-- Values Section -->
        <div class="space-y-3 border-t border-gray-200 pt-4 mt-4">
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
            <div class="mt-4">
                <label class="text-xs font-semibold text-gray-700">Values Section Description</label>
                <textarea rows="2" data-key="about_values_description"
                    onchange="autoSave('about_values_description', this.value)"
                        class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                    placeholder="Description text shown above the values cards">{{ get_setting('about_values_description', '') }}</textarea>
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
                        <p class="text-xs font-semibold text-gray-700">Team Members</p>
                        <p class="text-[11px] text-gray-500">Name, title, photo, bio</p>
                    </div>
                    <button type="button" onclick="pageEditor.addItem('team-members')"
                        class="inline-flex items-center gap-2 px-3 h-9 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100 text-xs font-semibold transition">
                        <span class="w-5 h-5 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[11px]">+</span>
                        Add member
                    </button>
                </div>
                <div id="team-members" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($teamMembersArr as $idx => $item)
                        <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="{{ $idx }}">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold">{{ $idx + 1 }}</span>
                                    <div class="text-sm font-semibold text-gray-800">Member</div>
                                </div>
                                <button type="button"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                                    onclick="pageEditor.removeItem('team-members', {{ $idx }})">×</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Name</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                        value="{{ $item['name'] ?? '' }}" data-field="name" oninput="pageEditor.sync('team-members')">
                                </div>
                                <div>
                                    <label class="text-[11px] font-semibold text-gray-700">Title</label>
                                    <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                        value="{{ $item['title'] ?? '' }}" data-field="title" oninput="pageEditor.sync('team-members')">
                                </div>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700 mb-2 block">Photo</label>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <input type="file" accept="image/png,image/jpeg,image/jpg,image/webp" 
                                            class="hidden" 
                                            id="team-photo-{{ $idx }}"
                                            onchange="handleTeamPhotoUpload({{ $idx }}, this)">
                                        <label for="team-photo-{{ $idx }}" 
                                            class="flex-1 cursor-pointer inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition">
                                            <i class='bx bx-cloud-upload text-sm'></i>
                                            Upload Photo
                                        </label>
                                        @if(!empty($item['photo']))
                                            <button type="button" 
                                                onclick="clearTeamPhoto({{ $idx }})"
                                                class="px-3 py-2 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                                                <i class='bx bx-x'></i>
                                            </button>
                                        @endif
                            </div>
                                    <div class="relative">
                                        @if(!empty($item['photo']))
                                            <div class="flex items-center gap-2">
                                                <img src="{{ str_starts_with($item['photo'], 'data:') ? $item['photo'] : (str_starts_with($item['photo'], 'http') ? $item['photo'] : asset('storage/' . $item['photo'])) }}" 
                                                    alt="Preview" 
                                                    class="w-16 h-16 rounded-lg object-cover border border-gray-200"
                                                    id="team-photo-preview-{{ $idx }}"
                                                    onerror="this.style.display='none';">
                                                <div class="flex-1">
                                                    <p class="text-[10px] text-gray-500 mb-1">Photo URL</p>
                                                    <input type="text" 
                                                        class="w-full rounded-lg border border-gray-200 px-2 py-1.5 text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/20"
                                                        value="{{ $item['photo'] }}" 
                                                        data-field="photo" 
                                                        data-index="{{ $idx }}"
                                                        id="team-photo-input-{{ $idx }}"
                                                        oninput="pageEditor.sync('team-members'); updateTeamPhotoPreview({{ $idx }}, this.value)"
                                                        placeholder="Photo URL">
                </div>
            </div>
                                        @else
                                            <input type="text" 
                                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                                value="{{ $item['photo'] ?? '' }}" 
                                                data-field="photo" 
                                                data-index="{{ $idx }}"
                                                id="team-photo-input-{{ $idx }}"
                                                oninput="pageEditor.sync('team-members')"
                                                placeholder="Photo URL or upload image">
                                        @endif
                </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Bio (Short)</label>
                                <textarea rows="2" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    data-field="bio" oninput="pageEditor.sync('team-members')"
                                    placeholder="Short bio about the team member">{{ $item['bio'] ?? '' }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endif

@if($isPackages)
    <div class="space-y-4">
        <!-- Hero Section -->
        <div class="space-y-3">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Hero Section</h3>
            <div>
                <label class="text-xs font-semibold text-gray-700">Hero Title</label>
                <input type="text" data-key="packages_hero_title"
                    onchange="autoSave('packages_hero_title', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                    placeholder="Main title for the hero section" value="{{ get_setting('packages_hero_title', 'Special Packages') }}">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-700">Hero Subtitle</label>
                <textarea rows="3" data-key="packages_hero_subtitle"
                    onchange="autoSave('packages_hero_subtitle', this.value)"
                    class="auto-save-input w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all resize-none"
                    placeholder="Subtitle/description for the hero section">{{ get_setting('packages_hero_subtitle', 'Choose from our specially curated packages designed to meet your wellness needs.') }}</textarea>
            </div>
        </div>

        <!-- Packages Section -->
        <div class="space-y-2 border-t border-gray-200 pt-4 mt-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-700">Packages</p>
                    <p class="text-[11px] text-gray-500">Name, pricing, sessions, duration, image</p>
                </div>
                <button type="button" onclick="pageEditor.addItem('packages')"
                    class="inline-flex items-center gap-2 px-3 h-9 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100 hover:bg-indigo-100 text-xs font-semibold transition">
                    <span class="w-5 h-5 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[11px]">+</span>
                    Add Package
                </button>
            </div>
            <div id="packages" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($packagesArr as $idx => $item)
                    <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="{{ $idx }}">
                        <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold">{{ $idx + 1 }}</span>
                                <div class="text-sm font-semibold text-gray-800">Package</div>
                            </div>
                            <button type="button"
                                class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                                onclick="pageEditor.removeItem('packages', {{ $idx }})">×</button>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Package Name</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                value="{{ $item['name'] ?? '' }}" data-field="name" oninput="pageEditor.sync('packages')">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Original Price (RM)</label>
                                <input type="number" step="0.01" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    value="{{ $item['original_price'] ?? '' }}" data-field="original_price" oninput="pageEditor.sync('packages')" placeholder="500">
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Price (RM)</label>
                                <input type="number" step="0.01" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    value="{{ $item['price'] ?? '' }}" data-field="price" oninput="pageEditor.sync('packages')" placeholder="450">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Sessions</label>
                                <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    value="{{ $item['sessions'] ?? '' }}" data-field="sessions" oninput="pageEditor.sync('packages')" placeholder="2X SESSIONS">
                            </div>
                            <div>
                                <label class="text-[11px] font-semibold text-gray-700">Duration</label>
                                <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                    value="{{ $item['duration'] ?? '' }}" data-field="duration" oninput="pageEditor.sync('packages')" placeholder="1 HOUR PER SESSION">
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700 mb-2 block">Package Image</label>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <input type="file" accept="image/png,image/jpeg,image/jpg,image/webp" 
                                        class="hidden" 
                                        id="package-image-{{ $idx }}"
                                        onchange="handlePackageImageUpload({{ $idx }}, this)">
                                    <label for="package-image-{{ $idx }}" 
                                        class="flex-1 cursor-pointer inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition">
                                        <i class='bx bx-cloud-upload text-sm'></i>
                                        Upload Image
                                    </label>
                                    @if(!empty($item['image']))
                                        <button type="button" 
                                            onclick="clearPackageImage({{ $idx }})"
                                            class="px-3 py-2 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    @endif
                                </div>
                                <div class="relative">
                                    @if(!empty($item['image']))
                                        <div class="flex items-center gap-2">
                                            <img src="{{ str_starts_with($item['image'], 'data:') ? $item['image'] : (str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image'])) }}" 
                                                alt="Preview" 
                                                class="w-16 h-16 rounded-lg object-cover border border-gray-200"
                                                id="package-image-preview-{{ $idx }}"
                                                onerror="this.style.display='none';">
                                            <div class="flex-1">
                                                <p class="text-[10px] text-gray-500 mb-1">Image URL</p>
                                                <input type="text" 
                                                    class="w-full rounded-lg border border-gray-200 px-2 py-1.5 text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/20"
                                                    value="{{ $item['image'] }}" 
                                                    data-field="image" 
                                                    data-index="{{ $idx }}"
                                                    id="package-image-input-{{ $idx }}"
                                                    oninput="pageEditor.sync('packages'); updatePackageImagePreview({{ $idx }}, this.value)"
                                                    placeholder="Image URL">
                                            </div>
                                        </div>
                                    @else
                                        <input type="text" 
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                            value="{{ $item['image'] ?? '' }}" 
                                            data-field="image" 
                                            data-index="{{ $idx }}"
                                            id="package-image-input-{{ $idx }}"
                                            oninput="pageEditor.sync('packages')"
                                            placeholder="Image URL or upload image">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Description (Optional)</label>
                            <textarea rows="2" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                data-field="description" oninput="pageEditor.sync('packages')"
                                placeholder="Package description">{{ $item['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

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
            let items = [];
            
            if (section === 'about-mission') {
                // Mission items are simple strings
                Array.from(container.children).forEach((el, i) => {
                    el.setAttribute('data-index', i);
                    const input = el.querySelector('[data-field="text"]');
                    if (input && input.value.trim()) {
                        items.push(input.value.trim());
                    }
                });
            } else {
                // Other sections are objects
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
            }
            
            const keyMap = {
                'about-values': 'about_values',
                'about-mission': 'about_mission_items',
                'team-members': 'team_members',
                'packages': 'packages'
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
            'about-mission': (idx) => `
                <div class="relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow p-3" data-index="${idx}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-gray-600">Mission Item ${idx + 1}</span>
                        <button type="button"
                            class="flex items-center justify-center w-7 h-7 rounded-full bg-red-50 text-red-600 border border-red-100 text-xs font-semibold"
                            onclick="pageEditor.removeItem('about-mission', ${idx})">×</button>
                    </div>
                    <input type="text" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                        data-field="text" oninput="pageEditor.sync('about-mission')"
                        placeholder="Enter mission item">
                </div>
            `,
            'team-members': (idx) => `
                <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow p-4 space-y-3" data-index="${idx}">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-semibold">${idx + 1}</span>
                            <div class="text-sm font-semibold text-gray-800">Member</div>
                        </div>
                        <button type="button"
                            class="flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 border border-red-100 text-sm font-semibold shadow"
                            onclick="pageEditor.removeItem('team-members', ${idx})">×</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Name</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                data-field="name" oninput="pageEditor.sync('team-members')">
                        </div>
                        <div>
                            <label class="text-[11px] font-semibold text-gray-700">Title</label>
                            <input type="text" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                data-field="title" oninput="pageEditor.sync('team-members')">
                        </div>
                    </div>
                        <div>
                        <label class="text-[11px] font-semibold text-gray-700 mb-2 block">Photo</label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input type="file" accept="image/png,image/jpeg,image/jpg,image/webp" 
                                    class="hidden" 
                                    id="team-photo-${idx}"
                                    onchange="handleTeamPhotoUpload(${idx}, this)">
                                <label for="team-photo-${idx}" 
                                    class="flex-1 cursor-pointer inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition">
                                    <i class='bx bx-cloud-upload text-sm'></i>
                                    Upload Photo
                                </label>
                        </div>
                            <input type="text" 
                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                                data-field="photo" 
                                data-index="${idx}"
                                id="team-photo-input-${idx}"
                                oninput="pageEditor.sync('team-members')"
                                placeholder="Photo URL or upload image">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-gray-700">Bio (Short)</label>
                        <textarea rows="2" class="w-full mt-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                            data-field="bio" oninput="pageEditor.sync('team-members')" placeholder="Short bio about the team member"></textarea>
                    </div>
                </div>
            `,
        },
        toast(action, section) {
            const labels = {
                'about-values': 'Value',
                'team-members': 'Member',
                'packages': 'Package',
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

    // Handle team photo upload
    window.handleTeamPhotoUpload = function(idx, input) {
        const file = input.files[0];
        if (!file) return;

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'File too large',
                    text: 'Maximum file size is 2MB',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
            input.value = '';
            return;
        }

        // Validate file type
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid file type',
                    text: 'Please upload PNG, JPG, or WEBP image',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
            input.value = '';
            return;
        }

        // Convert to base64
        const reader = new FileReader();
        reader.onload = function(e) {
            const base64Data = e.target.result;
            const photoInput = document.getElementById(`team-photo-input-${idx}`);
            if (photoInput) {
                photoInput.value = base64Data;
                pageEditor.sync('team-members');
                
                // Update preview if exists
                updateTeamPhotoPreview(idx, base64Data);
            }
        };
        reader.readAsDataURL(file);
    };

    // Clear team photo
    window.clearTeamPhoto = function(idx) {
        const photoInput = document.getElementById(`team-photo-input-${idx}`);
        const fileInput = document.getElementById(`team-photo-${idx}`);
        const preview = document.getElementById(`team-photo-preview-${idx}`);
        
        if (photoInput) {
            photoInput.value = '';
            pageEditor.sync('team-members');
        }
        if (fileInput) {
            fileInput.value = '';
        }
        if (preview) {
            preview.style.display = 'none';
        }
    };

    // Update team photo preview
    window.updateTeamPhotoPreview = function(idx, url) {
        const preview = document.getElementById(`team-photo-preview-${idx}`);
        if (preview && url) {
            preview.src = url;
            preview.style.display = 'block';
            preview.onerror = function() {
                this.style.display = 'none';
            };
        }
    };

    // Handle package image upload
    window.handlePackageImageUpload = function(idx, input) {
        const file = input.files[0];
        if (!file) return;

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'File too large',
                    text: 'Maximum file size is 2MB',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
            input.value = '';
            return;
        }

        // Validate file type
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid file type',
                    text: 'Please upload PNG, JPG, or WEBP image',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
            input.value = '';
            return;
        }

        // Convert to base64
        const reader = new FileReader();
        reader.onload = function(e) {
            const base64Data = e.target.result;
            const imageInput = document.getElementById(`package-image-input-${idx}`);
            if (imageInput) {
                imageInput.value = base64Data;
                pageEditor.sync('packages');
                
                // Update preview if exists
                updatePackageImagePreview(idx, base64Data);
            }
        };
        reader.readAsDataURL(file);
    };

    // Clear package image
    window.clearPackageImage = function(idx) {
        const imageInput = document.getElementById(`package-image-input-${idx}`);
        const fileInput = document.getElementById(`package-image-${idx}`);
        const preview = document.getElementById(`package-image-preview-${idx}`);
        
        if (imageInput) {
            imageInput.value = '';
            pageEditor.sync('packages');
        }
        if (fileInput) {
            fileInput.value = '';
        }
        if (preview) {
            preview.style.display = 'none';
        }
    };

    // Update package image preview
    window.updatePackageImagePreview = function(idx, url) {
        const preview = document.getElementById(`package-image-preview-${idx}`);
        if (preview && url) {
            preview.src = url;
            preview.style.display = 'block';
            preview.onerror = function() {
                this.style.display = 'none';
            };
        }
    };
}
</script>
@endpush
