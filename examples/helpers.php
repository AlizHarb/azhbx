<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;

$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
]);

// Register Custom Helper
$engine->registerHelper('uppercase', function ($data, $options, $engine) {
    $text = $options['args'][0] ?? '';
    // Resolve if it's a variable name
    if (is_string($text) && isset($data[$text])) {
        $text = $data[$text];
    }
    return strtoupper((string)$text);
});

$engine->registerHelper('formatDate', function ($data, $options, $engine) {
    $date = $options['args'][0] ?? '';
    // Resolve date
    if (is_string($date) && isset($data[$date])) {
        $date = $data[$date];
    }
    
    $format = $options['args'][1] ?? 'Y-m-d';
    // Resolve format if needed (though usually string literal)
    if (is_string($format) && isset($data[$format])) {
        $format = $data[$format];
    }
    
    return date($format, strtotime((string)$date));
});

$viewPath = __DIR__ . '/views/themes/default/helpers.hbx';
if (!file_exists($viewPath)) {
    file_put_contents($viewPath, '
<h1>Helpers</h1>

<p>Original: {{ message }}</p>
<p>Uppercase: {{ uppercase message }}</p>

<p>Date: {{ date }}</p>
<p>Formatted: {{ formatDate date "F j, Y" }}</p>
');
}

$data = [
    'message' => 'hello world',
    'date' => '2023-10-25'
];

echo $engine->render('helpers', $data);
