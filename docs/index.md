# Introduction

Welcome to **AzHbx**, a next-generation PHP templating engine designed for modern applications. Inspired by the elegance of Handlebars and built for the power of PHP 8.5, AzHbx bridges the gap between simplicity and performance.

## Why AzHbx?

In the vast landscape of PHP templating engines, AzHbx stands out by focusing on three core pillars:

1.  **Developer Experience (DX)**: The syntax is intuitive, clean, and logic-less where it matters. It forces a separation of concerns that keeps your templates maintainable.
2.  **Modern PHP Features**: Built from the ground up for PHP 8.5+, leveraging strict types, property hooks (where available), and fibers for asynchronous rendering.
3.  **Extensibility**: A robust plugin system allowing for custom helpers, partial loaders, and module resolvers.

## Key Features

- **Logic-less Syntax**: Keep your views clean. Use helpers for logic.
- **Zero Dependencies**: A lightweight footprint that won't bloat your `vendor` directory.
- **Auto-Escaping**: Security by default. All output is escaped unless explicitly marked as raw.
- **Async Ready**: Built-in support for PHP Fibers, allowing you to pause rendering while waiting for database queries or API calls.
- **Modular Architecture**: Organize your views into themes and modules for large-scale applications.

## Quick Start

```php
use AlizHarb\AzHbx\Engine;

$engine = new Engine();

echo $engine->render('welcome', [
    'name' => 'Developer',
    'messages' => ['Welcome to the future', 'Happy coding!']
]);
```

**welcome.hbx**

```html
<h1>Hello, {{ name }}!</h1>
<ul>
  {{#each messages}}
  <li>{{ this }}</li>
  {{/each}}
</ul>
```

Ready to dive in? Head over to the [Installation](?page=installation.md) guide.
