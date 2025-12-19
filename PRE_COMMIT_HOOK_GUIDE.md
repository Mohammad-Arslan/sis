# Git Pre-Commit Hooks Guide

## What Are Pre-Commit Hooks?

Pre-commit hooks are scripts that Git automatically runs **before** you commit code. If the hook exits with an error (non-zero exit code), the commit is **blocked**.

## How They Work

1. **Location**: Hooks are stored in `.git/hooks/` directory
2. **Execution**: Git runs the `pre-commit` script automatically before each commit
3. **Result**: 
   - If hook exits with `0` → Commit proceeds ✅
   - If hook exits with `1` → Commit is blocked ❌

## Our Pre-Commit Hook

We've created a pre-commit hook that runs:

1. **PHPCS** - Code style checks on staged PHP files
2. **PHPStan** - Static analysis on the entire codebase

### What It Does

- ✅ Checks only **staged PHP files** for code style issues
- ✅ Runs PHPStan on the entire codebase (for consistency)
- ✅ Shows clear error messages if checks fail
- ✅ Blocks the commit if issues are found

## Usage

### Normal Usage

Just commit as usual - the hook runs automatically:

```bash
git add .
git commit -m "Your commit message"
# Pre-commit hook runs automatically here
```

### If Checks Fail

If the hook finds issues:

1. **Fix the issues** shown in the output
2. **Re-run the checks** manually if needed:
   ```bash
   composer phpcs:fix  # Auto-fix code style
   composer phpstan     # Check static analysis
   ```
3. **Stage the fixes** and commit again:
   ```bash
   git add .
   git commit -m "Your commit message"
   ```

### Bypassing the Hook (Not Recommended)

If you **really** need to bypass the hook (emergency only):

```bash
git commit --no-verify -m "Your commit message"
```

⚠️ **Warning**: Only use `--no-verify` when absolutely necessary. It defeats the purpose of code quality checks.

## Customizing the Hook

The hook is located at: `.git/hooks/pre-commit`

You can edit it to:
- Add more checks (Psalm, tests, etc.)
- Change which files are checked
- Make some checks optional
- Add custom validation rules

## Example Output

### ✅ Success
```
========================================
  Running Pre-Commit Checks
========================================

Staged PHP files:
  - app/Http/Controllers/UserController.php
  - app/Models/User.php

Running PHPCS on staged files...
✓ PHPCS passed

Running PHPStan...
✓ PHPStan passed

========================================
All pre-commit checks passed!
========================================
```

### ❌ Failure
```
========================================
  Running Pre-Commit Checks
========================================

Staged PHP files:
  - app/Http/Controllers/UserController.php

Running PHPCS on staged files...
✗ PHPCS failed
Run 'composer phpcs:fix' to auto-fix some issues

PHPCS errors:
FILE: app/Http/Controllers/UserController.php
--------------------------------------------------------------------------------
FOUND 5 ERRORS AFFECTING 3 LINES
--------------------------------------------------------------------------------
...

========================================
Pre-commit checks failed!
========================================
Please fix the issues above before committing.
You can bypass this hook with: git commit --no-verify
```

## Sharing Hooks with Team

⚠️ **Important**: The `.git/hooks/` directory is **NOT** tracked by Git by default.

To share hooks with your team, you have a few options:

### Option 1: Use a Hooks Manager (Recommended)

Use a tool like [pre-commit](https://pre-commit.com/) or [Husky](https://typicode.github.io/husky/) that manages hooks in your repository.

### Option 2: Manual Setup Script

Create a setup script that team members run:

```bash
#!/bin/bash
# setup-hooks.sh
cp .git/hooks/pre-commit.example .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
```

### Option 3: Store in Repository

Store hooks in a `hooks/` directory in your repo and copy them:

```bash
cp hooks/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
```

## Best Practices

1. ✅ **Keep hooks fast** - They run on every commit
2. ✅ **Check only staged files** when possible (faster)
3. ✅ **Provide clear error messages** - Help developers fix issues
4. ✅ **Make critical checks required** - Don't allow bypassing
5. ✅ **Document your hooks** - So team knows what runs

## Troubleshooting

### Hook Not Running

1. Check if the file exists: `ls -la .git/hooks/pre-commit`
2. Check if it's executable: `chmod +x .git/hooks/pre-commit`
3. Check file permissions: `ls -l .git/hooks/pre-commit`

### Hook Too Slow

- Only check staged files (not entire codebase)
- Run lighter checks in pre-commit
- Move heavy checks to CI/CD

### Need to Skip Hook Temporarily

```bash
git commit --no-verify -m "Emergency fix"
```

---

**Your pre-commit hook is now active!** 🎉

Every time you commit, it will automatically check your code quality.

