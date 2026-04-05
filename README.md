# Laravel Scout Bulk Actions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mozex/laravel-scout-bulk-actions.svg?style=flat-square)](https://packagist.org/packages/mozex/laravel-scout-bulk-actions)
[![GitHub Tests Workflow Status](https://img.shields.io/github/actions/workflow/status/mozex/laravel-scout-bulk-actions/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mozex/laravel-scout-bulk-actions/actions/workflows/tests.yml)
[![License](https://img.shields.io/github/license/mozex/laravel-scout-bulk-actions.svg?style=flat-square)](https://packagist.org/packages/mozex/laravel-scout-bulk-actions)
[![Total Downloads](https://img.shields.io/packagist/dt/mozex/laravel-scout-bulk-actions.svg?style=flat-square)](https://packagist.org/packages/mozex/laravel-scout-bulk-actions)

Laravel Scout's built-in commands work on one model at a time. If your project has ten searchable models, that's ten separate `scout:import` calls every time you need to rebuild your indexes. This package fixes that.

Scout Bulk Actions auto-discovers every model that uses the `Searchable` trait and lets you import, flush, or refresh all of them with a single command. It also supports queued bulk imports for projects where synchronous indexing is too slow.

## Support This Project

I maintain this package along with [several other open-source PHP packages](https://github.com/mozex?tab=repositories&q=&type=source) used by thousands of developers every day.

If my packages save you time or help your business, consider [**sponsoring my work on GitHub Sponsors**](https://github.com/sponsors/mozex). Your support lets me keep these packages updated, respond to issues quickly, and ship new features.

Business sponsors get logo placement in package READMEs. [**See sponsorship tiers →**](https://github.com/sponsors/mozex)

## Installation

```bash
composer require mozex/laravel-scout-bulk-actions
```

That's it. The package auto-registers its service provider via Laravel's package discovery.

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag="scout-bulk-actions-config"
```

This creates `config/scout-bulk-actions.php`:

```php
return [
    'model_directories' => [
        app_path('Models'),
        // base_path('Modules/*/Models'),
    ],
];
```

The `model_directories` array tells the package where to look for your searchable models. By default it scans `app/Models`, which covers most Laravel projects.

Glob patterns work here too. If you're using a modular architecture, something like `base_path('Modules/*/Models')` will scan the `Models` directory inside every module at once.

After changing the config in production, run `php artisan config:clear` to pick up the new values.

## Commands

### `scout:import-all`

Imports all discovered searchable models into the search index:

```bash
php artisan scout:import-all
```

You can control the chunk size (how many records are sent per batch) with the `--chunk` option:

```bash
php artisan scout:import-all --chunk=200
```

If omitted, it falls back to the `scout.chunk.searchable` config value.

### `scout:flush-all`

Removes all records from the search index for every discovered model:

```bash
php artisan scout:flush-all
```

### `scout:queue-import-all`

For large datasets, synchronous imports can be slow. This command dispatches queued jobs that split each model's records into chunks by ID range, the same approach Scout's native `scout:queue-import` uses, but applied across all your models at once:

```bash
php artisan scout:queue-import-all
```

Options:

```bash
# Set the chunk size per job
php artisan scout:queue-import-all --chunk=500

# Specify which queue to dispatch jobs to
php artisan scout:queue-import-all --queue=indexing
```

This is the fastest way to rebuild indexes for projects with many models and millions of rows.

### `scout:refresh`

Flushes then imports, in one step. Useful when you need a clean re-index:

```bash
php artisan scout:refresh
```

You can also target a single model:

```bash
php artisan scout:refresh "App\Models\Post"
```

When no model is specified, it runs `scout:flush-all` followed by `scout:import-all` under the hood. The `--chunk` option works here too.

### Production Safety

All commands ask for confirmation when `APP_ENV` is `production`. To skip the prompt (for CI pipelines or automated scripts), pass `--force`:

```bash
php artisan scout:import-all --force
php artisan scout:flush-all --force
php artisan scout:queue-import-all --force
php artisan scout:refresh --force
```

## How It Works

The package scans the directories you've configured using Symfony's Finder component. For each PHP file it finds, it:

1. Converts the file path to a fully qualified class name.
2. Checks via Reflection that the class is a concrete (non-abstract) Eloquent model.
3. Verifies the class uses Laravel Scout's `Searchable` trait.

Models that pass all three checks are collected, and the chosen Scout command (`scout:import`, `scout:flush`, or `scout:queue-import`) runs against each one. A progress bar tracks the operation so you can see where things stand.

If any single model fails during the operation, the command stops immediately and returns a failure exit code.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mozex](https://github.com/mozex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
