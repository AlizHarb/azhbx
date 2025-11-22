<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
]);

// Create Module Template
$modulePath = __DIR__ . '/views/modules/blog/post.hbx';
if (!file_exists($modulePath)) {
    @mkdir(dirname($modulePath), 0755, true);
    file_put_contents($modulePath, '
<article>
    <h3>{{ title }}</h3>
    <p>{{ body }}</p>
</article>
');
}

$data = [
    'title' => 'Module System',
    'body' => 'Modules allow you to organize templates into namespaces.',
];

// Render using module syntax: module::template
echo $engine->render('blog::post', $data);
