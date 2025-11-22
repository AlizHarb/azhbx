# Plugins & Extensions

Plugins allow you to extend AzHbx's functionality in a modular way. You can bundle helpers, directives, and other logic into a reusable class.

## Creating a Plugin

A plugin must implement the `AlizHarb\AzHbx\Contracts\PluginInterface`.

```php
namespace App\Plugins;

use AlizHarb\AzHbx\Contracts\PluginInterface;
use AlizHarb\AzHbx\Engine;
use AlizHarb\AzHbx\Attributes\Helper;
use AlizHarb\AzHbx\Attributes\Directive;

class MyCustomPlugin implements PluginInterface
{
    /**
     * Register the plugin with the engine.
     * You can manually register helpers here, or let the AttributeLoader handle it.
     */
    public function register(Engine $engine): void
    {
        // Manual registration (optional)
        // $engine->registerHelper('foo', fn() => 'bar');
    }

    #[Helper('shout')]
    public function shout(string $text): string
    {
        return strtoupper($text) . '!';
    }

    #[Directive('alert')]
    public function alertDirective(array $context): string
    {
        return '<div class="alert">Alert!</div>';
    }
}
```

## Loading a Plugin

Load the plugin into the engine instance.

```php
$engine->loadPlugin(new App\Plugins\MyCustomPlugin());
```

## Best Practices

1.  **Group Related Logic**: Create separate plugins for different domains (e.g., `StringPlugin`, `DatePlugin`, `AuthPlugin`).
2.  **Use Attributes**: Use `#[Helper]` and `#[Directive]` attributes for cleaner code.
3.  **Keep it Stateless**: Helpers should ideally be pure functions. If you need state, pass it via the context.
