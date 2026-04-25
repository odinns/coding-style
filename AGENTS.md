# AGENTS.md

## Project

This repo is `odinns/coding-style`, a public Composer package for shared Rector and PHPStan/Larastan defaults for Laravel projects.

It is a package, not a Laravel app.

## Package Rules

- Keep the package standalone and public-safe.
- Do not reference private client, employer, or source-project names in code, docs, tests, examples, commit messages, or comments.
- Do not add local application history to shared defaults.
- Do not add project-specific Rector skips, PHPStan baselines, or legacy ignores to this package.
- Keep consuming-project config small:
  - local paths stay local
  - local skips stay local
  - local baselines stay local
  - local ignores stay local
- Prefer stable, boring rules over aggressive rewrites.
- This package does not provide a Laravel service provider, published config, migrations, routes, views, commands, or runtime integration.
- This package does not format code. Rector and PHPStan only.

## Public Interfaces

The main public API is:

```php
Odinns\CodingStyle\OdinnsRectorConfig::setup(Rector\Config\RectorConfig $rectorConfig): void
```

Shipped consumer config files:

```text
config/phpstan.neon
config/larastan.neon
stubs/rector.php.stub
stubs/phpstan.neon.stub
```

Avoid breaking these paths without a major version.

## Testing

Run the full check before committing:

```bash
composer test:all
```

This runs:

```bash
composer test:composer
composer test:types
composer test:refactor
composer test
```

Tests use Pest. Add tests under `tests/Unit`.

## Release

Tags are plain semver tags, for example:

```bash
git tag -a 1.0.0 -m "Release 1.0.0"
git push origin 1.0.0
```

Packagist consumes the GitHub repository.

## Local Consumer Install

For testing this package inside another local Laravel project:

```bash
composer config repositories.odinns-coding-style path ~/Projects/odinns/coding-style
composer require --dev 'odinns/coding-style:*@dev'
```

Then copy or merge the stubs:

```bash
cp vendor/odinns/coding-style/stubs/rector.php.stub rector.php
cp vendor/odinns/coding-style/stubs/phpstan.neon.stub phpstan.neon
```

If config files already exist in the consuming app, merge manually. Do not overwrite local project config blindly.

