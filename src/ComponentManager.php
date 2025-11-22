<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * Component Manager
 *
 * Manages the registration and loading of file-based components.
 * Components are essentially partials that can be used with the <x-Name> syntax.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 */
class ComponentManager
{
    /**
     * Path to components directory
     *
     * @var string
     */
    private string $componentsPath;

    /**
     * Registered components
     *
     * @var array<string, string> Map of component name to file path
     */
    private array $components = [];

    /**
     * Initialize Component Manager
     *
     * @param string $componentsPath Path to components directory
     */
    public function __construct(string $componentsPath)
    {
        $this->componentsPath = rtrim($componentsPath, '/');
    }

    /**
     * Register all components from the directory and modules
     *
     * Scans the components directory and registers all .hbx files.
     * Also scans modules for components.
     *
     * @param HelperRegistry $registry Helper registry to register components into
     */
    public function registerComponents(HelperRegistry $registry): void
    {
        // 1. Register global components
        $this->scanDirectory($this->componentsPath, $registry);

        // 2. Register module components
        // Modules are in dirname($componentsPath) . '/modules' usually, based on Engine config.
        // But we only have $componentsPath here which is views/components.
        // So modules are in views/modules.
        $modulesPath = dirname($this->componentsPath) . '/modules';

        if (is_dir($modulesPath)) {
            $modules = scandir($modulesPath);
            foreach ($modules as $module) {
                if ($module === '.' || $module === '..') {
                    continue;
                }

                $moduleCompPath = $modulesPath . '/' . $module . '/components';
                if (is_dir($moduleCompPath)) {
                    $this->scanDirectory($moduleCompPath, $registry, $module);
                }
            }
        }
    }

    /**
     * Scan directory and register components
     *
     * @param string $path
     * @param HelperRegistry $registry
     * @param string|null $modulePrefix
     */
    private function scanDirectory(string $path, HelperRegistry $registry, ?string $modulePrefix = null): void
    {
        if (!is_dir($path)) {
            return;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'hbx') {
                $relativePath = substr($file->getPathname(), strlen($path) + 1);
                $name = $this->formatComponentName($relativePath);

                if ($modulePrefix) {
                    $name = $modulePrefix . '::' . $name;
                }

                // Register as a helper
                $registry->register($name, function ($context, $options, $engine) use ($file) {
                    // Merge attributes (hash) into context
                    $attributes = $options['hash'] ?? [];

                    // Resolve attributes using BuiltInHelpers logic
                    foreach ($attributes as $key => $val) {
                        $attributes[$key] = BuiltInHelpers::resolveValue($context, $val);
                    }

                    $data = array_merge($context, $attributes);

                    // If it's a block component, render the slot
                    if (isset($options['fn'])) {
                        $slot = $options['fn']($context);
                        $data['slot'] = $slot;
                    }

                    return $engine->renderComponent($file->getPathname(), $data);
                });
            }
        }
    }

    /**
     * Format component name from file path
     *
     * e.g. "alert.hbx" -> "Alert"
     * e.g. "form/input.hbx" -> "Form.Input" or "x-form-input"?
     * Let's stick to PascalCase for components: "Alert", "Form.Input"
     *
     * @param string $relativePath
     * @return string
     */
    private function formatComponentName(string $relativePath): string
    {
        $name = substr($relativePath, 0, -4); // Remove .hbx
        $parts = explode(DIRECTORY_SEPARATOR, $name);
        $parts = array_map('ucfirst', $parts);

        return implode('.', $parts);
    }
}
