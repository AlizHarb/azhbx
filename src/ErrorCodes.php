<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * AzHbx Error Codes
 *
 * Standardized error codes for the AzHbx templating engine.
 */
final /**
 * ErrorCodes class.
 *
 * @package AlizHarb\AzHbx
 */
class ErrorCodes
{
    // Template Loading Errors
    public const TEMPLATE_NOT_FOUND = 'E001';
    public const PARTIAL_NOT_FOUND = 'E002';
    public const LAYOUT_NOT_FOUND = 'E003';

    // Compilation Errors
    public const SYNTAX_ERROR = 'E101';
    public const UNCLOSED_BLOCK = 'E102';
    public const UNEXPECTED_TOKEN = 'E103';

    // Runtime Errors
    public const HELPER_NOT_FOUND = 'E201';
    public const HELPER_EXECUTION_FAILED = 'E202';
    public const INVALID_CONTEXT = 'E203';

    // System Errors
    public const CACHE_WRITE_FAILED = 'E301';
    public const CONFIGURATION_ERROR = 'E302';

    /**
     * Get the description for an error code.
     */
    public static function getDescription(string $code): string
    {
        return match ($code) {
            self::TEMPLATE_NOT_FOUND => 'The requested template file could not be found.',
            self::PARTIAL_NOT_FOUND => 'The requested partial template could not be found.',
            self::LAYOUT_NOT_FOUND => 'The requested layout template could not be found.',
            self::SYNTAX_ERROR => 'The template contains invalid syntax.',
            self::UNCLOSED_BLOCK => 'A block was opened but not closed.',
            self::UNEXPECTED_TOKEN => 'An unexpected token was encountered during parsing.',
            self::HELPER_NOT_FOUND => 'The requested helper is not registered.',
            self::HELPER_EXECUTION_FAILED => 'The helper threw an exception during execution.',
            self::INVALID_CONTEXT => 'The context provided to the template is invalid.',
            self::CACHE_WRITE_FAILED => 'Failed to write the compiled template to the cache directory.',
            self::CONFIGURATION_ERROR => 'The engine configuration is invalid.',
            default => 'Unknown error.',
        };
    }
}
