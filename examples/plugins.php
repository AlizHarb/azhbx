<?php

require __DIR__ . '/../vendor/autoload.php';

use AlizHarb\AzHbx\Engine;
use AlizHarb\AzHbx\Contracts\PluginInterface;
use AlizHarb\AzHbx\Attributes\Helper;

// 1. Define a Plugin using PHP 8.5 Attributes
class StringUtilsPlugin implements PluginInterface
{
    public function register(Engine $engine): void
    {
        // Manual registration is still possible
        // $engine->registerHelper('manual_helper', ...);
    }

    #[Helper('reverse')]
    public function reverseString(string $text): string
    {
        return strrev($text);
    }

    #[Helper('shout')]
    public function shoutString(string $text): string
    {
        return strtoupper($text) . '!!!';
    }
}

// 2. Initialize Engine
$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/../cache',
]);

// 3. Load Plugin
$engine->loadPlugin(new StringUtilsPlugin());

// 4. Render Template using Plugin Helpers
// We'll use a dynamic template for this example
$template = "
<h1>Plugin Demo</h1>
<p>Original: {{ message }}</p>
<p>Reverse: {{ reverse message }}</p>
<p>Shout: {{ shout message }}</p>
";

// Create a temporary file for the example
$tempFile = __DIR__ . '/views/plugin_demo.hbx';
file_put_contents($tempFile, $template);

echo $engine->render('plugin_demo', ['message' => 'Hello World']);

// Cleanup
unlink($tempFile);
