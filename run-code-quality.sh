#!/bin/bash

# Code Quality Tools Runner Script
# This script runs all static analysis and code quality tools

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Project root directory
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_ROOT"

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Code Quality Tools Runner${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Track if any tool fails
FAILED=0

# Function to run a tool and check its exit code
run_tool() {
    local tool_name=$1
    local tool_command=$2
    
    echo -e "${YELLOW}Running ${tool_name}...${NC}"
    echo "----------------------------------------"
    
    if eval "$tool_command"; then
        echo -e "${GREEN}✓ ${tool_name} passed${NC}"
        echo ""
        return 0
    else
        echo -e "${RED}✗ ${tool_name} failed${NC}"
        echo ""
        FAILED=1
        return 1
    fi
}

# PHPStan - Static Analysis
run_tool "PHPStan" "vendor/bin/phpstan analyse --memory-limit=2G" || true

# Psalm - Type Checking
run_tool "Psalm" "vendor/bin/psalm" || true

# PHPCS - Code Style (PSR-12)
run_tool "PHPCS" "vendor/bin/phpcs" || true

# PHPMD - Code Smells
run_tool "PHPMD" "vendor/bin/phpmd app,routes,database,config,tests text phpmd.xml" || true

# PHPCPD - Duplicate Code Detection
run_tool "PHPCPD" "vendor/bin/phpcpd --exclude vendor --exclude storage --exclude public --exclude bootstrap/cache --exclude node_modules --min-lines 5 --min-tokens 50 app routes database config tests" || true

# Summary
echo -e "${BLUE}========================================${NC}"
if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}All code quality checks passed!${NC}"
    exit 0
else
    echo -e "${RED}Some code quality checks failed.${NC}"
    echo -e "${YELLOW}Please review the output above and fix the issues.${NC}"
    exit 1
fi


