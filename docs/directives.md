# Directives

Directives are special helpers that provide a concise syntax for common control structures and utility functions. They are identified by the `@` prefix.

## Syntax

Directives can be used in two ways:

### 1. Inline Directives

Used for simple output or single-line actions.

```html
<!-- Bare syntax -->
@csrf

<!-- Or inside braces -->
{{ @csrf }}
```

### 2. Block Directives

Used for wrapping content, similar to block helpers.

```html
@auth
<p>Welcome back, user!</p>
@endauth @guest
<p>Please login.</p>
@endguest
```

## Built-in Directives

AzHbx comes with several useful built-in directives.

### `@csrf`

Generates a hidden input field with the CSRF token. Useful for forms.

```html
<form method="POST">@csrf ...</form>
```

_Output:_ `<input type="hidden" name="_token" value="...">`

### `@method`

Generates a hidden input field to spoof HTTP methods (PUT, PATCH, DELETE).

```html
<form method="POST">@method "PUT" ...</form>
```

_Output:_ `<input type="hidden" name="_method" value="PUT">`

### `@auth` / `@guest`

Conditionally render content based on the user's authentication state.

```html
@auth
<az-UserProfile />
@endauth @guest
<a href="/login">Login</a>
@endguest
```

### `@env`

Check the application environment.

```html
@env "production"
<script src="analytics.js"></script>
@endenv
```

## Custom Directives

You can create your own directives using the `#[Directive]` attribute in a Plugin or by manually registering them.

### Using PHP Attributes (Recommended)

Create a plugin class and mark methods with `#[Directive]`.

```php
use AlizHarb\AzHbx\Attributes\Directive;
use AlizHarb\AzHbx\Contracts\PluginInterface;

class MyPlugin implements PluginInterface
{
    public function register(Engine $engine): void {}

    #[Directive('datetime')]
    public function formatDateTime(string $timestamp): string
    {
        return date('Y-m-d H:i', strtotime($timestamp));
    }
}
```

**Usage:**

```html
Current time: @datetime "now"
```

### Manual Registration

```php
$engine->getDirectiveRegistry()->register('upper', function ($text) {
    return strtoupper($text);
});
```
