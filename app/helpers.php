<?php

if (!function_exists('get_setting')) {
    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('get_currency_symbol')) {
    /**
     * Get currency symbol, with fallback to currency code
     *
     * @return string
     */
    function get_currency_symbol()
    {
        $symbol = get_setting('currency_symbol');
        if ($symbol) {
            return $symbol;
        }
        
        // Fallback to currency code if symbol not set
        $currency = get_setting('currency', 'USD');
        
        // Map common currency codes to symbols
        $currencyMap = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'AUD' => 'A$',
            'CAD' => 'C$',
            'CHF' => 'CHF',
            'CNY' => '¥',
            'INR' => '₹',
            'SGD' => 'S$',
        ];
        
        return $currencyMap[$currency] ?? $currency;
    }
}

if (!function_exists('breadcrumb')) {
    /**
     * Generate breadcrumb items array
     *
     * @param array $items Array of ['label' => string, 'url' => string|null]
     * @return array
     */
    function breadcrumb(array $items = []): array
    {
        $breadcrumbs = [];
        
        // Add home/dashboard based on user role
        if (Auth::check()) {
            $role = Auth::user()->role;
            $dashboardRoute = match($role) {
                'admin' => route('admin.dashboard'),
                'doctor' => route('doctor.dashboard'),
                'staff' => route('staff.dashboard'),
                'patient' => route('patient.dashboard'),
                default => route('home')
            };
            
            $breadcrumbs[] = [
                'label' => 'Dashboard',
                'url' => $dashboardRoute
            ];
        } else {
            $breadcrumbs[] = [
                'label' => 'Home',
                'url' => route('home')
            ];
        }
        
        // Add custom items
        foreach ($items as $item) {
            if (is_string($item)) {
                $breadcrumbs[] = [
                    'label' => $item,
                    'url' => null
                ];
            } else {
                $breadcrumbs[] = $item;
            }
        }
        
        return $breadcrumbs;
    }
}

