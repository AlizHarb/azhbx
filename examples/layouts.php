<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
]);

// Create Layout
$layoutPath = __DIR__ . '/views/themes/default/layout.hbx';
if (!file_exists($layoutPath)) {
    file_put_contents($layoutPath, '
<!DOCTYPE html>
<html>
<head>
    <title>{{ title }}</title>
</head>
<body>
    <header>
        {{> header }}
    </header>
    <main>
        {{#block "content"}}{{/block}}
    </main>
    <footer>
        &copy; {{ year }} AzHbx
    </footer>
</body>
</html>
');
}

// Create Partial
$partialPath = __DIR__ . '/views/partials/header.hbx';
if (!file_exists($partialPath)) {
    @mkdir(dirname($partialPath), 0755, true);
    file_put_contents($partialPath, '
<h1>Site Header</h1>
<nav>
    <a href="/">Home</a> | <a href="/about">About</a>
</nav>
');
}

// Create Child Template
$childPath = __DIR__ . '/views/themes/default/page.hbx';
if (!file_exists($childPath)) {
    file_put_contents($childPath, '
{{#extend "layout"}}
    {{#block "content"}}
        <h2>{{ pageTitle }}</h2>
        <p>This is the content of the page.</p>
    {{/block}}
{{/extend}}
');
}

$data = [
    'title' => 'Layout Example',
    'year' => date('Y'),
    'pageTitle' => 'Welcome to Layouts',
];

echo $engine->render('page', $data);
