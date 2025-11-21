<?php

use AlizHarb\AzHbx\Engine;

test('it renders layout inheritance', function () {
    $engine = new Engine([
        'views_path' => __DIR__ . '/../examples/views',
        'cache_path' => __DIR__ . '/../cache',
    ]);

    // Create layout
    $layoutContent = 'Header - {{#block "content"}}Default{{/block}} - Footer';
    file_put_contents(__DIR__ . '/../examples/views/themes/default/layout.hbx', $layoutContent);

    // Create child template
    $childContent = '{{#extend "layout"}}{{#block "content"}}Child Content{{/block}}{{/extend}}';
    file_put_contents(__DIR__ . '/../examples/views/themes/default/child.hbx', $childContent);

    $result = $engine->render('child', []);
    expect($result)->toBe('Header - Child Content - Footer');
    
    // Clean up
    unlink(__DIR__ . '/../examples/views/themes/default/layout.hbx');
    unlink(__DIR__ . '/../examples/views/themes/default/child.hbx');
    array_map('unlink', glob(__DIR__ . '/../cache/*.php'));
});
