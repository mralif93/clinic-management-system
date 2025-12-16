@props([
    'placeholder' => 'Search...',
    'minLength' => 2,
    'debounce' => 300
])

<div class="relative" x-data="{ 
    query: '', 
    results: [], 
    showResults: false, 
    selectedIndex: -1,
    loading: false,
    debounceTimer: null
}" 
@click.away="showResults = false"
@keydown.escape="showResults = false">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class='bx bx-search text-gray-400'></i>
        </div>
        
        <input
            type="text"
            x-model="query"
            @input="
                clearTimeout(debounceTimer);
                if (query.length >= {{ $minLength }}) {
                    loading = true;
                    debounceTimer = setTimeout(() => {
                        fetch('{{ route('search.autocomplete') }}?q=' + encodeURIComponent(query))
                            .then(response => response.json())
                            .then(data => {
                                results = data.results || [];
                                showResults = results.length > 0;
                                loading = false;
                            })
                            .catch(() => {
                                loading = false;
                            });
                    }, {{ $debounce }});
                } else {
                    results = [];
                    showResults = false;
                }
            "
            @keydown.arrow-down.prevent="selectedIndex = Math.min(selectedIndex + 1, results.length - 1)"
            @keydown.arrow-up.prevent="selectedIndex = Math.max(selectedIndex - 1, -1)"
            @keydown.enter.prevent="
                if (selectedIndex >= 0 && results[selectedIndex]) {
                    window.location.href = results[selectedIndex].url;
                } else if (results.length > 0) {
                    window.location.href = results[0].url;
                }
            "
            placeholder="{{ $placeholder }}"
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            aria-label="Global search"
            aria-autocomplete="list"
            aria-expanded="false"
            :aria-expanded="showResults"
        >
        
        <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <i class='bx bx-loader-alt bx-spin text-gray-400'></i>
        </div>
    </div>
    
    <!-- Search Results Dropdown -->
    <div 
        x-show="showResults && results.length > 0"
        x-transition
        class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-96 overflow-y-auto"
        role="listbox"
    >
        <template x-for="(result, index) in results" :key="index">
            <a
                :href="result.url"
                @click="showResults = false"
                :class="{
                    'bg-blue-50': selectedIndex === index,
                    'bg-white': selectedIndex !== index
                }"
                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0"
                role="option"
                :aria-selected="selectedIndex === index"
            >
                <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i :class="result.icon + ' text-gray-600 text-xl'"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate" x-text="result.title"></p>
                    <p class="text-xs text-gray-500 truncate" x-text="result.subtitle"></p>
                </div>
                <i class='bx bx-chevron-right text-gray-400'></i>
            </a>
        </template>
    </div>
    
    <!-- No Results -->
    <div 
        x-show="showResults && results.length === 0 && query.length >= {{ $minLength }} && !loading"
        x-transition
        class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg p-4 text-center"
    >
        <i class='bx bx-search text-4xl text-gray-300 mb-2'></i>
        <p class="text-sm text-gray-500">No results found</p>
    </div>
</div>

