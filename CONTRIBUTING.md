# Contributing to AzHbx

Thank you for your interest in contributing to AzHbx! We welcome contributions from the community.

## 🚀 Getting Started

### Prerequisites

- PHP 8.3 or higher
- Composer
- Git

### Development Setup

1. **Fork the repository**
2. **Clone your fork**
   ```bash
   git clone https://github.com/YOUR_USERNAME/azhbx.git
   cd azhbx
   ```
3. **Install dependencies**
   ```bash
   composer install
   ```
4. **Create a branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

## 📝 Development Guidelines

### Coding Standards

We follow **PSR-12** coding standards and use **PHPStan** for static analysis.

- **Style**: Run `composer check-style` to verify and `composer fix-style` to auto-fix.
- **Analysis**: Run `composer analyse` to run PHPStan level 8 checks.
- **Strict Types**: All PHP files must start with `declare(strict_types=1);`.
- **Naming**:
  - Classes: PascalCase
  - Methods/Variables: camelCase
  - Constants: UPPER_SNAKE_CASE

### Testing

- Write tests for all new features using **Pest**.
- Ensure 100% pass rate before submitting.
- Run tests: `composer test`
- Run coverage: `composer test:coverage`

### Branching Model

- `main`: Stable release branch.
- `develop`: Integration branch for next release.
- `feature/*`: New features (branch off `develop`).
- `fix/*`: Bug fixes (branch off `develop` or `main` for hotfixes).

## 🐛 Reporting Bugs

Please include:

- PHP & AzHbx versions
- Minimal reproduction code
- Error code (if applicable, see `docs/errors.md`)

## 📥 Pull Request Process

1. **Update documentation** (including `docs/` and `README.md`).
2. **Add tests** covering the new functionality.
3. **Verify quality**: Run `composer test`, `composer analyse`, and `composer check-style`.
4. **Update CHANGELOG.md**.
5. **Submit PR** targeting the `develop` branch.

## 📜 Code of Conduct

Be respectful, inclusive, and constructive.

---

Thank you for contributing! 🎉
