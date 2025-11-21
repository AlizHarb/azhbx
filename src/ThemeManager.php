<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

use AlizHarb\AzHbx\Exceptions\EngineException;

/**
 * Theme Manager
 *
 * Manages multiple themes with automatic fallback to default theme.
 * Provides theme validation and resolution.
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
     * Active theme name
     *
     * @var string
     */
    private string $activeTheme;

    /**
     * Initialize theme manager
     *
     * @param string $viewsPath    Base path for views
     * @param string $defaultTheme Default theme name
     */
    public function __construct(string $viewsPath, string $defaultTheme = 'default')
    {
        $this->viewsPath = $viewsPath;
        $this->setActiveTheme($defaultTheme);
    }

    /**
     * Get the active theme name
     *
     * @return string Active theme name
     */
    public function getActiveTheme(): string
    {
        return $this->activeTheme;
    }

    /**
     * Set the active theme name
     *
     * @param string $theme Theme name
     *
     * @return void
     * @throws \InvalidArgumentException If theme name is empty
     */
    public function setActiveTheme(string $theme): void
    {
        if (empty($theme)) {
            throw new \InvalidArgumentException("Theme name cannot be empty.");
        }
        $this->activeTheme = $theme;
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
