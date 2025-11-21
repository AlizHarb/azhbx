<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx\Contracts;

use AlizHarb\AzHbx\Engine;

/**
 * Plugin Interface
 *
 * Contract for AzHbx plugins. Plugins can register custom helpers,
 * modify engine behavior, or add new functionality.
 *
 * @package AlizHarb\AzHbx\Contracts
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
interface PluginInterface
{
    /**
     * Register the plugin with the engine
     *
     * Called when the plugin is loaded. Use this method to register
     * helpers, modify configuration, or perform any setup.
     *
     * @param Engine $engine Engine instance
     *
     * @return void
     */
    public function register(Engine $engine): void;
}
