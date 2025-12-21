# Project-Specific Guidelines

## Application Context
This is a School Information System (SIS) built with Laravel 12 and PHP 8.5.

## Code Style & Conventions
- Follow PSR-12 coding standards
- Use Laravel Pint for code formatting
- Always use type hints for methods and properties
- Use PHP 8.5 features (pipe operator, clone with syntax, URI extension)

## Database Conventions
- Use Eloquent relationships instead of manual joins
- Always eager load relationships to prevent N+1 queries
- Use database transactions for multi-step operations

## Security Guidelines
- Always validate user input using Form Request classes
- Use Laravel's built-in authorization (policies, gates)
- Never expose sensitive data in API responses
- Use parameterized queries (Eloquent/Query Builder)

## Testing Requirements
- Write feature tests for critical user flows
- Use factories for test data generation
- Aim for meaningful test coverage

## Architecture Patterns
- Keep controllers thin - delegate to service classes
- Use service classes in `app/Services/` for business logic
- Follow repository pattern where appropriate




