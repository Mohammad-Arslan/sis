# Code Quality Setup Guide

This document provides instructions for setting up and using static analysis and code quality tools for the Laravel 12 project.

## 📦 Installation

### Step 1: Install Composer Dependencies

Run the following command to install all code quality tools:

```bash
composer require --dev \
    larastan/larastan:^3.0 \
    phpstan/phpstan:^2.0 \
    vimeo/psalm:^6.0 \
    psalm/plugin-laravel:^2.7 \
    squizlabs/php_codesniffer:^3.10 \
    phpmd/phpmd:^2.15 \
    sebastian/phpcpd:^7.0
```

Or simply run:

```bash
composer install
```

This will install all dependencies including the dev dependencies defined in `composer.json`.

## 🛠️ Configuration Files

All configuration files are already created in the project root:

- `phpstan.neon` - PHPStan/Larastan configuration
- `psalm.xml` - Psalm configuration
- `phpcs.xml` - PHP CodeSniffer configuration (PSR-12)
- `phpmd.xml` - PHPMD configuration
- PHPCPD uses command-line arguments (no config file needed)

## 🚀 Usage

### Run All Tools

You can run all tools individually or create a script to run them all at once.

#### Individual Commands

**PHPStan (Static Analysis):**
```bash
vendor/bin/phpstan analyse --memory-limit=2G
```

**Psalm (Type Checking):**
```bash
vendor/bin/psalm
```

**PHPCS (Code Style - PSR-12):**
```bash
vendor/bin/phpcs
```

**PHPMD (Code Smells):**
```bash
vendor/bin/phpmd app,routes,database,config,tests text phpmd.xml
```

**PHPCPD (Duplicate Code Detection):**
```bash
vendor/bin/phpcpd \
    --exclude vendor \
    --exclude storage \
    --exclude public \
    --exclude bootstrap/cache \
    --exclude node_modules \
    --min-lines 5 \
    --min-tokens 50 \
    app routes database config tests
```

### Create a Convenience Script

Add this to your `composer.json` scripts section:

```json
"scripts": {
    "analyse": [
        "@phpstan",
        "@psalm",
        "@phpcs",
        "@phpmd",
        "@phpcpd"
    ],
    "phpstan": "vendor/bin/phpstan analyse --memory-limit=2G",
    "psalm": "vendor/bin/psalm",
    "phpcs": "vendor/bin/phpcs",
    "phpcs:fix": "vendor/bin/phpcbf",
    "phpmd": "vendor/bin/phpmd app,routes,database,config,tests text phpmd.xml",
    "phpcpd": "vendor/bin/phpcpd --exclude vendor --exclude storage --exclude public --exclude bootstrap/cache --exclude node_modules --min-lines 5 --min-tokens 50 app routes database config tests"
}
```

Then run all tools with:

```bash
composer analyse
```

## 🔧 Tool-Specific Usage

### PHPStan

**Run with baseline (ignore existing errors):**
```bash
vendor/bin/phpstan analyse --generate-baseline
```

**Run with specific level:**
```bash
vendor/bin/phpstan analyse --level=7
```

**Run on specific path:**
```bash
vendor/bin/phpstan analyse app/Http/Controllers
```

### Psalm

**Generate baseline:**
```bash
vendor/bin/psalm --set-baseline=psalm-baseline.xml
```

**Run with baseline:**
```bash
vendor/bin/psalm --use-baseline=psalm-baseline.xml
```

**Show info about issues:**
```bash
vendor/bin/psalm --show-info=true
```

### PHPCS

**Check code style:**
```bash
vendor/bin/phpcs
```

**Auto-fix code style issues:**
```bash
vendor/bin/phpcbf
```

**Check specific file:**
```bash
vendor/bin/phpcs app/Http/Controllers/UserController.php
```

**Show summary:**
```bash
vendor/bin/phpcs --report=summary
```

### PHPMD

