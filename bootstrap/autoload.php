<?php
/**
 * Custom autoloader for Dolumar
 * Replaces composer autoloader
 */

spl_autoload_register(function ($class) {
    // Convert namespace to path
    $class = str_replace('\\', '/', $class);
    
    // List of possible base paths
    $basePaths = [
        __DIR__ . '/../src/',
        __DIR__ . '/../packages/dolumar-engine/src/',
        __DIR__ . '/../lib/phpmailer/',
        __DIR__ . '/../lib/openid/',
        __DIR__ . '/../lib/airbrake/',
        __DIR__ . '/../lib/',
    ];
    
    foreach ($basePaths as $basePath) {
        $file = $basePath . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Handle Auth namespace (OpenID)
    if (strpos($class, 'Auth/') === 0) {
        $file = __DIR__ . '/../lib/openid/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
