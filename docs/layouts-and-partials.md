# Layouts & Partials

Building maintainable applications requires reusing code. AzHbx provides two primary mechanisms for this: **Partials** for reusable components, and **Layouts** for page structure inheritance.

## Partials

Partials are smaller templates that can be included in other templates. They are typically stored in `views/partials/`.

### Creating a Partial

Create a file `views/partials/card.hbx`:

```html
<div class="card">
  <h3>{{ title }}</h3>
  <p>{{ content }}</p>
</div>
```

### Using a Partial

You can include a partial using the `{{> partialName }}` syntax.

```html
{{> card }}
```

By default, the partial inherits the **current context**. If you want to pass specific data to the partial, you can do so as named arguments (if supported by your custom helper implementation) or by setting the context.

_Note: In standard AzHbx, partials inherit the current data context._

## Layouts (Template Inheritance)

Layouts allow you to define a master template (skeleton) and inject content into specific blocks. This is achieved using the `extend` and `block` helpers.

### 1. Define the Layout

Create a layout file, e.g., `views/themes/default/layouts/app.hbx`. Define placeholders using `{{#block "blockName"}}{{/block}}`.

```html
<!DOCTYPE html>
<html>
  <head>
    <title>{{ title }}</title>
  </head>
  <body>
    <header>{{> header }}</header>

    <main>
      {{#block "content"}}
      <!-- Default content if nothing is injected -->
      <p>Default content</p>
      {{/block}}
    </main>

    <footer>&copy; 2025</footer>
  </body>
</html>
```

### 2. Extend the Layout

In your page template (e.g., `home.hbx`), use `{{#extend "layoutName"}}` to specify which layout to use, and `{{#block "blockName"}}` to define the content for the placeholders.

```html
{{#extend "layouts/app"}} {{#block "content"}}
<h1>Welcome Home</h1>
<p>This content is injected into the 'content' block of the layout.</p>
{{/block}} {{/extend}}
```

> [!TIP]
> Ensure your layout paths are relative to your theme root or properly resolved by the engine.

### Nested Layouts

You can even nest layouts! A layout can extend another layout, allowing for complex hierarchies like `Base Layout -> Auth Layout -> Login Page`.
