# Layouts & Partials

AzHbx provides a powerful layout inheritance system, similar to Blade or Twig, allowing you to build complex page structures with ease.

## Partials

Partials are reusable snippets of code that can be included in other views. They are useful for headers, footers, and shared UI elements.

### Creating a Partial

Partials are stored in `views/partials/` (by convention, though they can be anywhere).

**`views/partials/header.hbx`**

```html
<header>
  <h1>{{ title }}</h1>
</header>
```

### Using a Partial

Use the `{{> name }}` syntax or `{{ partial "name" }}` helper.

```html
{{> partials/header title="My Page" }}
```

You can pass data to the partial as named arguments. These will be merged into the partial's context.

## Layouts (Inheritance)

Layouts define the skeleton of your HTML page. Child views "extend" a layout and inject content into specific "blocks".

### Creating a Layout

Layouts are typically stored in `views/layouts/`.

**`views/layouts/app.hbx`**

```html
<!DOCTYPE html>
<html>
  <head>
    <title>{{ title }}</title>
  </head>
  <body>
    <nav>...</nav>

    <main>
      {{#block "content"}}
      <!-- Default content if not overridden -->
      <p>Default content</p>
      {{/block}}
    </main>

    <footer>{{#block "footer"}} &copy; 2025 {{/block}}</footer>
  </body>
</html>
```

### Extending a Layout

In your child view, use the `{{#extend}}` helper to specify the parent layout, and `{{#block}}` to define content for the blocks.

**`views/home.hbx`**

```html
{{#extend "layouts/app"}} {{#block "content"}}
<h2>Welcome Home</h2>
<p>This is the home page content.</p>
{{/block}} {{#block "footer"}}
<p>Custom footer for home page</p>
{{/block}} {{/extend}}
```

### Nested Layouts

You can chain layouts! A child view extends `layouts/two-column`, which extends `layouts/app`.

**`views/layouts/two-column.hbx`**

```html
{{#extend "layouts/app"}} {{#block "content"}}
<div class="sidebar">{{#block "sidebar"}} Sidebar {{/block}}</div>
<div class="main">{{#block "main"}} Main {{/block}}</div>
{{/block}} {{/extend}}
```
