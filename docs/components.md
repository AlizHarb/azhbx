# Components

Components are reusable UI elements that help you build consistent and maintainable interfaces. AzHbx provides a powerful component system inspired by modern frameworks, supporting both inline and block usage, attribute passing, and slot content.

## Creating Components

Components are simply `.hbx` files stored in the `views/components` directory. The directory structure determines the component's name.

### Directory Structure

```
views/
  components/
    alert.hbx       -> <az-Alert />
    button.hbx      -> <az-Button />
    form/
      input.hbx     -> <az-Form.Input />
      label.hbx     -> <az-Form.Label />
```

### Component Template

A component file is a standard AzHbx template. It has access to:

1.  **Attributes**: Passed as named arguments (e.g., `type="success"`).
2.  **Slot**: The content inside the component block (for block components).
3.  **Context**: Any data passed from the parent scope.

**Example: `views/components/alert.hbx`**

```html
<div class="alert alert-{{ type }}">
  <div class="icon">
    <az-Icon name="{{ icon }}" />
  </div>
  <div class="content">{{{ slot }}}</div>
</div>
```

## Using Components

You can render components using the `<az-Name>` syntax.

### Inline Components

For components that don't have content, use the self-closing syntax:

```html
<az-Icon name="user" size="lg" />
```

### Block Components

For components that wrap content, use the block syntax. The content inside is available as `{{ slot }}` (or `{{{ slot }}}` for unescaped HTML) within the component.

```html
<az-Alert type="error" icon="warning">
  <strong>Error!</strong> Something went wrong.
</az-Alert>
```

## Passing Data

### Literal Strings

Attributes are passed as strings by default.

```html
<az-Button label="Save Changes" />
```

### Dynamic Variables

To pass a variable from the current scope, simply use the variable name. The engine will resolve it.

```html
<!-- Assuming $user['name'] is "Ali" -->
<az-ProfileCard name="user.name" />
```

### Attributes Bag

All attributes passed to the component are merged into the component's data context.

```html
<!-- Usage -->
<az-Button class="btn-primary" disabled="true" />

<!-- Component (button.hbx) -->
<button class="{{ class }}" {{#if disabled}}disabled{{/if}}>
    {{ label }}
</button>
```

## Module Components

If you are using the Module system, you can access components defined within a module using the `::` separator.

**Structure:**

```
modules/
  Blog/
    components/
      card.hbx
```

**Usage:**

```html
<az-Blog::Card title="My Post" />
```

## Nested Components

Components can be nested within other components.

```html
<az-Card>
  <az-Card.Header> <az-Icon name="user" /> User Profile </az-Card.Header>
  <az-Card.Body> ... </az-Card.Body>
</az-Card>
```

_Note: `Card.Header` would map to `views/components/card/header.hbx`._
