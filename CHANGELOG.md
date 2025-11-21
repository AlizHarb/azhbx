# Changelog

All notable changes to AzHbx will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-01-21

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

---

## [Unreleased]

### Planned

- Additional built-in helpers
- Performance optimizations
- Enhanced error messages
- IDE autocomplete support
- More examples and tutorials

---

[1.0.0]: https://github.com/alizharb/azhbx/releases/tag/v1.0.0
