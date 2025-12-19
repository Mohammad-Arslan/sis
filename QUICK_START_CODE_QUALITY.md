# Quick Start - Code Quality Tools

## 🚀 Installation (Copy-Paste Ready)

```bash
# Install all code quality tools
composer require --dev \
    larastan/larastan:^3.0 \
    phpstan/phpstan:^2.0 \
    vimeo/psalm:^6.0 \
    psalm/plugin-laravel:^2.7 \
    squizlabs/php_codesniffer:^3.10 \
    phpmd/phpmd:^2.15 \
    sebastian/phpcpd:^7.0
```

Or simply:

```bash
composer install
```

## 📋 Run All Tools

```bash
# Run all tools at once
composer analyse
```

## 🔧 Individual Tool Commands

```bash
# PHPStan - Static Analysis
composer phpstan
# or
vendor/bin/phpstan analyse --memory-limit=2G

# Psalm - Type Checking
composer psalm
# or
vendor/bin/psalm

# PHPCS - Code Style (PSR-12)
composer phpcs
# or
vendor/bin/phpcs

# Auto-fix code style issues
composer phpcs:fix
# or
vendor/bin/phpcbf

# PHPMD - Code Smells
composer phpmd
# or
vendor/bin/phpmd app,routes,database,config,tests text phpmd.xml

# PHPCPD - Duplicate Code Detection
composer phpcpd
# or
vendor/bin/phpcpd --exclude vendor --exclude storage --exclude public --exclude bootstrap/cache --exclude node_modules --min-lines 5 --min-tokens 50 app routes database config tests
```

## 📁 Configuration Files

All configuration files are in the project root:

- `phpstan.neon` - PHPStan/Larastan config
- `psalm.xml` - Psalm config
- `phpcs.xml` - PHPCS config (PSR-12)
- `phpmd.xml` - PHPMD config

## 🔄 GitHub Actions

The workflow (`.github/workflows/code-quality.yml`) automatically runs on:
- Push to `main`, `master`, `development`
- Pull requests to `main`, `master`, `development`

## 📚 Full Documentation

See `CODE_QUALITY_SETUP.md` for detailed documentation.

