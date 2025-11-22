<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;
use eftec\bladeone\BladeOne;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

// Setup
$iterations = 10000;
$data = [
    'title' => 'Benchmark',
    'items' => array_fill(0, 100, ['name' => 'Item', 'price' => 10]),
    'user' => ['name' => 'Ali', 'admin' => true],
];

// AzHbx
$azhbx = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/cache/azhbx',
]);
// Pre-compile
$viewsPath = __DIR__ . '/views';
$themePath = $viewsPath . '/themes/default';

if (!is_dir($viewsPath)) {
    mkdir($viewsPath, 0777, true);
}
if (!is_dir($viewsPath . '/themes')) {
    mkdir($viewsPath . '/themes', 0777, true);
}
if (!is_dir($themePath)) {
    mkdir($themePath, 0777, true);
}

file_put_contents($themePath . '/test.hbx', '<h1>{{title}}</h1><ul>{{#each items as |item|}}<li>{{item.name}}: {{item.price}}</li>{{/each}}</ul>');

// Twig
$twigLoader = new ArrayLoader([
    'test' => '<h1>{{ title }}</h1><ul>{% for item in items %}<li>{{ item.name }}: {{ item.price }}</li>{% endfor %}</ul>',
]);
$twig = new Environment($twigLoader, [
    'cache' => __DIR__ . '/cache/twig',
    'auto_reload' => true,
]);

// BladeOne
if (!is_dir(__DIR__ . '/views')) {
    mkdir(__DIR__ . '/views');
}
if (!is_dir(__DIR__ . '/cache/blade')) {
    mkdir(__DIR__ . '/cache/blade', 0777, true);
}
file_put_contents(__DIR__ . '/views/test.blade.php', '<h1>{{$title}}</h1><ul>@foreach($items as $item)<li>{{$item["name"]}}: {{$item["price"]}}</li>@endforeach</ul>');
$blade = new BladeOne(__DIR__ . '/views', __DIR__ . '/cache/blade', BladeOne::MODE_AUTO);


echo "Running $iterations iterations...\n\n";

// Benchmark AzHbx
$start = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $azhbx->render('test', $data);
}
$azhbxTime = microtime(true) - $start;
echo "AzHbx: " . number_format($azhbxTime, 4) . "s\n";

// Benchmark Twig
$start = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $twig->render('test', $data);
}
$twigTime = microtime(true) - $start;
echo "Twig:  " . number_format($twigTime, 4) . "s\n";

// Benchmark BladeOne
$start = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $blade->run('test', $data);
}
$bladeTime = microtime(true) - $start;
echo "Blade: " . number_format($bladeTime, 4) . "s\n";
