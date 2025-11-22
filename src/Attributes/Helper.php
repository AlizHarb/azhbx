<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx\Attributes;

use Attribute;

/**
 * Helper Attribute
 *
 * PHP 8.5 attribute for marking methods as template helpers.
 * Used by AttributeLoader to automatically register helpers from plugin classes.
 *
 * @package AlizHarb\AzHbx\Attributes
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
#[Attribute(Attribute::TARGET_METHOD)]
/**
 * Helper class.
 *
 * @package AlizHarb\AzHbx
 */
class Helper
{
    /**
     * Initialize helper attribute
     *
     * @param string $name Helper name to register in the engine
     */
    public function __construct(public string $name)
    {
    }
}
