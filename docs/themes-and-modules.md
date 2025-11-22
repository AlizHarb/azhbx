# Themes & Modules

AzHbx provides a robust system for organizing your views into Themes and Modules, allowing for easy customization and modularity.

## Themes

Themes allow you to change the look and feel of your application by switching between different sets of views.

### Directory Structure

Themes are located in the `views/themes/` directory.

```
views/
  themes/
    default/        <-- Default theme
      layouts/
      partials/
      home.hbx
    dark/           <-- "Dark" theme
      home.hbx
```

### Configuring the Active Theme

You can set the active theme when initializing the Engine or dynamically at runtime.

```php
$engine = new Engine([
    'views_path' => __DIR__ . '/views',
    'theme' => 'dark' // Set active theme
]);

// Or change it later
$engine->setTheme('dark');
```

### Resolution Logic

When you request a template (e.g., `$engine->render('home')`), AzHbx looks for it in the following order:

1.  **Active Theme**: `views/themes/{active_theme}/home.hbx`
2.  **Default Theme**: `views/themes/default/home.hbx`
3.  **Root**: `views/home.hbx` (Fallback)

This allows you to override specific templates in a custom theme while falling back to the default theme for others.

## Modules

Modules allow you to group related views, components, and logic together. This is perfect for large applications (e.g., Blog module, Forum module).

### Directory Structure

Modules are located in the `views/modules/` directory (or a custom path if configured).

```
views/
  modules/
    Blog/
      views/
        post.hbx
        archive.hbx
      components/
        card.hbx
```

### Using Module Views

To render a view from a module, use the `::` separator.

```php
echo $engine->render('Blog::post', ['title' => 'Hello']);
```

This resolves to: `views/modules/Blog/views/post.hbx` (or `views/modules/Blog/post.hbx` depending on structure).

_Note: The exact structure is defined by the `ModuleManager`. By default, it looks in `views/modules/{Module}/{View}.hbx`._

### Module Components

As described in the [Components](?page=components) section, you can use components from modules:

```html
<az-Blog::Card />
```

### Overriding Module Views

You can override module views from within your theme. This is powerful for theming 3rd-party modules.

**Path:** `views/themes/{theme}/modules/{Module}/{view}.hbx`

**Example:**
To override `Blog::post` in the `dark` theme, create:
`views/themes/dark/modules/Blog/post.hbx`

AzHbx will prefer this file over the original module file.
