<?php

use AlizHarb\AzHbx\Bridge\Laravel\AzHbxEngine;
use AlizHarb\AzHbx\Bridge\Symfony\DependencyInjection\AzHbxExtension;
use AlizHarb\AzHbx\Engine;
use Symfony\Component\DependencyInjection\ContainerBuilder;

test('laravel engine renders template', function () {
    $engine = new Engine(['views_path' => __DIR__ . '/../views']);
    $laravelEngine = new AzHbxEngine($engine);

    // Mock template
    if (!is_dir(__DIR__ . '/../views/themes/default')) {
        mkdir(__DIR__ . '/../views/themes/default', 0777, true);
    }
    $path = __DIR__ . '/../views/themes/default/laravel.hbx';
    file_put_contents($path, 'Hello {{name}}');

    $output = $laravelEngine->get($path, ['name' => 'Laravel']);

    expect($output)->toBe('Hello Laravel');
});

test('symfony extension loads configuration', function () {
    $container = new ContainerBuilder();
    $extension = new AzHbxExtension();

    $extension->load([
        'azhbx' => [
            'views_path' => '/custom/views',
            'default_theme' => 'dark',
        ],
    ], $container);

    $definition = $container->getDefinition('azhbx.engine');
    $args = $definition->getArgument(0);

    expect($args['views_path'])->toBe('/custom/views');
    expect($args['default_theme'])->toBe('dark');
    expect($container->hasAlias(Engine::class))->toBeTrue();
});
