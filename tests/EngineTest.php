<?php

use AlizHarb\AzHbx\Engine;

test('it renders a simple template', function () {
    $engine = new Engine([
        'views_path' => __DIR__ . '/../examples/views',
        'cache_path' => __DIR__ . '/../cache',
    ]);

    // Create a temporary template file
    $templateContent = 'Hello, {{ name }}!';
    file_put_contents(__DIR__ . '/../examples/views/themes/default/hello.hbx', $templateContent);

    $result = $engine->render('hello', ['name' => 'World']);

    expect($result)->toBe('Hello, World!');

    // Clean up
    unlink(__DIR__ . '/../examples/views/themes/default/hello.hbx');
});
