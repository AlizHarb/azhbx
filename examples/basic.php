<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

// Initialize Engine
$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
]);

// Create the view file for this example if it doesn't exist
$viewPath = __DIR__ . '/views/themes/default/basic.hbx';
if (!file_exists($viewPath)) {
    @mkdir(dirname($viewPath), 0755, true);
    file_put_contents($viewPath, '
<h1>Hello, {{ name }}!</h1>
<p>Welcome to {{ library }}.</p>
<p>Current time: {{ time }}</p>
<p>Raw HTML: {{{ raw_html }}}</p>
');
}

// Data to pass to the view
$data = [
    'name' => 'Developer',
    'library' => 'AzHbx',
    'time' => date('Y-m-d H:i:s'),
    'raw_html' => '<strong>Bold Text</strong>'
];

// Render
echo $engine->render('basic', $data);
