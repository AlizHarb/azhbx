<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
    'default_theme' => 'default'
]);

// Create Default Theme Template
$defaultPath = __DIR__ . '/views/themes/default/theme_demo.hbx';
if (!file_exists($defaultPath)) {
    file_put_contents($defaultPath, '<p>This is the <strong>DEFAULT</strong> theme.</p>');
}

// Create Dark Theme Template
$darkPath = __DIR__ . '/views/themes/dark/theme_demo.hbx';
if (!file_exists($darkPath)) {
    @mkdir(dirname($darkPath), 0755, true);
    file_put_contents($darkPath, '<p style="background: #333; color: #fff; padding: 10px;">This is the <strong>DARK</strong> theme.</p>');
}

echo "<h2>Default Theme</h2>";
echo $engine->render('theme_demo');

echo "<h2>Dark Theme</h2>";
$engine->setTheme('dark');
echo $engine->render('theme_demo');
