# Advanced Topics

This section covers advanced features and configuration options for AzHbx.

## Profiler

AzHbx includes a built-in profiler to help you analyze rendering performance.

### Enabling the Profiler

```php
$engine = new Engine([
    'debug' => true, // Profiler is enabled in debug mode
    // ...
]);
```

### Viewing Profile Data

After rendering, you can access the profile data.

```php
$output = $engine->render('home');
$profile = $engine->getProfiler()->getReport();

print_r($profile);
```

The report includes:

- **Total Time**: Total rendering time.
- **Memory Usage**: Peak memory usage.
- **Steps**: Breakdown of time spent in parsing, compiling, and rendering.

## Hot Reload (Development)

For a better development experience, AzHbx supports hot reloading of templates. When enabled, the engine checks if the template file has been modified since it was last compiled. If so, it recompiles it automatically.

```php
$engine = new Engine([
    'cache_path' => __DIR__ . '/cache',
    'auto_reload' => true, // Enable hot reload
]);
```

> [!TIP]
> Disable `auto_reload` in production for best performance.

## Security (XSS Prevention)

AzHbx automatically escapes all variable output (`{{ var }}`) using `htmlspecialchars`. This prevents Cross-Site Scripting (XSS) attacks.

To render unescaped HTML, use `{{{ var }}}`. **Only do this for trusted content.**

## Caching

AzHbx compiles templates into PHP code and caches them on disk. This ensures that subsequent renders are extremely fast.

Ensure your `cache_path` is writable by the web server.

```php
'cache_path' => __DIR__ . '/storage/cache/views',
```

To clear the cache, simply delete the files in the cache directory.
