# HBX Specification (Draft 1.0)

## Overview

HBX (Handlebars Extended) is a superset of the Handlebars templating language, designed for PHP applications with a focus on performance, type safety, and modern developer experience.

## Syntax

### Expressions

- `{{ variable }}`: Output variable (escaped).
- `{{{ variable }}}`: Output variable (raw/unescaped).
- `{{ expression }}`: Evaluate expression (e.g., `{{ user.name | upper }}`).

### Control Structures

- `{{#if condition}} ... {{/if}}`
- `{{#each array as |item|}} ... {{/each}}`
- `{{#unless condition}} ... {{/unless}}`

### Helpers

- Built-in helpers: `if`, `unless`, `each`, `with`, `log`.
- Custom helpers registered via `AzHbx::registerHelper('name', callback)`.

### Partials

- `{{> partialName }}`: Include a partial.
- `{{> partialName param=value }}`: Include with parameters.

### Comments

- `{{! This is a comment }}`
- `{{!-- This is a block comment --}}`

## Extensions

- **Dual Mode**: Interpreted (dev) vs Compiled (prod).
- **Type Safety**: Strict typing in helpers and data context.
- **Security**: Auto-escaping by default, CSP support.

## File Extension

- `.hbx` is the standard file extension.
