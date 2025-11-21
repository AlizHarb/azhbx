<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

use AlizHarb\AzHbx\Attributes\Helper;
use ReflectionClass;
use ReflectionMethod;

/**
 * Attribute Loader
 *
 * Scans plugin objects for methods with #[Helper] attributes
 * and automatically registers them with the engine.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class AttributeLoader
{
    /**
     * Initialize attribute loader
     *
     * @param Engine $engine Engine instance for helper registration
     */
    public function __construct(private Engine $engine)
    {
    }

    /**
     * Load helpers from object using #[Helper] attributes
     *
     * Scans all public methods of the object for #[Helper] attributes
     * and registers them with the engine.
     *
     * @param object $object Plugin or helper object to scan
     *
     * @return void
     */
    public function loadFromObject(object $object): void
    {
        $reflection = new ReflectionClass($object);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(Helper::class);
            foreach ($attributes as $attribute) {
                $helperAttr = $attribute->newInstance();
                $helperName = $helperAttr->name;

                $this->engine->registerHelper($helperName, function ($data, $options, $engine) use ($object, $method) {
                    // Forward call to the object method
                    // We expect the method to handle arguments directly or via options
                    // For simplicity in this advanced version, we'll pass raw args
                    // But to maintain compatibility with our helper signature:
                    
                    $args = $options['args'] ?? [];
                    return $method->invokeArgs($object, $args);
                });
            }
        }
    }
}
