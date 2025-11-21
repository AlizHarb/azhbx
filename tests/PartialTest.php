<?php

use AlizHarb\AzHbx\Engine;

test('it renders partials', function () {
    $engine = new Engine([
        'views_path' => __DIR__ . '/../examples/views',
        'cache_path' => __DIR__ . '/../cache',
    ]);

    // Create partial
    $partialContent = 'Hello from partial, {{name}}!';
    file_put_contents(__DIR__ . '/../examples/views/partials/header.hbx', $partialContent);

    // Create main template
    $templateContent = 'Start {{ partial "header" }} End';
    file_put_contents(__DIR__ . '/../examples/views/themes/default/partial_test.hbx', $templateContent);

    $result = $engine->render('partial_test', ['name' => 'User']);
    expect($result)->toBe('Start Hello from partial, User! End');
    
    // Clean up
    unlink(__DIR__ . '/../examples/views/partials/header.hbx');
    unlink(__DIR__ . '/../examples/views/themes/default/partial_test.hbx');
});
