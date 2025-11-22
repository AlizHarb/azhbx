# Changelog

All notable changes to AzHbx are documented here.

---

## [1.1.0] - 2025-11-22

<details open>
<summary><strong>✨ Added Features</strong></summary>

### Component System

- **Syntax**: Added support for `<az-Name ... />` (inline) and `<az-Name ...>...</az-Name>` (block) syntax.
- **Manager**: Implemented `ComponentManager` to auto-register components from `views/components`.
- **Modules**: Added support for Module components using `::` separator (e.g., `<az-Blog::Card />`).
- **Attributes**: Implemented automatic attribute merging into component context.
- **Slots**: Added `{{ slot }}` support for block components.
- **Nesting**: Supported nested components (e.g., `views/components/card/header.hbx` -> `<az-Card.Header>`).

### Directive System

- **Syntax**: Added support for bare `@directive` syntax (e.g., `@csrf`, `@auth`).
- **Registry**: Implemented `DirectiveRegistry` to manage built-in and custom directives.
- **Built-ins**:
  - `@csrf`: Generates hidden CSRF input.
  - `@method`: Generates hidden HTTP method input.
  - `@auth` / `@guest`: Authentication control structures.
  - `@env`: Environment check control structure.
- **Attributes**: Added `#[Directive]` attribute for defining directives in plugins.

### Core Enhancements

- **Named Arguments**: Updated `Parser` and `Compiler` to support named arguments in helpers (`key="value"`).
- **Quote Handling**: Improved `Parser` to preserve quotes for literal strings vs variable paths.
- **Attribute Resolution**: Updated `BuiltInHelpers` and `ComponentManager` to correctly resolve attributes (literals vs variables).

</details>

<details>
<summary><strong>🔄 Changed</strong></summary>

### Parser

- Added `preprocess` method to transform `<az-` and `@directive` syntax into internal Handlebars syntax.
- Updated regex to support colons (`:`) in tag names for Module components.
- Refactored argument parsing to better handle quotes and named arguments.

### BuiltInHelpers

- Exposed `resolveValue` as a public static method for use by `ComponentManager`.
- Updated `partial`, `extend`, and `block` helpers to strip quotes from names if present.

### ComponentManager

- Updated `registerComponents` to scan both `views/components` and `views/modules/{Module}/components`.
- Implemented recursive directory scanning for nested components.

### Documentation

- Added `docs/components.md`, `docs/directives.md`.
- Overhauled `docs/basics.md`, `docs/control-structures.md`, `docs/helpers.md`, `docs/layouts-and-partials.md`, `docs/themes-and-modules.md`, `docs/plugins.md`, `docs/errors.md`, `docs/advanced.md`.
- Added Right-Side Table of Contents with ScrollSpy to documentation.
- Added Sponsor link and Changelog page to documentation UI.

</details>

---

## [1.0.0] - 2025-01-21

<details>
<summary><strong>🎉 Initial Release</strong></summary>

### Added

- 🎉 Initial release of AzHbx
- ✨ Handlebars-inspired templating syntax
- 🔒 Auto-escaping for XSS prevention
- 🎨 Theme system with fallback support
- 📦 Module system for namespaced templates
- 🔌 Plugin architecture with PHP 8.5 Attributes
- ⚡ Async rendering support with Fibers
- 🛠️ Custom helper registration
- 📐 Layout inheritance and partials
- 🎯 Dot notation for nested data access
- 📚 Comprehensive documentation
- 💡 Interactive examples
- 🧪 Full test suite with Pest
- 📦 Standalone autoloader (no Composer required)

### Features

- **Core Engine**: Template compilation and rendering
- **Built-in Helpers**: `if`, `unless`, `each`, `with`, `partial`, `extend`, `block`
- **Theme Manager**: Multi-theme support with smart fallbacks
- **Module Manager**: Organize templates by feature/package
- **Helper Registry**: Register custom helpers
- **Partial Loader**: Reusable template components
- **Compiler**: Converts templates to optimized PHP code
- **Renderer**: Executes compiled templates with data context

### Documentation

- Installation & Configuration guide
- Basic Syntax reference
- Control Structures tutorial
- Layouts & Partials guide
- Custom Helpers documentation
- Plugins & Extensions guide
- Themes & Modules architecture
- Advanced Topics (Security, Caching, Performance)

### Examples

- Basic rendering
- Control structures
- Layouts and partials
- Custom helpers
- Theme switching
- Module system
- Plugin architecture
- Async rendering with Fibers
- Standalone usage (no Composer)

</details>

---

[1.1.0]: https://github.com/alizharb/azhbx/releases/tag/v1.1.0
[1.0.0]: https://github.com/alizharb/azhbx/releases/tag/v1.0.0
