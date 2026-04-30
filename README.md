# Odinns Coding Style

Shared Rector and PHPStan defaults for Laravel projects.

This package gives Laravel apps a small, boring way to share static-analysis and automated-refactoring rules. The package owns the defaults. Each application owns its paths, baselines, ignore rules, and local skips.

That split matters. Shared config should describe current taste. Local config should describe local history.

## Requirements

- PHP `^8.3`
- Composer 2
- Rector 2
- PHPStan 2
- Laravel 11, 12, or 13 through Larastan support

## Installation

Install the published package as a development dependency:

```bash
composer require --dev odinns/coding-style
```

The package installs Rector, PHPStan, Larastan, and the supported PHPStan extensions it configures.

### Local Path Install

For local development, install this checkout into another Laravel project as a Composer path repository:

```bash
composer config repositories.odinns-coding-style path ~/Projects/odinns/coding-style
composer require --dev 'odinns/coding-style:*@dev'
```

Then copy the starter config files:

```bash
cp vendor/odinns/coding-style/stubs/rector.php.stub rector.php
cp vendor/odinns/coding-style/stubs/phpstan.neon.stub phpstan.neon
```

If the consuming project already has `rector.php` or `phpstan.neon`, merge the package calls into the existing files instead of overwriting them.

## Rector

Create a `rector.php` file in the consuming project:

```php
<?php

declare(strict_types=1);

use Odinns\CodingStyle\OdinnsRectorConfig;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    OdinnsRectorConfig::setup($rectorConfig);

    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);
};
```

Then add Composer scripts:

```json
{
  "scripts": {
    "refactor": "rector process --ansi",
    "test:refactor": "rector process --dry-run --ansi"
  }
}
```

Run Rector in check mode:

```bash
composer test:refactor
```

Apply changes:

```bash
composer refactor
```

### Local Rector skips

Keep skips in the consuming project:

```php
return static function (RectorConfig $rectorConfig): void {
    OdinnsRectorConfig::setup($rectorConfig);

    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->skip([
        __DIR__ . '/app/Legacy',
        SomeProjectSpecificRector::class,
    ]);
};
```

Do not upstream local skip lists into this package. A skip caused by one app's history is not a coding standard.

## PHPStan and Larastan

Create a `phpstan.neon` file in the consuming project:

```neon
includes:
  - vendor/odinns/coding-style/config/larastan.neon

parameters:
  paths:
    - app
    - bootstrap
    - config
    - database
    - routes
    - tests

  level: 5
```

Then add Composer scripts:

```json
{
  "scripts": {
    "test:types": "phpstan analyse --memory-limit=2G",
    "test:types:baseline": "phpstan analyse --generate-baseline --memory-limit=2G"
  }
}
```

Run PHPStan:

```bash
composer test:types
```

### Baselines

Baselines belong to the consuming project:

```neon
includes:
  - vendor/odinns/coding-style/config/larastan.neon
  - phpstan-baseline.neon

parameters:
  paths:
    - app
    - tests

  level: 5
```

Generate a baseline:

```bash
composer test:types:baseline
```

Baselines are debt registers. Keep them local and shrink them when the code changes nearby.

### Local ignores

Project-specific ignores also stay local:

```neon
includes:
  - vendor/odinns/coding-style/config/larastan.neon

parameters:
  paths:
    - app
    - tests

  level: 5

  ignoreErrors:
    -
      identifier: some.local.identifier
      path: app/Legacy/OldThing.php
```

If an ignore only makes sense in one app, it does not belong in this package.

## Included Defaults

### Rector

The shared Rector setup enables conservative automated improvements:

- dead-code cleanup
- code-quality cleanup
- type declaration improvements
- safe privatization
- early returns
- import cleanup
- required `declare(strict_types=1);` declarations
- PHP 8.3 set support
- Laravel relation generic return types
- Laravel model casts method migration
- Laravel validation rule array conversion

The package automatically uses the consuming project's `phpstan.neon` when it exists, falling back to `phpstan.neon.dist`. It also loads Larastan's bootstrap file when the consuming project has one installed.

### PHPStan

The base PHPStan config enables:

- deprecation rules
- Mockery support
- stricter return type checks
- missing override attribute checks
- safer array-offset reporting
- less overconfident PHPDoc treatment

The Larastan config adds:

- Larastan
- model property checks
- Octane compatibility checks

## Stubs

Copy-ready stubs are included:

```text
vendor/odinns/coding-style/stubs/rector.php.stub
vendor/odinns/coding-style/stubs/phpstan.neon.stub
```

They are deliberately plain files. No generator command. No plugin. No theatrical machinery.

## Update Strategy

Use normal Composer updates:

```bash
composer update odinns/coding-style --with-dependencies
```

After updating, run:

```bash
composer test:refactor
composer test:types
```

If Rector proposes a large change set, review it as a normal refactor. Automated does not mean harmless. It means repeatable.

## Design Principles

- Shared defaults should be stable and boring.
- Local paths stay local.
- Local skips stay local.
- Local baselines stay local.
- Rules should remove noise, not rewrite taste every Tuesday.
- The package should be easy to delete from a project if it stops earning its keep.

## What This Package Does Not Do

- It does not publish Laravel config.
- It does not register a service provider.
- It does not add migrations, routes, views, or commands.
- It does not own application-specific baselines.
- It does not own application-specific Rector skips.
- It does not try to format code. Use a formatter separately.

## Testing With Pest

The package test suite uses Pest.

Add tests under `tests/Unit`:

```php
<?php

declare(strict_types=1);

it('describes behavior clearly', function (): void {
    expect(true)->toBeTrue();
});
```

Run the suite:

```bash
composer test
```

## Developing This Package

Install dependencies:

```bash
composer install
```

Run all checks:

```bash
composer test:all
```

Run checks separately:

```bash
composer validate --strict
composer test:types
composer test:refactor
composer test
```

## Contributing

Keep changes small and explain the behavior change. Add shared rules only when they are broadly useful across Laravel projects.

Do not add one application's skip list, baseline, or legacy exception to this package. That path leads to a swamp with invoices.

## Security

If you discover a security issue, report it privately to the maintainer instead of opening a public issue.

## License

The MIT License. See [LICENSE](LICENSE).
