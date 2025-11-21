# Themes & Modules

As your application grows, organizing templates becomes crucial. AzHbx provides first-class support for **Themes** (skinning your app) and **Modules** (component-based architecture).

## Themes

Themes allow you to completely change the look of your application by switching the active template directory. This is perfect for CMSs, multi-tenant apps, or simply supporting Dark/Light modes via different template sets.

### Directory Structure

Themes live in the `themes/` directory under your `views_path`.

```text
views/
└── themes/
    ├── default/      <-- The default theme
    │   ├── layout.hbx
    │   └── home.hbx
    └── dark/         <-- A secondary theme
        └── layout.hbx
```

### Switching Themes

You can set the active theme at runtime using the `setTheme` method.

```php
$engine->setTheme('dark');
```

### Fallback Logic

AzHbx implements a smart fallback system. If you request a template while the `dark` theme is active:

1.  Check `views/themes/dark/template.hbx`
2.  If not found, check `views/themes/default/template.hbx`

This allows you to create "child themes" that only override specific templates while inheriting the rest from the default theme.

## Modules

Modules are designed for package-based or domain-driven architectures. They allow you to namespace your templates.

### Directory Structure

Modules live in the `modules/` directory.

```text
views/
└── modules/
    ├── blog/         <-- Module name: "blog"
    │   ├── post.hbx
    │   └── list.hbx
    └── auth/         <-- Module name: "auth"
        └── login.hbx
```

### Rendering Module Templates

To render a template from a module, use the double-colon `::` syntax: `moduleName::templateName`.

```php
// Render the 'post' template from the 'blog' module
echo $engine->render('blog::post', ['title' => 'My Post']);
```

### Overriding Module Templates

You can override module templates from within your active theme. This is powerful for theming third-party modules.

**Structure for Override:**

```text
views/
└── themes/
    └── default/
        └── modules/
            └── blog/
                └── post.hbx  <-- Overrides the original blog::post
```

When you call `$engine->render('blog::post')`, AzHbx looks in this order:

1.  `views/themes/{activeTheme}/modules/blog/post.hbx`
2.  `views/themes/default/modules/blog/post.hbx`
3.  `views/modules/blog/post.hbx`
