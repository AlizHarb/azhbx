<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx\Exceptions;

use Exception;

/**
 * Engine Exception
 *
 * Base exception class for all AzHbx template engine errors.
 * Thrown when templates are not found, compilation fails, or other engine errors occur.
 *
 * @package AlizHarb\AzHbx\Exceptions
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class EngineException extends Exception
{
}
