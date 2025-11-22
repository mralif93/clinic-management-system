<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file is the entry point for Vercel serverless functions.
 * It bootstraps the Laravel application and handles all requests.
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');

define('LARAVEL_START', microtime(true));

try {
    /*
    |--------------------------------------------------------------------------
    | Check If The Application Is Under Maintenance
    |--------------------------------------------------------------------------
    |
    | If the application is in maintenance / demo mode via the "down" command
    | we will load this file so that any pre-rendered content can be shown
    | instead of starting the framework, which could cause an exception.
    |
    */

    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        require $maintenance;
    }

    /*
    |--------------------------------------------------------------------------
    | Register The Auto Loader
    |--------------------------------------------------------------------------
    |
    | Composer provides a convenient, automatically generated class loader for
    | this application. We just need to utilize it! We'll simply require it
    | into the script here so we don't need to manually load our classes.
    |
    */

    // Check if vendor directory exists
    $vendorPath = __DIR__.'/../vendor/autoload.php';
    if (!file_exists($vendorPath)) {
        throw new Exception('vendor/autoload.php not found. Please ensure composer install runs during build.');
    }

    require $vendorPath;

    /*
    |--------------------------------------------------------------------------
    | Run The Application
    |--------------------------------------------------------------------------
    |
    | Once we have the application, we can handle the incoming request using
    | the application's HTTP kernel. Then, we will send the response back
    | to this client's browser, allowing them to enjoy our application.
    |
    */

    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    if (!$app) {
        throw new Exception('Failed to bootstrap Laravel application. bootstrap/app.php returned null.');
    }

    $kernel = $app->make(Kernel::class);

    $response = $kernel->handle(
        $request = Request::capture()
    )->send();

    $kernel->terminate($request, $response);

} catch (Throwable $e) {
    // Log to stderr (visible in Vercel function logs)
    error_log('=== Laravel Error ===');
    error_log('Message: ' . $e->getMessage());
    error_log('File: ' . $e->getFile() . ':' . $e->getLine());
    error_log('Trace: ' . $e->getTraceAsString());
    
    // Check if APP_DEBUG is enabled
    $appDebug = getenv('APP_DEBUG') === 'true' || getenv('APP_DEBUG') === '1';
    
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    
    if ($appDebug) {
        echo '<!DOCTYPE html><html><head><title>500 Server Error</title><style>body{font-family:Arial,sans-serif;padding:20px;background:#f5f5f5;}h1{color:#dc2626;}pre{background:#fff;padding:15px;border-radius:5px;overflow:auto;}</style></head><body>';
        echo '<h1>500 Internal Server Error</h1>';
        echo '<h2>Error Details:</h2>';
        echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '<h3>Stack Trace:</h3>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '</body></html>';
    } else {
        echo '<!DOCTYPE html><html><head><title>500 Server Error</title></head><body>';
        echo '<h1>500 Internal Server Error</h1>';
        echo '<p>An error occurred. Please check the server logs or enable APP_DEBUG=true for details.</p>';
        echo '</body></html>';
    }
    
    exit(1);
}

