@props([
    'steps' => [],
    'autoStart' => false,
    'showProgress' => true
])

@php
    $tourId = 'tour_' . uniqid();
@endphp

<div 
    id="{{ $tourId }}"
    x-data="{ 
        currentStep: 0, 
        steps: @js($steps),
        showTour: {{ $autoStart ? 'true' : 'false' }},
        showProgress: {{ $showProgress ? 'true' : 'false' }}
    }"
    x-show="showTour"
    class="fixed inset-0 z-50 hidden"
    style="display: none;"
    x-transition
>
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showTour = false"></div>
    
    <!-- Tour Content -->
    <div 
        x-show="currentStep < steps.length"
        class="fixed bg-white rounded-xl shadow-2xl max-w-md p-6"
        :style="{
            top: steps[currentStep]?.top + 'px' || '50%',
            left: steps[currentStep]?.left + 'px' || '50%',
            transform: 'translate(-50%, -50%)'
        }"
        x-transition
    >
        <!-- Progress Bar -->
        <div x-show="showProgress" class="mb-4">
            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span>Step <span x-text="currentStep + 1"></span> of <span x-text="steps.length"></span></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div 
                    class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                    :style="{ width: ((currentStep + 1) / steps.length * 100) + '%' }"
                ></div>
            </div>
        </div>
        
        <!-- Step Content -->
        <template x-if="steps[currentStep]">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2" x-text="steps[currentStep].title"></h3>
                <p class="text-gray-600 mb-4" x-text="steps[currentStep].content"></p>
                
                <!-- Step Image/Element Highlight -->
                <div x-show="steps[currentStep].element" class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Look for the highlighted element</p>
                </div>
            </div>
        </template>
        
        <!-- Navigation -->
        <div class="flex justify-between gap-3">
            <button 
                x-show="currentStep > 0"
                @click="currentStep--"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
                Previous
            </button>
            
            <div class="flex-1"></div>
            
            <button 
                x-show="currentStep < steps.length - 1"
                @click="currentStep++"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
                Next
            </button>
            
            <button 
                x-show="currentStep === steps.length - 1"
                @click="showTour = false"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
            >
                Got it!
            </button>
            
            <button 
                @click="showTour = false"
                class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors"
            >
                Skip
            </button>
        </div>
    </div>
</div>

<script>
    // Tour initialization
    window.startTour = function(tourId) {
        const tour = document.getElementById(tourId);
        if (tour && window.Alpine) {
            const data = Alpine.$data(tour);
            if (data) {
                data.showTour = true;
                data.currentStep = 0;
            }
        }
    };
</script>

