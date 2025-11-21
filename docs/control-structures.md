# Control Structures

Control structures allow you to manipulate the flow of your templates. AzHbx provides a set of powerful built-in helpers for conditionals, loops, and context switching.

## Conditionals

### `if` / `else`

The `if` block renders its content only if the argument evaluates to a truthy value.

```html
{{#if isLoggedIn}}
<button>Logout</button>
{{else}}
<button>Login</button>
{{/if}}
```

You can also chain `else if` logic (using nested structures or custom helpers, though standard Handlebars usually relies on nesting):

```html
{{#if isAdmin}}
<p>Admin Panel</p>
{{else}} {{#if isModerator}}
<p>Mod Panel</p>
{{else}}
<p>User Dashboard</p>
{{/if}} {{/if}}
```

### `unless`

The `unless` helper is the inverse of `if`. It renders the block if the value is **falsy**.

```html
{{#unless hasLicense}}
<div class="alert">Please purchase a license.</div>
{{/unless}}
```

## Iteration

### `each`

The `each` helper iterates over arrays and objects.

#### Iterating Arrays

Inside the block, `{{ this }}` refers to the current element.

```php
$data = ['items' => ['Apple', 'Banana', 'Cherry']];
```

```html
<ul>
  {{#each items}}
  <li>{{ this }}</li>
  {{/each}}
</ul>
```

#### Iterating Objects

When iterating over objects, you can access the key using `{{ @key }}`.

```php
$data = ['user' => ['name' => 'John', 'age' => 30]];
```

```html
<dl>
  {{#each user}}
  <dt>{{ @key }}</dt>
  <dd>{{ this }}</dd>
  {{/each}}
</dl>
```

#### Empty Lists

You can provide an `{{else}}` block which will be rendered if the list is empty.

```html
{{#each items}}
<li>{{ this }}</li>
{{else}}
<li>No items found.</li>
{{/each}}
```

## Context Switching

### `with`

The `with` helper shifts the context to a specific property. This is useful for grouping nested data.

```php
$data = [
    'author' => [
        'name' => 'Jane Doe',
        'bio' => 'Writer'
    ]
];
```

```html
{{#with author}}
<h2>{{ name }}</h2>
<p>{{ bio }}</p>
{{/with}}
```

Inside the `with` block, you can access the parent context using `../`.

```html
{{#with author}}
<p>{{ name }} works at {{ ../companyName }}</p>
{{/with}}
```
