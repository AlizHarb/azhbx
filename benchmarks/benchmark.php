<?php

/**
 * Benchmark AzHbx against Twig.
 *
 * Run: php benchmarks/benchmark.php
 */

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;
use Twig\Environment as TwigEnvironment;
use Twig\Loader\ArrayLoader;

$iterations = 1000;

// Simple template
$template = "<h1>Hello, {{ name }}!</h1>";

// AzHbx setup
$viewsPath = __DIR__ . '/../examples/views/themes/default';
if (!is_dir($viewsPath)) {
    mkdir($viewsPath, 0755, true);
}
$viewPath = $viewsPath . '/benchmark.hbx';
file_put_contents($viewPath, $template);

$engine = new Engine([
    'views_path' => __DIR__ . '/../examples/views',
    'cache_path' => __DIR__ . '/../cache',
    'debug' => false,
]);

$azhbxStart = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $engine->render('benchmark', ['name' => 'World']);
}
$azhbxTime = microtime(true) - $azhbxStart;

// Twig setup
$loader = new ArrayLoader(['benchmark' => $template]);
$twig = new TwigEnvironment($loader);
$twigStart = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $twig->render('benchmark', ['name' => 'World']);
}
$twigTime = microtime(true) - $twigStart;

// Output results
echo "AzHbx time: {$azhbxTime}s\n";
echo "Twig time: {$twigTime}s\n";

// Determine faster
if ($azhbxTime < $twigTime) {
    echo "AzHbx is faster.\n";
} else {
    echo "Twig is faster.\n";
}
