<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * Module Manager
 *
 * Resolves module-namespaced templates (e.g., 'blog::post').
 * Modules are organized in modules/{name}/views/ directory structure.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class ModuleManager
{
    /**
     * Base path for views directory
     *
     * @var string
     */
    private string $viewsPath;

    /**
     * Initialize module manager
     *
     * @param string $viewsPath Base path for views
     */
    public function __construct(string $viewsPath)
    {
        $this->viewsPath = $viewsPath;
    }

    /**
     * Resolve module template path
     *
     * Expects format: 'module::view'
     * Resolves to: views/modules/{module}/{view}.hbx
     *
     * @param string $template Module template identifier (e.g., 'blog::post')
     *
     * @return string Full path to module template file
     */
    public function resolve(string $template): string
    {
        // Format: module::view
        [$module, $view] = explode('::', $template, 2);

        // Structure: modules/{module}/{view}.hbx
        return $this->viewsPath . '/modules/' . $module . '/' . $view . '.hbx';
    }
}
