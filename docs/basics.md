# Basic Usage

This guide covers the fundamental syntax of AzHbx.

## Variables

To output the value of a variable, wrap it in double curly braces `{{ }}`.

```html
<h1>{{ title }}</h1>
<p>Hello, {{ user.name }}!</p>
```

### Dot Notation

You can access nested array or object properties using dot notation.

```php
$data = [
    'user' => [
        'profile' => ['age' => 25]
    ]
];
```

```html
Age: {{ user.profile.age }}
```

## Raw Output

By default, AzHbx escapes all output to prevent XSS attacks. If you need to render raw HTML, use triple curly braces `{{{ }}}`.

```html
<!-- Escaped (Safe) -->
{{ html_content }}
<!-- Output: &lt;b&gt;Bold&lt;/b&gt; -->

<!-- Unescaped (Raw) -->
{{{ html_content }}}
<!-- Output: <b>Bold</b> -->
```

> [!WARNING]
> Be careful when using raw output with user-generated content, as it can expose your application to XSS vulnerabilities.

## Comments

You can add comments to your templates that will not be rendered in the final HTML.

```html
<!-- This is an HTML comment, it will be visible in the source -->
{{! This is an AzHbx comment, it will be removed completely }}
```

## Whitespace Control

AzHbx preserves whitespace by default. However, you can strip whitespace by adding a `~` character to the braces (if supported by the parser, currently standard Handlebars behavior is to preserve).

_Note: Explicit whitespace control syntax is planned for a future release._
