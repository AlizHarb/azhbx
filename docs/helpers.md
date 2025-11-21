# Helpers

Helpers are the powerhouse of AzHbx. They allow you to execute PHP logic during the rendering process, keeping your templates clean and logic-less.

## Built-in Helpers

AzHbx comes with several standard helpers:

- `if`
- `unless`
- `each`
- `with`
- `partial` (aliased as `>`)
- `extend`
- `block`

## Custom Helpers

You can register your own helpers to perform text formatting, data manipulation, or complex logic.

### Registering a Helper

Use the `registerHelper` method on the `Engine` instance.

```php
$engine->registerHelper('uppercase', function ($data, $options, $engine) {
    // $data: The current data context
    // $options: Contains 'args' (arguments passed in template) and 'fn' (block content)
    // $engine: The engine instance

    $text = $options['args'][0] ?? '';

    // Resolve variable if it's a key in data
    if (is_string($text) && isset($data[$text])) {
        $text = $data[$text];
    }

    return strtoupper((string)$text);
});
```

### Using the Helper

```html
<p>{{ uppercase "hello world" }}</p>
<!-- Output: HELLO WORLD -->

<p>{{ uppercase title }}</p>
<!-- Output: (Value of title in uppercase) -->
```

## Block Helpers

Block helpers wrap a section of your template. They receive a `fn` callable in `$options` which renders the inner block.

### Example: `bold` Helper

```php
$engine->registerHelper('bold', function ($data, $options, $engine) {
    $content = ($options['fn'])($data);
    return "<strong>{$content}</strong>";
});
```

**Usage:**

```html
{{#bold}} This text is bold. {{/bold}}
```

**Output:**

```html
<strong> This text is bold. </strong>
```

### Advanced: Conditional Block Helper

You can create custom logic flow.

```php
$engine->registerHelper('isAdmin', function ($data, $options, $engine) {
    if (isset($data['user']['role']) && $data['user']['role'] === 'admin') {
        return ($options['fn'])($data);
    }
    return ''; // Or render an {{else}} block if you implement inverse logic
});
```
