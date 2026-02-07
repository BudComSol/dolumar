<?php
/**
 * Custom autoloader for Dolumar
 * Replaces composer autoloader
 */

spl_autoload_register(function ($class) {
    // List of possible base paths
    $basePaths = [
        __DIR__ . '/../src/',
        __DIR__ . '/../packages/dolumar-engine/src/',
        __DIR__ . '/../lib/',
    ];
    
    // Try namespace-based loading first (e.g., PHPMailer\PHPMailer\PHPMailer)
    $namespacedPath = str_replace('\\', '/', $class);
    foreach ($basePaths as $basePath) {
        $file = $basePath . $namespacedPath . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Try underscore-based class names (e.g., Neuron_Core_Database -> Neuron/Core/Database.php)
    $underscorePath = str_replace('_', '/', $class);
    foreach ($basePaths as $basePath) {
        $file = $basePath . $underscorePath . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Handle Auth namespace (OpenID) - special case
    if (strpos($class, 'Auth_') === 0 || strpos($class, 'Auth/') === 0) {
        $authPath = str_replace(['Auth_', 'Auth/'], ['Auth/', 'Auth/'], $class);
        $authPath = str_replace('_', '/', $authPath);
        $file = __DIR__ . '/../lib/openid/' . $authPath . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