**Run with specific format:**
```bash
# Text format
vendor/bin/phpmd app text phpmd.xml

# XML format
vendor/bin/phpmd app xml phpmd.xml

# HTML format
vendor/bin/phpmd app html phpmd.xml --reportfile phpmd-report.html
```

**Check specific directory:**
```bash
vendor/bin/phpmd app/Http/Controllers text phpmd.xml
```

### PHPCPD

**Find duplicates with custom thresholds:**
```bash
vendor/bin/phpcpd \
    --min-lines 10 \
    --min-tokens 70 \
    app
```

**Generate XML report:**
```bash
vendor/bin/phpcpd --log-pmd phpcpd-report.xml app
```

**Generate JSON report:**
```bash
vendor/bin/phpcpd --log-pmd phpcpd-report.json app
```

## 📊 GitHub Actions

The project includes a GitHub Actions workflow (`.github/workflows/code-quality.yml`) that automatically runs all code quality checks on:

- Every push to `main`, `master`, or `development` branches
- Every pull request to `main`, `master`, or `development` branches

The workflow runs all tools in parallel and provides detailed reports in the Actions tab.

## 🎯 PHP 8.5 Features Support

All tools are configured to support PHP 8.5 features:

- ✅ Null-safe operators (`?->`)
- ✅ Union types (`string|int`)
- ✅ Intersection types (`A&B`)
- ✅ Attributes (`#[Attribute]`)
- ✅ Named arguments
- ✅ Match expressions
- ✅ Constructor property promotion
- ✅ Readonly properties
- ✅ Enums
- ✅ First-class callables

## 🔍 Configuration Details

### PHPStan Configuration

- **Level**: 5 (moderate strictness, can be increased gradually)
- **PHP Version**: 8.5
- **Paths**: `app/`, `routes/`, `database/`, `config/`
- **Excludes**: `vendor/`, `storage/`, `public/`, `bootstrap/cache/`

### Psalm Configuration

- **Error Level**: 4 (moderate strictness)
- **PHP Version**: 8.5
- **Laravel Plugin**: Enabled
- **Paths**: `app/`, `routes/`, `database/`, `config/`

### PHPCS Configuration

- **Standard**: PSR-12
- **Line Length**: 120 characters (soft), 140 characters (hard limit)
- **Paths**: `app/`, `routes/`, `database/`, `config/`, `tests/`

### PHPMD Configuration

- **Rulesets**: codesize, unusedcode, naming, design, controversial, cleancode
- **Paths**: `app/`, `routes/`, `database/`, `config/`, `tests/`

### PHPCPD Configuration

- **Minimum Lines**: 5
- **Minimum Tokens**: 50
- **Paths**: `app/`, `routes/`, `database/`, `config/`, `tests/`

## 🐛 Troubleshooting

### PHPStan Memory Issues

If PHPStan runs out of memory:

```bash
vendor/bin/phpstan analyse --memory-limit=4G
```

### Psalm Plugin Issues

If Psalm can't find Laravel classes:

```bash
vendor/bin/psalm --clear-cache
vendor/bin/psalm
```

### PHPCS Not Finding Standard

If PHPCS can't find PSR-12:

```bash
composer require --dev dealerdirect/phpcodesniffer-composer-installer
composer update
```

## 📝 Best Practices

1. **Run tools before committing**: Use pre-commit hooks or run `composer analyse` before pushing
2. **Fix issues incrementally**: Don't try to fix everything at once
3. **Use baselines**: Generate baselines for existing code and fix new code
4. **Increase strictness gradually**: Start with lower levels and increase over time
5. **Focus on new code**: Ensure new code passes all checks
6. **Review CI results**: Check GitHub Actions for any issues

## 🔗 Resources

- [PHPStan Documentation](https://phpstan.org/)
- [Larastan Documentation](https://github.com/larastan/larastan)
- [Psalm Documentation](https://psalm.dev/)
- [PHPCS Documentation](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHPMD Documentation](https://phpmd.org/)
- [PHPCPD Documentation](https://github.com/sebastianbergmann/phpcpd)

## 📄 License

This setup is part of the Laravel project and follows the same license.

