<?php

use AlizHarb\AzHbx\Engine;

test('it renders raw output', function () {
    $engine = new Engine([
        'views_path' => __DIR__ . '/../examples/views',
        'cache_path' => __DIR__ . '/../cache',
    ]);

    $templateContent = 'Safe: {{html}} Raw: {{{html}}}';
    file_put_contents(__DIR__ . '/../examples/views/themes/default/raw_test.hbx', $templateContent);

    $html = '<b>Bold</b>';
    $result = $engine->render('raw_test', ['html' => $html]);
    
    expect($result)->toBe('Safe: &lt;b&gt;Bold&lt;/b&gt; Raw: <b>Bold</b>');
    
    unlink(__DIR__ . '/../examples/views/themes/default/raw_test.hbx');
    array_map('unlink', glob(__DIR__ . '/../cache/*.php'));
});
