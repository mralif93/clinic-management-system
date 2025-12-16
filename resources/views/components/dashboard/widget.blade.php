@props([
    'title' => null,
    'collapsible' => false,
    'removable' => false,
    'draggable' => false,
    'widgetId' => null,
    'class' => ''
])

@php
    $widgetId = $widgetId ?? 'widget_' . uniqid();
@endphp

<div 
    class="bg-white border border-gray-200 rounded-lg shadow-sm {{ $class }}"
    data-widget-id="{{ $widgetId }}"
    @if($draggable) draggable="true" @endif
    x-data="{ collapsed: false }"
>
    @if($title)
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            
            <div class="flex items-center gap-2">
                @if($collapsible)
                    <button 
                        @click="collapsed = !collapsed"
                        class="p-1 text-gray-400 hover:text-gray-600 transition-colors"
                        aria-label="Toggle widget"
                    >
                        <i class='bx bx-chevron-up text-xl transition-transform' :class="{ 'rotate-180': collapsed }"></i>
                    </button>
                @endif
                
                @if($removable)
                    <button 
                        onclick="removeWidget('{{ $widgetId }}')"
                        class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                        aria-label="Remove widget"
                    >
                        <i class='bx bx-x text-xl'></i>
                    </button>
                @endif
            </div>
        </div>
    @endif
    
    <div class="p-6" x-show="!collapsed" x-transition>
        {{ $slot }}
    </div>
</div>

<script>
    function removeWidget(widgetId) {
        Swal.fire({
            title: 'Remove Widget?',
            text: 'Are you sure you want to remove this widget?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, remove'
        }).then((result) => {
            if (result.isConfirmed) {
                const widget = document.querySelector(`[data-widget-id="${widgetId}"]`);
                if (widget) {
                    widget.style.transition = 'opacity 0.3s';
                    widget.style.opacity = '0';
                    setTimeout(() => {
                        widget.remove();
                        // Save widget preferences
                        saveWidgetPreferences();
                    }, 300);
                }
            }
        });
    }
    
    function saveWidgetPreferences() {
        // Save widget visibility preferences
        const widgets = Array.from(document.querySelectorAll('[data-widget-id]'));
        const visibleWidgets = widgets
            .filter(w => w.style.display !== 'none')
            .map(w => w.dataset.widgetId);
        
        localStorage.setItem('dashboard_widgets', JSON.stringify(visibleWidgets));
    }
</script>

