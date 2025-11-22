<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx\Attributes;

use Attribute;

/**
 * Directive Attribute
 *
 * PHP 8.5 attribute for marking methods as template directives.
 * Directives are helpers that start with '@'.
 *
 * @package AlizHarb\AzHbx\Attributes
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Directive
{
    /**
     * Initialize directive attribute
     *
     * @param string $name Directive name (without @, it will be auto-prefixed if needed, or full name)
     */
    public function __construct(public string $name)
    {
    }
}
