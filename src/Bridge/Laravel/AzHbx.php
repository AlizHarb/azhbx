<?php

namespace AlizHarb\AzHbx\Bridge\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * AzHbx class.
 *
 * @package AlizHarb\AzHbx
 */
class AzHbx extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'azhbx';
    }
}
