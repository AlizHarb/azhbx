<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * Partial Loader
 *
 * Resolves and loads partial templates from the partials directory.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class PartialLoader
{
    /**
     * Base path for views directory
     *
     * @var string
     */
    private string $viewsPath;

    /**
     * Initialize partial loader
     *
     * @param string $viewsPath Base path for views
     */
    public function __construct(string $viewsPath)
    {
        $this->viewsPath = $viewsPath;
    }

    /**
     * Resolve partial template path
     *
     * Looks for partials in views/partials/ directory.
     *
     * @param string $name      Partial name
     * @param string $extension File extension (default: .hbx)
     *
     * @return string|null Full path to partial file or null if not found
     */
    public function resolve(string $name, string $extension = '.hbx'): ?string
    {
        // Check global partials
        $path = $this->viewsPath . '/partials/' . $name . $extension;
        if (file_exists($path)) {
            return $path;
        }
        
        return null;
    }
}
