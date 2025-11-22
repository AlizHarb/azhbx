<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

// Initialize with debug mode enabled
$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
    'debug' => true, // Enable Profiler and Hot Reload
]);

// Render a template (using the basic example template)
$html = $engine->render('basic', [
    'name' => 'Profiler Demo',
    'items' => range(1, 100), // Generate some data to process
]);

echo $html;

// Render the profiler toolbar
echo $engine->getProfiler()->renderToolbar();
