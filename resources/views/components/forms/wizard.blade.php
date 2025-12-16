@props([
    'steps' => [],
    'currentStep' => 1,
    'onStepChange' => null,
    'class' => ''
])

@php
    $totalSteps = count($steps);
@endphp

<div class="wizard-container {{ $class }}" x-data="{ currentStep: {{ $currentStep }}, totalSteps: {{ $totalSteps }} }">
    <!-- Step Indicator -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            @foreach($steps as $index => $step)
                @php
                    $stepNumber = $index + 1;
                    $isActive = $stepNumber == $currentStep;
                    $isCompleted = $stepNumber < $currentStep;
                @endphp
                
                <div class="flex items-center flex-1">
                    <!-- Step Circle -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 transition-colors
                            {{ $isActive ? 'bg-blue-600 border-blue-600 text-white' : '' }}
                            {{ $isCompleted ? 'bg-green-600 border-green-600 text-white' : '' }}
                            {{ !$isActive && !$isCompleted ? 'bg-white border-gray-300 text-gray-400' : '' }}">
                            @if($isCompleted)
                                <i class='bx bx-check text-xl'></i>
                            @else
                                <span class="font-semibold">{{ $stepNumber }}</span>
                            @endif
                        </div>
                        <span class="mt-2 text-xs font-medium {{ $isActive ? 'text-blue-600' : ($isCompleted ? 'text-green-600' : 'text-gray-400') }}">
                            {{ $step['label'] ?? "Step {$stepNumber}" }}
                        </span>
                    </div>
                    
                    <!-- Connector Line -->
                    @if($stepNumber < $totalSteps)
                        <div class="flex-1 h-0.5 mx-2 {{ $isCompleted ? 'bg-green-600' : 'bg-gray-300' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Step Content -->
    <div class="wizard-content">
        @foreach($steps as $index => $step)
            @php $stepNumber = $index + 1; @endphp
            <div x-show="currentStep === {{ $stepNumber }}" x-transition class="wizard-step">
                @if(isset($step['content']))
                    {!! $step['content'] !!}
                @else
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $step['title'] ?? "Step {$stepNumber}" }}</h3>
                        <p class="text-gray-600">{{ $step['description'] ?? '' }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    
    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
        <button 
            type="button"
            x-show="currentStep > 1"
            @click="currentStep--"
            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
        >
            <i class='bx bx-chevron-left mr-2'></i>Previous
        </button>
        
        <div class="flex-1"></div>
        
        <button 
            type="button"
            x-show="currentStep < totalSteps"
            @click="currentStep++"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
            Next<i class='bx bx-chevron-right ml-2'></i>
        </button>
        
        <button 
            type="submit"
            x-show="currentStep === totalSteps"
            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
        >
            <i class='bx bx-check mr-2'></i>Submit
        </button>
    </div>
</div>

