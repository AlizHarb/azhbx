<?php

use AlizHarb\AzHbx\Engine;

test('it renders with themes', function () {
    $engine = new Engine([
        'views_path' => __DIR__ . '/../examples/views',
        'cache_path' => __DIR__ . '/../cache',
        'default_theme' => 'default',
    ]);

    // Create default theme template
    file_put_contents(__DIR__ . '/../examples/views/themes/default/theme_test.hbx', 'Default Theme');

    // Create dark theme template
    $darkThemeDir = __DIR__ . '/../examples/views/themes/dark';
    if (!is_dir($darkThemeDir)) {
        mkdir($darkThemeDir, 0755, true);
    }
    file_put_contents($darkThemeDir . '/theme_test.hbx', 'Dark Theme');

    // Test default
    expect($engine->render('theme_test'))->toBe('Default Theme');

    // Switch theme
    $engine->setTheme('dark');
    expect($engine->render('theme_test'))->toBe('Dark Theme');

    // Fallback test: template only in default
    file_put_contents(__DIR__ . '/../examples/views/themes/default/fallback.hbx', 'Fallback');
    expect($engine->render('fallback'))->toBe('Fallback');

    // Clean up
    unlink(__DIR__ . '/../examples/views/themes/default/theme_test.hbx');
    unlink(__DIR__ . '/../examples/views/themes/dark/theme_test.hbx');
    unlink(__DIR__ . '/../examples/views/themes/default/fallback.hbx');
    // Recursive cleanup for dark theme directory
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__ . '/../examples/views/themes/dark', RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    rmdir(__DIR__ . '/../examples/views/themes/dark');
    array_map('unlink', glob(__DIR__ . '/../cache/*.php'));
});
