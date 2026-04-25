# Contributing

Keep this package small, public-safe, and boring in the useful way.

## What Belongs Here

- Shared Rector defaults that make sense across Laravel projects.
- Shared PHPStan or Larastan config that reduces repeat setup.
- Tests that prove the package still wires those defaults correctly.
- Documentation that helps a consuming project install and use the package without guesswork.

## What Does Not Belong Here

- One application's Rector skips.
- One application's PHPStan baseline.
- Legacy ignores copied from a private project.
- Client, employer, or source-project names.
- Laravel runtime furniture: no service providers, published config, migrations, routes, views, commands, or app integration.

Local history stays local. Shared defaults should describe current taste, not old archaeology.

## Development

Install dependencies:

```bash
composer install
```

Run the full gate before opening a pull request:

```bash
composer test:all
```

That runs Composer validation, PHPStan, Rector dry-run, and Pest.

## Pull Requests

Keep pull requests focused. Explain the behavior change and why it belongs in a shared package.

If a rule is useful only because one project is messy, it does not belong here. Fix it locally, baseline it locally, or leave it alone until it earns a shared rule.
