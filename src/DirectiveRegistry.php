<?php

declare(strict_types=1);

namespace AlizHarb\AzHbx;

/**
 * Directive Registry
 *
 * Manages special directives (helpers starting with @).
 *
 * @package AlizHarb\AzHbx
 * @author  Ali Harb <harbzali@gmail.com>
 * @license MIT
 */
class DirectiveRegistry
{
    /**
     * Register default directives
     *
     * @param HelperRegistry $registry
     */
    public function registerDefaults(HelperRegistry $registry): void
    {
        // @csrf
        $registry->register('@csrf', function () {
            // In a real app, this would get the token from session/framework
            // For now, we'll output a placeholder or look for a global function
            if (function_exists('csrf_field')) {
                return csrf_field();
            }

            return '<input type="hidden" name="_token" value="CSRF_TOKEN_PLACEHOLDER">';
        });

        // @method
        $registry->register('@method', function ($context, $options) {
            $method = $options['args'][0] ?? 'POST';

            return '<input type="hidden" name="_method" value="' . htmlspecialchars($method) . '">';
        });

        // @auth / @guest (Mock implementation)
        $registry->register('@auth', function ($context, $options) {
            // Check if 'user' exists in context and is not null
            $isAuth = isset($context['user']) && $context['user'];
            if ($isAuth) {
                return $options['fn']($context);
            }

            return '';
        });

        $registry->register('@guest', function ($context, $options) {
            $isAuth = isset($context['user']) && $context['user'];
            if (!$isAuth) {
                return $options['fn']($context);
            }

            return '';
        });

        // @env
        $registry->register('@env', function ($context, $options) {
            $env = $options['args'][0] ?? 'production';
            $currentEnv = getenv('APP_ENV') ?: 'production';

            if ($currentEnv === $env) {
                return $options['fn']($context);
            }

            return '';
        });
    }
}
