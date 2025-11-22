# Control Structures

AzHbx provides a set of built-in helpers for controlling the flow of your templates.

## If / Else

The `{{#if}}` helper renders a block if the argument is truthy (not false, null, empty array, 0, or empty string).

```html
{{#if isActive}}
<span class="badge">Active</span>
{{else}}
<span class="badge">Inactive</span>
{{/if}}
```

### Else If

You can chain conditions using `else if`.

```html
{{#if isAdmin}}
<p>Welcome Admin</p>
{{else if isModerator}}
<p>Welcome Moderator</p>
{{else}}
<p>Welcome User</p>
{{/if}}
```

## Unless

The `{{#unless}}` helper is the opposite of `if`. It renders the block if the argument is falsy.

```html
{{#unless isLoggedIn}}
<a href="/login">Login</a>
{{/unless}}
```

## Loops (Each)

The `{{#each}}` helper iterates over an array or iterable object.

```html
<ul>
  {{#each users}}
  <li>{{ name }} ({{ email }})</li>
  {{/each}}
</ul>
```

### Accessing the Current Item

Inside the loop, the context is set to the current item. You can access properties directly. To access the current item itself (if it's a primitive), use `{{ . }}` or `{{ this }}`.

### Loop Variables

_Note: Support for `@index`, `@key`, `@first`, `@last` is available in standard Handlebars, and AzHbx implements basic iteration._

### Empty Lists

You can use `{{else}}` with `each` to display content when the list is empty.

```html
{{#each items}}
<li>{{ name }}</li>
{{else}}
<li>No items found.</li>
{{/each}}
```

### Aliasing

You can alias the loop variable for clarity, especially in nested loops.

```html
{{#each users as |user|}} {{ user.name }} {{/each}}
```
