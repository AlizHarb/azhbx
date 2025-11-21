# Installation & Configuration

Getting started with AzHbx is straightforward. We adhere to standard PHP practices, making integration into any project seamless.

## Requirements

- **PHP**: Version 8.5 or higher.
- **Extensions**: `mbstring`.

## Installation

### Option 1: Via Composer (Recommended)

Install the package via Composer:

```bash
composer require alizharb/azhbx
```

Include the Composer autoloader in your project:

```php
require 'vendor/autoload.php';
```

### Option 2: Standalone (No Composer)

If you are not using Composer, you can download the source code and include the built-in autoloader.

1.  Download the `azhbx` directory to your project (e.g., in `lib/`).
2.  Include `src/autoload.php`.

```php
require 'lib/azhbx/src/autoload.php';
```

## Configuration

The `Engine` class is the entry point for AzHbx. It accepts an array of configuration options.

```php
use AlizHarb\AzHbx\Engine;

$config = [
    'views_path' => __DIR__ . '/views',
    'cache_path' => __DIR__ . '/storage/cache',
];

$engine = new Engine($config);
```

### Configuration Options

| Option          | Type     | Default               | Description                                                             |
| :-------------- | :------- | :-------------------- | :---------------------------------------------------------------------- |
| `views_path`    | `string` | `getcwd() . '/views'` | The root directory where your templates are stored.                     |
| `cache_path`    | `string` | `getcwd() . '/cache'` | Directory where compiled PHP templates are saved. **Must be writable.** |
| `extension`     | `string` | `'hbx'`               | The file extension for your templates (e.g., `.hbx`, `.hbs`, `.html`).  |
| `default_theme` | `string` | `'default'`           | The default theme directory name within `views/themes/`.                |
| `delimiters`    | `array`  | `['{{', '}}']`        | Custom delimiters if you need to avoid conflicts with other tools.      |

### Directory Structure

We recommend the following directory structure:

```text
project/
├── src/
├── storage/
│   └── cache/          <-- Writable cache directory
└── views/              <-- Your views_path
    ├── themes/
    │   ├── default/    <-- Default theme templates
    │   │   ├── layout.hbx
    │   │   └── home.hbx
    │   └── dark/       <-- Alternative theme
    ├── partials/       <-- Global partials
    │   ├── header.hbx
    │   └── footer.hbx
    └── modules/        <-- Module-specific templates
        └── blog/
            └── views/
                └── post.hbx
```
