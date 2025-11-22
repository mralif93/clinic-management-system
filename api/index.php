<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file is the entry point for Vercel serverless functions.
 * It bootstraps the Laravel application and handles all requests.
 */

use Illuminate\Http\Request;

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('LARAVEL_START', microtime(true));

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
if (!file_exists(__DIR__.'/../vendor/autoload.php')) {
    http_response_code(500);
    die('Error: vendor/autoload.php not found. Please run "composer install" during build.');
}

require __DIR__.'/../vendor/autoload.php';

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

try {
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    if (!$app) {
        throw new Exception('Failed to bootstrap Laravel application');
    }
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $response = $kernel->handle(
        $request = Request::capture()
    )->send();
    
    $kernel->terminate($request, $response);
} catch (Throwable $e) {
    // Log error to stderr for Vercel logs
    error_log('Laravel Error: ' . $e->getMessage());
    error_log('File: ' . $e->getFile() . ':' . $e->getLine());
    error_log('Trace: ' . $e->getTraceAsString());
    
    // Show error if APP_DEBUG is true
    if (env('APP_DEBUG', false)) {
        http_response_code(500);
        echo '<h1>500 Server Error</h1>';
        echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        http_response_code(500);
        echo '<h1>500 Internal Server Error</h1>';
        echo '<p>An error occurred. Please check the server logs.</p>';
    }
}

