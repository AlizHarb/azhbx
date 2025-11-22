<?php

namespace AlizHarb\AzHbx\Console;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Application class.
 *
 * @package AlizHarb\AzHbx
 */
class Application extends BaseApplication
{
    /**
 * __construct method.
 *
 * @return mixed
 */
    public function __construct()
    {
        parent::__construct('AzHbx CLI', '1.0.0');

        $this->add(new RenderCommand());
    }
}
