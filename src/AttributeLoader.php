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
            // Handle #[Helper]
            $helperAttributes = $method->getAttributes(Helper::class);
            foreach ($helperAttributes as $attribute) {
                $attrInst = $attribute->newInstance();
                $this->engine->registerHelper($attrInst->name, $this->createCallback($object, $method));
            }

            // Handle #[Directive]
            $directiveAttributes = $method->getAttributes(Attributes\Directive::class);
            foreach ($directiveAttributes as $attribute) {
                $attrInst = $attribute->newInstance();
                $name = $attrInst->name;
                if (!str_starts_with($name, '@')) {
                    $name = '@' . $name;
                }
                $this->engine->registerHelper($name, $this->createCallback($object, $method));
            }
        }
    }

    /**
     * Create a callback for the helper/directive
     *
     * @param object $object
     * @param ReflectionMethod $method
     * @return \Closure
     */
    private function createCallback(object $object, ReflectionMethod $method): \Closure
    {
        return function ($data, $options, $engine) use ($object, $method) {
            // We pass the context ($data) and options to the method if it requests them
            // Or we can just pass what we have.
            // Let's pass standard arguments: $context, $options
            return $method->invoke($object, $data, $options);
        };
    }
}
