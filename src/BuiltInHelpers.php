<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * Built-in Template Helpers
 *
 * Provides core template helpers for control structures, partials, and layouts.
 * Includes: if, unless, each, with, partial (>), extend, block.
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 * @link    https://github.com/AlizHarb/azhbx
 */
class BuiltInHelpers
{
    /**
     * Register all built-in helpers with the engine
     *
     * @param Engine $engine Engine instance to register helpers with
     *
     * @return void
     */
    public static function register(Engine $engine): void
    {
        self::registerControlHelpers($engine);
        self::registerLayoutHelpers($engine);
    }

    /**
     * Register control structure helpers (if, unless, each)
     *
     * @param Engine $engine
     * @return void
     */
    private static function registerControlHelpers(Engine $engine): void
    {
        $engine->registerHelper('if', function ($data, $options, $engine) {
            $condition = $options['args'][0] ?? null;
            $val = self::resolveValue($data, $condition);

            if (!empty($val)) {
                return ($options['fn'])($data);
            }

            return '';
        });

        $engine->registerHelper('unless', function ($data, $options, $engine) {
            $condition = $options['args'][0] ?? null;
            $val = self::resolveValue($data, $condition);

            if (empty($val)) {
                return ($options['fn'])($data);
            }

            return '';
        });

        $engine->registerHelper('each', function ($data, $options, $engine) {
            $buffer = '';
            $args = $options['args'] ?? [];
            $key = $args[0] ?? null;

            // Resolve iterable
            $iterable = $data;
            if ($key && isset($data[$key])) {
                $iterable = $data[$key];
            } elseif ($key) {
                // Try to resolve path if not directly in data
                $resolved = self::resolveValue($data, $key);
                if ($resolved !== null) {
                    $iterable = $resolved;
                }
            }

            // Check for 'as |alias|' syntax
            $alias = null;
            if (isset($args[1], $args[2]) && $args[1] === 'as' && str_starts_with($args[2], '|') && str_ends_with($args[2], '|')) {
                $alias = substr($args[2], 1, -1);
            }

            if (is_array($iterable)) {
                foreach ($iterable as $item) {
                    if ($alias) {
                        $context = is_array($data) ? $data : [];
                        $context[$alias] = $item;
                        $buffer .= ($options['fn'])($context);
                    } else {
                        $buffer .= ($options['fn'])($item);
                    }
                }
            }

            return $buffer;
        });
    }

    /**
     * Register layout and partial helpers (partial, extend, block)
     *
     * @param Engine $engine
     * @return void
     */
    private static function registerLayoutHelpers(Engine $engine): void
    {
        $partialFn = function ($data, $options, $engine) {
            $name = $options['args'][0] ?? null;
            if (!$name) {
                return '';
            }

            // Strip quotes from name if present
            if ((str_starts_with($name, '"') && str_ends_with($name, '"')) ||
                (str_starts_with($name, "'") && str_ends_with($name, "'"))) {
                $name = substr($name, 1, -1);
            }

            // Merge named arguments from hash
            $context = $data;
            $hash = $options['hash'] ?? [];

            foreach ($hash as $key => $valPath) {
                $val = self::resolveValue($data, $valPath);
                $context[$key] = $val;
            }

            try {
                return $engine->render($name, $context);
            } catch (\Exception $e) {
                $path = $engine->getPartialLoader()->resolve($name);
                if (!$path) {
                    return "<!-- Partial '{$name}' not found -->";
                }

                return $engine->renderFile($path, $context);
            }
        };

        $engine->registerHelper('partial', $partialFn);
        $engine->registerHelper('>', $partialFn);

        $engine->registerHelper('extend', function ($data, $options, $engine) {
            $layoutName = $options['args'][0] ?? null;
            if (!$layoutName) {
                return '';
            }

            // Strip quotes
            if ((str_starts_with($layoutName, '"') && str_ends_with($layoutName, '"')) ||
                (str_starts_with($layoutName, "'") && str_ends_with($layoutName, "'"))) {
                $layoutName = substr($layoutName, 1, -1);
            }

            // Capture blocks from inner content
            $engine->startCapture();
            ($options['fn'])($data);
            $engine->endCapture();

            // Render layout
            return $engine->render($layoutName, $data);
        });

        $engine->registerHelper('block', function ($data, $options, $engine) {
            $name = $options['args'][0] ?? null;
            if (!$name) {
                return '';
            }

            // Strip quotes
            if ((str_starts_with($name, '"') && str_ends_with($name, '"')) ||
                (str_starts_with($name, "'") && str_ends_with($name, "'"))) {
                $name = substr($name, 1, -1);
            }

            if ($engine->isCapturing()) {
                // We are inside {{#extend}}, so we capture the content
                $content = ($options['fn'])($data);
                $engine->setBlock($name, $content);

                return '';
            } else {
                // We are inside the layout, so we output the block content
                $content = $engine->getBlock($name);
                if ($content !== null) {
                    return $content;
                }

                // Default content
                return ($options['fn'])($data);
            }
        });
    }

    /**
     * Resolve a value from data using dot notation
     *
     * @param array|object $data Context data
     * @param mixed        $path Path to resolve (string or other)
     *
     * @return mixed Resolved value or null if not found
     */
    public static function resolveValue(mixed $data, mixed $path): mixed
    {
        if (!is_string($path)) {
            return $path;
        }

        // Check for quoted string literal
        if ((str_starts_with($path, '"') && str_ends_with($path, '"')) ||
            (str_starts_with($path, "'") && str_ends_with($path, "'"))) {
            return substr($path, 1, -1);
        }

        // Direct access check
        if (is_array($data) && isset($data[$path])) {
            return $data[$path];
        }

        // Dot notation resolution
        $val = $data;
        $found = true;
        foreach (explode('.', $path) as $part) {
            if (is_array($val) && isset($val[$part])) {
                $val = $val[$part];
            } elseif (is_object($val) && isset($val->$part)) {
                $val = $val->$part;
            } else {
                $found = false;

                break;
            }
        }

        if ($found) {
            return $val;
        }

        // If not found, return null (treat as falsy/undefined)
        return null;
    }
}
