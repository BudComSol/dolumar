<?php
/**
 * Compatibility Autoloader Proxy
 * 
 * This file exists for compatibility with developers expecting
 * a root-level autoload.php (like Composer's vendor/autoload.php).
 * 
 * The actual autoloader is located at: bootstrap/autoload.php
 * 
 * IMPORTANT: Most entry points should use bootstrap/bootstrap.php
 * instead, which loads both the autoloader and application configuration.
 */

// For new code, use bootstrap/bootstrap.php instead:
// require_once __DIR__ . '/bootstrap/bootstrap.php';

// Load the custom autoloader
require_once __DIR__ . '/bootstrap/autoload.php';
