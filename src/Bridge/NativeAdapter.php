<?php

namespace AzHbx\Bridge;

use AlizHarb\AzHbx\Engine;

/**
 * NativeAdapter class.
 *
 * @package AlizHarb\AzHbx
 */
class NativeAdapter implements AdapterInterface
{
    protected Engine $engine;

    /**
 * __construct method.
 *
 * @return mixed
 */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
 * render method.
 *
 * @return mixed
 */
    public function render(string $template, array $data = []): string
    {
        return $this->engine->render($template, $data);
    }

    /**
 * share method.
 *
 * @return mixed
 */
    public function share(string $key, mixed $value): void
    {
        // Assuming AzHbx has a way to share globals, otherwise we might need to extend AzHbx
        // For now, we'll just pass it in render if possible, or store it locally
        // This is a placeholder implementation
    }
}
