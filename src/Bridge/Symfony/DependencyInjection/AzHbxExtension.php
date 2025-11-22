<?php

namespace AlizHarb\AzHbx\Bridge\Symfony\DependencyInjection;

use AlizHarb\AzHbx\Engine;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * AzHbxExtension class.
 *
 * @package AlizHarb\AzHbx
 */
class AzHbxExtension extends Extension
{
    /**
 * load method.
 *
 * @return mixed
 */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = new Definition(Engine::class);
        $definition->setArgument(0, [
            'views_path' => $config['views_path'] ?? '%kernel.project_dir%/templates',
            'cache_path' => $config['cache_path'] ?? '%kernel.cache_dir%/azhbx',
            'default_theme' => $config['default_theme'] ?? 'default',
        ]);
        $definition->setPublic(true);

        $container->setDefinition('azhbx.engine', $definition);
        $container->setAlias(Engine::class, 'azhbx.engine');
    }
}
