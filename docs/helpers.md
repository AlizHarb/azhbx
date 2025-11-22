# Helpers

Helpers are functions that can be used in your templates to perform logic or data manipulation. AzHbx comes with a set of built-in helpers and allows you to register your own.

## Built-in Helpers

### `if`

Conditionally render a block.

```html
{{#if isActive}} Active {{/if}}
```

### `unless`

Conditionally render a block if the value is falsy.

```html
{{#unless isLoggedIn}} Login {{/unless}}
```

### `each`

Iterate over an array or object.

```html
{{#each items}} {{ name }} {{/each}}
```

### `with`

Change the context to a specific property.

```html
{{#with user}} {{ name }} ({{ email }}) {{/with}}
```

### `partial` / `>`

Render a partial view.

```html
{{> header title="Home" }}
```

## Custom Helpers

You can register custom helpers using the `Engine` instance or via Plugins.

### Registering via Engine

```php
$engine->registerHelper('uppercase', function ($text) {
    return strtoupper($text);
});
```

**Usage:**

```html
{{ uppercase "hello" }}
<!-- HELLO -->
```

### Registering via Plugins (Recommended)

Use the `#[Helper]` attribute in a plugin class.

```php
use AlizHarb\AzHbx\Attributes\Helper;
use AlizHarb\AzHbx\Contracts\PluginInterface;

class StringPlugin implements PluginInterface
{
    public function register(Engine $engine): void {}

    #[Helper('reverse')]
    public function reverse(string $text): string
    {
        return strrev($text);
    }
}
```

## Helper Arguments

Helpers receive arguments in two ways:

1.  **Positional Arguments**: Passed in order.

    ```html
    {{ myHelper arg1 arg2 }}
    ```

    _Access:_ `$options['args'][0]`, `$options['args'][1]`

2.  **Named Arguments (Hash)**: Passed as key-value pairs.
    ```html
    {{ myHelper key="value" foo="bar" }}
    ```
    _Access:_ `$options['hash']['key']`, `$options['hash']['foo']`

### Helper Signature

```php
function ($context, $options, $engine) {
    // $context: Current data context
    // $options: Array containing 'args', 'hash', and 'fn' (for block helpers)
    // $engine: The Engine instance
}
```

## Block Helpers

Block helpers wrap a section of the template. They receive a `fn` callable in `$options`.

```php
$engine->registerHelper('bold', function ($context, $options) {
    return '<b>' . $options['fn']($context) . '</b>';
});
```

**Usage:**

```html
{{#bold}} This is bold {{/bold}}
```
