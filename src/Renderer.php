<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * Template Renderer
 *
 * Executes compiled PHP templates and returns the rendered output.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class Renderer
{
    /**
     * Engine instance for template context
     *
     * @var Engine
     */
    private Engine $engine;

    /**
     * Initialize the renderer
     *
     * @param Engine $engine Engine instance
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Render a compiled template
     *
     * @param string               $compiledPath Path to compiled PHP file
     * @param array<string, mixed> $data         Data to pass to template
     *
     * @return string Rendered HTML output
     */
    public function render(string $compiledPath, array $data): string
    {
        $renderFn = require $compiledPath;

        return $renderFn($data, $this->engine);
    }
}
