<?php

namespace AlizHarb\AzHbx\Bridge\Laravel;

use AlizHarb\AzHbx\Engine;
use Illuminate\Support\ServiceProvider;

/**
 * AzHbxServiceProvider class.
 *
 * @package AlizHarb\AzHbx
 */
class AzHbxServiceProvider extends ServiceProvider
{
    /**
 * register method.
 *
 * @return mixed
 */
    public function register()
    {
        $this->app->singleton('azhbx', function ($app) {
            $config = $app['config']['view.azhbx'] ?? [];

            // Default config based on Laravel paths
            $defaults = [
                'views_path' => resource_path('views'),
                'cache_path' => storage_path('framework/views/azhbx'),
            ];

            return new Engine(array_merge($defaults, $config));
        });
    }

    /**
 * boot method.
 *
 * @return mixed
 */
    public function boot()
    {
        $this->app['view']->addExtension('hbx', 'azhbx', function () {
            return new AzHbxEngine($this->app['azhbx']);
        });
    }
}
