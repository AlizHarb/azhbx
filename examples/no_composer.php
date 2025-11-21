<?php

// 1. Include the standalone autoloader
require __DIR__ . '/../src/autoload.php';

use AlizHarb\AzHbx\Engine;

// 2. Initialize Engine
$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
]);

// 3. Render
echo "<h1>Standalone Usage</h1>";
echo $engine->render('basic', ['name' => 'Standalone User']);
