# Basic Syntax

AzHbx templates are HTML files with embedded expressions. The syntax is designed to be clean, intuitive, and familiar to users of Handlebars or Mustache.

## Variables

The most common tag is the variable tag, denoted by double curly braces `{{ }}`.

```html
<h1>{{ title }}</h1>
```

If the data passed to the template is `['title' => 'Welcome']`, this will render:

```html
<h1>Welcome</h1>
```

### Nested Data (Dot Notation)

You can access nested properties of arrays or objects using dot notation.

**Data:**

```php
[
    'user' => [
        'profile' => [
            'name' => 'Alice'
        ]
    ]
]
```

**Template:**

```html
Welcome, {{ user.profile.name }}!
```

### The `this` Keyword

When iterating over an array of primitives (strings, numbers), or when you need to refer to the current context object itself, use `this`.

```html
{{#each tags}}
<span class="tag">{{ this }}</span>
{{/each}}
```

## Output Escaping

### Auto-Escaping (Safe)

By default, AzHbx **escapes all HTML entities** in variables. This is a critical security feature to prevent Cross-Site Scripting (XSS).

**Data:**

```php
['userInput' => '<img src=x onerror="alert(1)">']
```

**Template:**

```html
{{ userInput }}
```

**Output:**

```text
&lt;img src=x onerror=&quot;alert(1)&quot;&gt;
```

The dangerous HTML is rendered as plain text, preventing execution.

### Raw Output (Unsafe)

To output raw HTML, use the triple stash `{{{ }}}`.

**Data:**

```php
['trustedHtml' => '<strong>Bold Text</strong>']
```

**Template:**

```html
{{{ trustedHtml }}}
```

**Output:**

```html
<strong>Bold Text</strong>
```

> [!CAUTION]
> Only use raw output for trusted content. Never use it for user-submitted data. Using `{{{ }}}` with untrusted input can lead to XSS vulnerabilities.

## Whitespace Control

Template whitespace may sometimes appear in your rendered HTML. You can strip whitespace by adding a tilde `~` to the braces.

- `{{~` removes whitespace **before** the tag.
- `~}}` removes whitespace **after** the tag.

```html
<ul>
  {{~#each items}}
  <li>{{ this }}</li>
  {{~/each}}
</ul>
```

## Comments

Comments allow you to leave notes in your template code that are removed during rendering.

```html
{{! This comment will not show up in the HTML }}
<!-- This HTML comment WILL show up in the HTML -->
```
