# 🗺️ AzHbx Product Roadmap

This document outlines the strategic vision and feature roadmap for the AzHbx templating engine. It serves as a guide for future development and version planning.

> **Note:** This roadmap is a living document and subject to change based on community feedback and technical requirements.

---

## 🚀 v1.1.0 - Developer Experience & Tooling

**Focus:** improving the workflow for developers and integrating with existing ecosystems.

### 🛠️ CLI Tooling

- **Pre-compiler CLI**: A command-line tool (`bin/azhbx`) to pre-compile templates during deployment builds. This removes compilation overhead in production.
  ```bash
  ./vendor/bin/azhbx compile --dir=views --out=cache
  ```
- **Cache Clearing**: Command to clear the template cache.
- **Syntax Check**: CLI command to validate template syntax without rendering.

### 🔌 Framework Integrations

- **Laravel Service Provider**: A dedicated package or built-in adapter to easily swap Blade with AzHbx in Laravel projects.
- **Symfony Bundle**: Integration for the Symfony framework.
- **PSR-7/15 Middleware**: Middleware to automatically render templates for PSR-7 responses.

### 🐛 Enhanced Debugging

- **Source Maps**: Better error reporting that maps compiled PHP errors back to the specific line and column in the original `.hbx` template file.
- **Debug Helper**: A `{{ debug }}` helper that dumps the current context data nicely (using `symfony/var-dumper` if available).

---

## ⚡ v1.2.0 - Extended Standard Library

**Focus:** Expanding the built-in capabilities to reduce the need for custom helpers in common scenarios.

### 🧰 New Built-in Helpers

- **Date/Time**: `{{ formatDate date "Y-m-d" }}`
- **Math**: `{{ add 1 2 }}`, `{{ sub 10 5 }}`, `{{ mul price quantity }}`
- **Strings**: `{{ upper title }}`, `{{ lower slug }}`, `{{ truncate description 100 }}`
- **Logic**: `{{ eq a b }}`, `{{ gt count 10 }}`, `{{ and condition1 condition2 }}`
- **Collections**: `{{ sort users "name" }}`, `{{ filter items "isActive" }}`

### 🎨 Advanced Layouts

- **Multiple Inheritance**: Support for deeper layout nesting.
- **Dynamic Partials**: `{{ partial (concat "user/" type) }}` - allowing dynamic resolution of partial names.

---

## 🔒 v1.3.0 - Security & Sandbox

**Focus:** Making AzHbx safe for running untrusted templates (e.g., user-submitted content).

### 🛡️ Sandbox Mode

- **Policy Manager**: Define strict allow-lists for methods and properties that can be accessed on objects.
- **Resource Limits**: Limits on execution time and memory usage for rendering.
- **Disable PHP**: Option to strictly forbid any raw PHP execution or access to certain globals.

### 🔐 Content Security

- **CSP Nonce Support**: Automatic injection of nonces into script/style tags.
- **Context-Aware Escaping**: Smarter escaping based on context (HTML attribute, JS string, CSS) to prevent sophisticated XSS.

---

## 🚀 v2.0.0 - The Next Generation

**Focus:** Major architectural improvements and breaking changes.

### 🏗️ Architecture

- **AST Transformation API**: Public API to modify the Abstract Syntax Tree before compilation. This allows plugins to implement completely new syntax or optimizations.
- **Streaming Rendering**: Full support for streaming responses, allowing the browser to start rendering HTML before the entire template is processed (great for large lists).
- **PHP 8.4+ Requirement**: Fully embrace Property Hooks and new PHP features dropping support for older versions.

### ⚡ Performance

- **JIT Compilation**: Just-In-Time compilation of hot code paths.
- **Zero-Copy Rendering**: Optimizations to reduce memory copying during string concatenation.

---

## 💡 Ideas for Investigation

- **VS Code Extension**: A dedicated extension for `.hbx` files with syntax highlighting, snippets, and autocomplete.
- **Live Reloading**: Integration with Vite/Webpack for hot-reloading templates during development.
- **Component System**: A syntax closer to modern component frameworks (like `<x-alert type="error">`) that maps to partials.
