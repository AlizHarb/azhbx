# Plugins & Extensions

AzHbx features a powerful plugin system that allows you to bundle helpers, configurations, and other logic into reusable packages. This system leverages **PHP 8.5 Attributes** for a modern, declarative developer experience.

## The Plugin Interface

All plugins must implement the `AlizHarb\AzHbx\Contracts\PluginInterface`.

```php
namespace AlizHarb\AzHbx\Contracts;

use AlizHarb\AzHbx\Engine;

interface PluginInterface
{
    public function register(Engine $engine): void;
}
```

## Creating a Plugin

You can register helpers manually within the `register` method, or use the `#[Helper]` attribute to automatically register methods as helpers.

### Using Attributes (Recommended)

```php
use AlizHarb\AzHbx\Contracts\PluginInterface;
use AlizHarb\AzHbx\Attributes\Helper;
use AlizHarb\AzHbx\Engine;

class StringUtilsPlugin implements PluginInterface
{
    public function register(Engine $engine): void
    {
        // Setup logic if needed
    }

    #[Helper('reverse')]
    public function reverse(string $text): string
    {
        return strrev($text);
    }

    #[Helper('uppercase')]
    public function uppercase(string $text): string
    {
        return strtoupper($text);
    }
}
```

### Manual Registration

```php
class LegacyPlugin implements PluginInterface
{
    public function register(Engine $engine): void
    {
        $engine->registerHelper('shout', function($text) {
            return strtoupper($text) . '!!!';
        });
    }
}
```

## Loading Plugins

To use a plugin, simply load it into the engine instance.

```php
$engine = new Engine();
$engine->loadPlugin(new StringUtilsPlugin());
```

Now you can use the helpers in your templates:

```html
<p>{{ reverse "hello" }}</p>
<!-- Output: olleh -->
```

## Best Practices

1.  **Namespace your plugins**: If you are distributing plugins, use a unique namespace to avoid class name collisions.
2.  **Prefix helpers**: Consider prefixing your helper names (e.g., `str_reverse`) to avoid conflicts with other plugins or built-in helpers.
3.  **Keep it stateless**: Helpers should ideally be pure functions. If you need state, manage it carefully within your plugin class.
