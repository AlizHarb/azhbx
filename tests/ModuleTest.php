<?php

use AlizHarb\AzHbx\Engine;

test('it renders module templates', function () {
    $engine = new Engine([
        'views_path' => __DIR__ . '/../examples/views',
        'cache_path' => __DIR__ . '/../cache',
    ]);

    // Create module structure
    $blogModuleDir = __DIR__ . '/../examples/views/modules/blog';
    if (!is_dir($blogModuleDir)) {
        mkdir($blogModuleDir, 0755, true);
    }
    file_put_contents($blogModuleDir . '/post.hbx', 'Blog Post');

    // Render module template
    expect($engine->render('blog::post'))->toBe('Blog Post');

    // Clean up
    unlink(__DIR__ . '/../examples/views/modules/blog/post.hbx');
    rmdir(__DIR__ . '/../examples/views/modules/blog');
    array_map('unlink', glob(__DIR__ . '/../cache/*.php'));
});
