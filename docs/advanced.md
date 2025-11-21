# Advanced Topics

This section covers advanced features and internal mechanisms of AzHbx, including asynchronous rendering, security best practices, and caching.

## Asynchronous Rendering (Fibers)

AzHbx is designed for the modern PHP ecosystem, including support for non-blocking I/O via **Fibers** (introduced in PHP 8.1).

This allows you to pause the rendering of a template while waiting for data (e.g., a database query or API call) and resume it when the data is ready.

### Example: Async Data Loading

```php
$fiber = new Fiber(function () use ($engine) {
    // 1. Start rendering.
    // Imagine render() encounters a need for data and suspends itself,
    // or you suspend execution to fetch data.

    $data = Fiber::suspend('fetching_data');

    // 2. Resume with data
    return $engine->render('async_template', $data);
});

// Start the fiber
$status = $fiber->start();

if ($status === 'fetching_data') {
    // Simulate async operation
    $data = ['result' => 'Async Data Loaded!'];

    // Resume fiber with result
    echo $fiber->resume($data);
}
```

In a real-world scenario using an async framework (like Amphp or ReactPHP), this allows your template engine to be part of a non-blocking event loop.

## Security: XSS Prevention

Cross-Site Scripting (XSS) is a major vulnerability on the web. AzHbx mitigates this by **automatically escaping** all variable output.

### How it works

When you use `{{ variable }}`, the engine converts special characters to their HTML entities:

- `&` becomes `&amp;`
- `"` becomes `&quot;`
- `'` becomes `&#039;`
- `<` becomes `&lt;`
- `>` becomes `&gt;`

### Bypassing Escaping

If you explicitly need to render HTML (e.g., content from a CMS), use the triple stash `{{{ variable }}}`.

> [!CAUTION] > **Never** use `{{{ }}}` with user-submitted input. Always sanitize HTML using a library like HTML Purifier before passing it to the template if you intend to output it raw.

## Caching

To ensure high performance, AzHbx compiles your templates into native PHP code. This compilation happens only once (or when the template file changes).

### Cache Path

You must configure a writable cache directory:

```php
$engine = new Engine([
    'cache_path' => __DIR__ . '/cache',
    // ...
]);
```

### Clearing the Cache

During development, AzHbx checks file modification times to auto-recompile. In production, you should clear the cache directory during deployment to ensure fresh templates are generated.

```bash
rm -rf /path/to/cache/*
```

## Performance Tips

1.  **OpCache**: Ensure PHP's OpCache is enabled. Since AzHbx compiles to PHP files, OpCache will cache the compiled bytecode, making rendering extremely fast.
2.  **Minimize Logic**: Keep complex logic out of templates. Perform data processing in your controllers or services before passing data to the view.
3.  **Use Partials Wisely**: While partials are great for organization, excessive nesting can have a minor performance impact. Keep the hierarchy reasonable.
