# Contributing to AzHbx

Thank you for your interest in contributing to AzHbx! We welcome contributions from the community.

## 🚀 Getting Started

### Prerequisites

- PHP 8.5 or higher
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

### Code Style

- Follow **PSR-12** coding standards
- Use **strict types** (`declare(strict_types=1);`)
- Write **descriptive variable and method names**
- Add **PHPDoc comments** for all public methods

### Testing

- Write tests for all new features
- Ensure all tests pass before submitting PR
- Run tests with: `composer test`

### Commit Messages

Use clear, descriptive commit messages:

```
feat: add support for nested partials
fix: resolve XSS vulnerability in raw output
docs: update installation guide
test: add tests for helper registry
```

## 🐛 Reporting Bugs

When reporting bugs, please include:

- PHP version
- AzHbx version
- Minimal code to reproduce the issue
- Expected vs actual behavior
- Error messages/stack traces

## 💡 Suggesting Features

We love new ideas! When suggesting features:

- Check if it's already been suggested
- Explain the use case
- Provide examples if possible
- Consider backward compatibility

## 📥 Pull Request Process

1. **Update documentation** if needed
2. **Add tests** for new functionality
3. **Ensure all tests pass**
4. **Update CHANGELOG.md** with your changes
5. **Submit PR** with clear description

### PR Checklist

- [ ] Code follows PSR-12 standards
- [ ] All tests pass
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
- [ ] No breaking changes (or clearly documented)

## 🎯 Areas for Contribution

- **Bug fixes**
- **Performance improvements**
- **Documentation enhancements**
- **New helpers or plugins**
- **Test coverage improvements**
- **Examples and tutorials**

## 📜 Code of Conduct

- Be respectful and inclusive
- Provide constructive feedback
- Focus on what's best for the community
- Show empathy towards others

## 📞 Questions?

Feel free to open an issue for questions or join discussions.

---

Thank you for contributing to AzHbx! 🎉
