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

