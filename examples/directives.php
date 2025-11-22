<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

// Initialize Engine
$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/cache',
    'debug' => true,
]);

// Mock global csrf function for testing
if (!function_exists('csrf_field')) {
    function csrf_field()
    {
        return '<input type="hidden" name="_token" value="1234567890">';
    }
}

// Create theme directory
$themeDir = __DIR__ . '/views/themes/default';
if (!is_dir($themeDir)) {
    mkdir($themeDir, 0777, true);
}

// Create a view that uses directives
file_put_contents($themeDir . '/directive_demo.hbx', '
<h1>Directive Demo</h1>

<form method="POST">
    @csrf
    @method "PUT"
    
    <button type="submit">Submit</button>
</form>

@env "production"
    <p>This is production environment.</p>
@endenv

@env "local"
    <p>This is local environment.</p>
@endenv
');

// Set environment variable for testing
putenv('APP_ENV=local');

// Render
echo $engine->render('directive_demo');
