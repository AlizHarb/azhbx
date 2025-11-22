<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

// Initialize Engine
$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/cache',
    'debug' => true,
]);

// Ensure cache directory exists
if (!is_dir(__DIR__ . '/cache')) {
    mkdir(__DIR__ . '/cache', 0777, true);
}

// Create component files if they don't exist
$componentsDir = __DIR__ . '/views/components';
if (!is_dir($componentsDir)) {
    mkdir($componentsDir, 0777, true);
}

// Alert Component
file_put_contents($componentsDir . '/alert.hbx', '
<div class="alert alert-{{ type }}">
    <div class="alert-icon">
        <az-Icon name=icon />
    </div>
    <div class="alert-content">
        {{{ slot }}}
    </div>
</div>
');

// Icon Component
file_put_contents($componentsDir . '/icon.hbx', '<i class="fas fa-{{ name }}"></i>');

// Create theme directory
$themeDir = __DIR__ . '/views/themes/default';
if (!is_dir($themeDir)) {
    mkdir($themeDir, 0777, true);
}

// Create a view that uses components
file_put_contents($themeDir . '/component_demo.hbx', '
<h1>Component Demo</h1>

<az-Alert type="success" icon="check">
    <strong>Success!</strong> Operation completed successfully.
</az-Alert>

<az-Alert type="danger" icon="times">
    <strong>Error!</strong> Something went wrong.
</az-Alert>
');

// Render
echo $engine->render('component_demo');
