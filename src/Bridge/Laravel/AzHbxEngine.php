<?php

namespace AlizHarb\AzHbx\Bridge\Laravel;

use AlizHarb\AzHbx\Engine;
use Illuminate\Contracts\View\Engine as EngineInterface;

/**
 * AzHbxEngine class.
 *
 * @package AlizHarb\AzHbx
 */
class AzHbxEngine implements EngineInterface
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
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array   $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        return $this->engine->renderFile($path, $data);
    }
}
