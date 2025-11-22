# Error Codes

AzHbx uses specific error codes to help you identify and resolve issues quickly.

## E001: Template Not Found

**Message:** `Template '{name}' not found in theme '{theme}' or default.`
**Cause:** The engine could not locate the requested view file in the active theme, default theme, or root views directory.
**Solution:** Check the file path and ensure the file exists and has the `.hbx` extension.

## E002: Helper Not Found

**Message:** `Helper '{name}' not found.`
**Cause:** You tried to use a helper that has not been registered.
**Solution:** Register the helper using `$engine->registerHelper()` or ensure the plugin providing it is loaded.

## E003: Invalid Syntax

**Message:** `Syntax error in template...`
**Cause:** The template contains invalid syntax, such as unclosed braces or malformed tags.
**Solution:** Review the template code around the line number provided.

## E004: Component Not Found

**Message:** `Component '{name}' not found.`
**Cause:** You tried to use a component `<az-Name>` that does not exist in `views/components`.
**Solution:** Create the component file or check for typos in the component name.

## Debugging

Enable debug mode in the Engine configuration to see detailed stack traces.

```php
$engine = new Engine([
    'debug' => true,
    // ...
]);
```
