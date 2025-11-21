<div align="center">

# 🚀 AzHbx

**Next-Generation PHP Templating Engine**

[![PHP Version](https://img.shields.io/badge/PHP-8.5%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](CONTRIBUTING.md)

_Handlebars-inspired • Modern PHP • Production-Ready_

[Features](#-features) • [Installation](#-installation) • [Quick Start](#-quick-start) • [Documentation](#-documentation) • [Examples](#-examples)

</div>

---

## 🎯 Overview

**AzHbx** (AlizHarb Handlebars Extended) is a powerful, modern PHP templating engine designed for PHP 8.5+. It combines the elegance of Handlebars syntax with cutting-edge PHP features like Property Hooks and Attributes, delivering exceptional performance and developer experience.

### Why AzHbx?

- **🔒 Secure by Default**: Auto-escaping prevents XSS attacks
- **⚡ Blazing Fast**: Compiles to native PHP with OpCache support
- **🎨 Theme System**: Built-in multi-theme support with fallback logic
- **🧩 Modular Architecture**: Organize templates by feature/package
- **🔌 Plugin System**: Extend functionality with PHP 8.5 Attributes
- **📦 Zero Dependencies**: Works with or without Composer
- **🎯 Developer-Friendly**: Clean syntax, comprehensive docs, great DX

---

## ✨ Features

### Core Templating

- **Handlebars-Inspired Syntax**: Familiar `{{ }}` expressions
- **Nested Data Access**: Dot notation (`{{ user.profile.name }}`)
- **Control Structures**: `if`, `unless`, `each`, `with`
- **Layouts & Partials**: Template inheritance and reusable components
- **Custom Helpers**: Extend with your own logic
- **Whitespace Control**: Fine-tune output formatting

### Advanced Features

- **🎨 Theme Management**: Switch themes at runtime with smart fallbacks
- **📦 Module System**: Namespace templates by feature (`blog::post`)
- **🔌 Plugin Architecture**: Use PHP 8.5 `#[Helper]` attributes
- **⚡ Async Support**: Compatible with PHP Fibers for non-blocking I/O
- **🔐 Security First**: XSS prevention with auto-escaping
- **💾 Smart Caching**: Automatic recompilation on file changes

---

## 📦 Installation

### Via Composer (Recommended)

```bash
composer require alizharb/azhbx
```

### Standalone (No Composer)

Download the source and include the autoloader:

```php
require 'path/to/azhbx/src/autoload.php';
```

---

## 🚀 Quick Start

```php
use AlizHarb\AzHbx\Engine;

// Initialize
$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/cache',
]);

// Render
echo $engine->render('welcome', [
    'user' => [
        'name' => 'Alice',
        'role' => 'Admin'
    ]
]);
```

**Template** (`views/themes/default/welcome.hbx`):

```html
<h1>Welcome, {{ user.name }}!</h1>
<p>Role: {{ user.role }}</p>
```

---

## 📚 Documentation

Comprehensive documentation is available in the `docs/` directory or online:

- **[Installation & Configuration](docs/installation.md)**
- **[Basic Syntax](docs/basics.md)**
- **[Control Structures](docs/control-structures.md)**
- **[Layouts & Partials](docs/layouts-and-partials.md)**
- **[Custom Helpers](docs/helpers.md)**
- **[Plugins & Extensions](docs/plugins.md)**
- **[Themes & Modules](docs/themes-and-modules.md)**
- **[Advanced Topics](docs/advanced.md)**

### Interactive Documentation

Run the built-in documentation website:

```bash
php -S localhost:8000 -t docs
```

Visit `http://localhost:8000` to browse the interactive docs.

---

## 💡 Examples

Explore real-world examples in the `examples/` directory:

```bash
php -S localhost:8001 -t examples
```

Visit `http://localhost:8001` to see:

- Basic rendering
- Control structures
- Layouts and partials
- Custom helpers
- Theme switching
- Module system
- Plugin architecture
- Async rendering with Fibers

---

## 🔌 Plugin System

Create powerful extensions using PHP 8.5 Attributes:

```php
use AlizHarb\AzHbx\Contracts\PluginInterface;
use AlizHarb\AzHbx\Attributes\Helper;

class StringUtilsPlugin implements PluginInterface
{
    public function register(Engine $engine): void
    {
        // Optional: manual setup
    }

    #[Helper('uppercase')]
    public function uppercase(string $text): string
    {
        return strtoupper($text);
    }

    #[Helper('reverse')]
    public function reverse(string $text): string
    {
        return strrev($text);
    }
}

// Load plugin
$engine->loadPlugin(new StringUtilsPlugin());
```

Use in templates:

```html
<p>{{ uppercase "hello world" }}</p>
<!-- Output: HELLO WORLD -->
```

---

## 🎨 Theme System

Switch themes dynamically:

```php
$engine->setTheme('dark');
echo $engine->render('home', $data);
```

**Directory Structure:**

```
views/
├── themes/
│   ├── default/
│   │   └── home.hbx
│   └── dark/
│       └── home.hbx  ← Overrides default
└── partials/
    └── header.hbx
```

---

## 🧪 Testing

Run the test suite:

```bash
composer test
```

Or with coverage:

```bash
composer test:coverage
```

---

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

### Development Setup

```bash
git clone https://github.com/AlizHarb/azhbx.git
cd azhbx
composer install
composer test
```

---

## 📋 Requirements

- **PHP**: 8.5 or higher
- **Extensions**: `mbstring`

---

## 📄 License

AzHbx is open-source software licensed under the [MIT License](LICENSE).

---

## 🙏 Acknowledgments

Inspired by [Handlebars.js](https://handlebarsjs.com/) and modern PHP best practices.

---

<div align="center">

**Built with ❤️ by [Ali Harb](https://github.com/AlizHarb)**

[⬆ Back to Top](#-azhbx)

</div>
