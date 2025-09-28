# Contributing

Thank you for considering contributing to Laravel CIDI! Please read this guide before submitting any pull requests.

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues as you might find out that you don't need to create one. When you are creating a bug report, please include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples to demonstrate the steps**
- **Describe the behavior you observed after following the steps**
- **Explain which behavior you expected to see instead and why**
- **Include screenshots and animated GIFs if possible**
- **Include your environment details (OS, PHP version, Laravel version, etc.)**

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, please include:

- **Use a clear and descriptive title**
- **Provide a step-by-step description of the suggested enhancement**
- **Provide specific examples to demonstrate the steps**
- **Describe the current behavior and explain which behavior you expected to see instead**
- **Explain why this enhancement would be useful**
- **List some other applications where this enhancement exists**

### Pull Requests

- Fill in the required template
- Do not include issue numbers in the PR title
- Include screenshots and animated GIFs in your pull request whenever possible
- Follow the [PSR-12 coding standard](https://www.php-fig.org/psr/psr-12/)
- Include thoughtfully-worded, well-structured tests
- Document new code based on the [Documentation Styleguide](https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md)
- End all files with a newline
- Place `use` statements at the top of the file
- Place `use` statements in alphabetical order
- Place `use` statements in the following order:
  1. Core Laravel classes
  2. Third-party packages
  3. Application classes

## Development Setup

1. Fork the repository
2. Clone your fork: `git clone https://github.com/your-username/laravel-cidi.git`
3. Install dependencies: `composer install`
4. Install dev dependencies: `composer install --dev`
5. Run tests: `composer test`
6. Run code style checks: `composer cs-check`

## Testing

We use PHPUnit for testing. Please ensure all tests pass before submitting a pull request:

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Run specific test
./vendor/bin/phpunit tests/commands/CidiInstallCommandTest.php
```

## Code Style

We follow PSR-12 coding standards. Please ensure your code follows these standards:

```bash
# Check code style
composer cs-check

# Fix code style issues
composer cs-fix
```

## Documentation

Please ensure that any new features or changes are properly documented:

- Update the README.md if necessary
- Add or update docblocks for new methods
- Update the CHANGELOG.md for any user-facing changes

## Commit Messages

Please follow these guidelines for commit messages:

- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 72 characters or less
- Reference issues and pull requests liberally after the first line
- Consider starting the commit message with an applicable emoji:
  - 🎨 `:art:` when improving the format/structure of the code
  - 🐛 `:bug:` when fixing a bug
  - ✨ `:sparkles:` when adding a new feature
  - 📝 `:memo:` when writing docs
  - 🚀 `:rocket:` when improving performance
  - ♻️ `:recycle:` when refactoring code
  - 🔧 `:wrench:` when fixing a CI build
  - 🧪 `:test_tube:` when adding tests
  - 🔒 `:lock:` when dealing with security

## Release Process

1. Update the version in `composer.json`
2. Update the `CHANGELOG.md`
3. Create a new release on GitHub
4. Tag the release

## Questions?

If you have any questions about contributing, please open an issue or contact us at subhashladumor1@gmail.com.

Thank you for contributing to Laravel CIDI! 🚀

