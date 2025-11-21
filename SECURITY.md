# Security Policy

## Supported Versions

We release patches for security vulnerabilities for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x.x   | :white_check_mark: |

## Reporting a Vulnerability

We take security seriously. If you discover a security vulnerability, please follow these steps:

### 🔒 Private Disclosure

**DO NOT** open a public issue for security vulnerabilities.

Instead, please email security details to:

**Email**: security@example.com (Replace with your actual security contact)

### 📝 What to Include

Please include the following in your report:

- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if any)
- Your contact information

### ⏱️ Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Timeline**: Varies based on severity

### 🎁 Recognition

We appreciate responsible disclosure. Security researchers who report valid vulnerabilities will be:

- Credited in our CHANGELOG (if desired)
- Mentioned in our security acknowledgments
- Notified when the fix is released

## Security Best Practices

When using AzHbx:

1. **Always use auto-escaping** (default behavior)
2. **Never use `{{{ }}}` with user input**
3. **Keep dependencies updated**
4. **Use the latest stable version**
5. **Validate all user input** before passing to templates
6. **Set proper file permissions** on cache directories

## Known Security Features

- ✅ Auto-escaping enabled by default
- ✅ XSS prevention
- ✅ No eval() or dynamic code execution
- ✅ Compiled templates cached securely

Thank you for helping keep AzHbx secure! 🔒
