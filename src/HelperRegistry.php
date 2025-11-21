<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

use Closure;

/**
 * Helper Registry
 *
 * Manages registration and retrieval of template helper functions.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class HelperRegistry
{
    /**
     * Registered helper functions
     *
     * @var array<string, callable>
     */
    private array $helpers = [];

    /**
     * Register a helper function
     *
     * @param string   $name   Helper name
     * @param callable $helper Helper function
     *
     * @return void
     */
    public function register(string $name, callable $helper): void
    {
        $this->helpers[$name] = $helper;
    }

    /**
     * Get a registered helper
     *
     * @param string $name Helper name
     *
     * @return callable|null Helper function or null if not found
     */
    public function get(string $name): ?callable
    {
        return $this->helpers[$name] ?? null;
    }

    /**
     * Check if a helper is registered
     *
     * @param string $name Helper name
     *
     * @return bool True if helper exists, false otherwise
     */
    public function has(string $name): bool
    {
        return isset($this->helpers[$name]);
    }

    /**
     * Get all registered helpers
     *
     * @return array<string, callable> All registered helpers
     */
    public function getAll(): array
    {
        return $this->helpers;
    }
}
