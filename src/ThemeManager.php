<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

use AlizHarb\AzHbx\Exceptions\EngineException;

/**
 * Theme Manager
 *
 * Manages multiple themes with automatic fallback to default theme.
 * Uses PHP 8.5 Property Hooks for theme validation.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class ThemeManager
{
    /**
     * Base path for views directory
     *
     * @var string
     */
    private string $viewsPath;

    /**
     * Active theme name with validation
     *
     * Uses PHP 8.5 Property Hooks to validate theme name on set.
     *
     * @var string
     */
    public string $activeTheme {
        get => $this->activeTheme;
        set {
            if (empty($value)) {
                throw new \InvalidArgumentException("Theme name cannot be empty.");
            }
            $this->activeTheme = $value;
        }
    }

    /**
     * Initialize theme manager
     *
     * @param string $viewsPath    Base path for views
     * @param string $defaultTheme Default theme name
     */
    public function __construct(string $viewsPath, string $defaultTheme = 'default')
    {
        $this->viewsPath = $viewsPath;
        $this->activeTheme = $defaultTheme;
    }

    /**
     * Resolve template path with theme fallback
     *
     * Checks active theme first, then falls back to default theme.
     *
     * @param string $template Template name (without extension)
     *
     * @return string Full path to template file
     * @throws EngineException If template not found in any theme
     */
    public function resolve(string $template): string
    {
        // Check active theme
        $path = $this->viewsPath . '/themes/' . $this->activeTheme . '/' . $template . '.hbx';
        if (file_exists($path)) {
            return $path;
        }

        // Fallback to default theme if active is not default
        if ($this->activeTheme !== 'default') {
            $path = $this->viewsPath . '/themes/default/' . $template . '.hbx';
            if (file_exists($path)) {
                return $path;
            }
        }

        throw new EngineException("Template '{$template}' not found in theme '{$this->activeTheme}' or default.");
    }
}
