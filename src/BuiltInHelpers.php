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
     * Registers control structures (if, unless, each, with),
     * partials (partial, >), and layout helpers (extend, block).
     *
     * @param Engine $engine Engine instance to register helpers with
     *
     * @return void
     */
    public static function register(Engine $engine): void
    {
        $engine->registerHelper('if', function ($data, $options, $engine) {
            // Check first argument
            $condition = $options['args'][0] ?? null;
            
            // If condition is a string, it might be a key in data
            // In a real engine, we'd resolve this in the compiler or here
            // For now, let's assume if it's a string key in data, use that value
            $val = $condition;
            if (is_string($condition) && isset($data[$condition])) {
                $val = $data[$condition];
            }
            
            if (!empty($val)) {
                return ($options['fn'])($data);
            }
            return '';
        });

        $engine->registerHelper('each', function ($data, $options, $engine) {
            $buffer = '';
            // Check first argument for the array to iterate
            $key = $options['args'][0] ?? null;
            $iterable = $data;
            
            if ($key && isset($data[$key])) {
                $iterable = $data[$key];
            }

            if (is_array($iterable)) {
                foreach ($iterable as $item) {
                    $buffer .= ($options['fn'])($item);
                }
            }
            return $buffer;
        });

        $partialFn = function ($data, $options, $engine) {
            $name = $options['args'][0] ?? null;
            if (!$name) {
                return '';
            }
            
            $path = $engine->getPartialLoader()->resolve($name);
            if (!$path) {
                return "<!-- Partial '{$name}' not found -->";
            }
            
            return $engine->renderFile($path, $data);
        };

        $engine->registerHelper('partial', $partialFn);
        $engine->registerHelper('>', $partialFn);

        $engine->registerHelper('extend', function ($data, $options, $engine) {
            $layoutName = $options['args'][0] ?? null;
            if (!$layoutName) {
                return '';
            }

            // Capture blocks from inner content
            $engine->startCapture();
            ($options['fn'])($data); // This runs the block helpers which register themselves
            $engine->endCapture();

            // Render layout
            $result = $engine->render($layoutName, $data);
            return $result;
        });

        $engine->registerHelper('block', function ($data, $options, $engine) {
            $name = $options['args'][0] ?? null;
            if (!$name) {
                return '';
            }

            if ($engine->isCapturing()) {
                // We are inside {{#extend}}, so we capture the content
                $content = ($options['fn'])($data);
                $engine->setBlock($name, $content);
                return ''; // Return nothing during capture
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
}
