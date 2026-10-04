# Changelog

All notable changes to `laravel-scout-bulk-actions` will be documented in this file.

## 1.8.1 - 2026-10-04

### What's Changed

* Improve package setup

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.8.0...1.8.1

## 1.8.0 - 2026-08-01

### What's Changed

* Add `--fresh` option to `scout:import-all` for a per-model flush before each import
* Add `--order` option to `scout:queue-import-all` to control the direction ranges are queued in
* Bump minimum `laravel/scout` requirement from `^10.17` to `^10.18`

`scout:import-all --fresh` forwards Scout's own `--fresh` flag, flushing each model from the index immediately before that model is imported. This is not the same as `scout:refresh`, which empties every index first and only then starts importing. With `--fresh`, a model is missing from the index for the length of its own import and no longer, so the rest of your search keeps serving results while the rebuild runs.

`scout:queue-import-all --order=desc` queues each model's ID range from the highest ID downward, so recent records are indexed early in a long rebuild instead of last. This forwards the `--order` option added in Laravel Scout 11.4 and requires that version. The value is validated as `asc` or `desc` before any job is dispatched, because Scout reports an invalid order with a zero exit code.

The `laravel/scout` floor moved to `^10.18` because `scout:import` gained `--fresh` in that release. 10.17.x was the only version in the previous range without it.

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.7.0...1.8.0

## 1.7.0 - 2026-04-22

* Add Laravel Scout 11 support

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.6.0...1.7.0

## 1.6.0 - 2026-04-09

### What's Changed

* Add [Laravel Boost](https://laravel.com/docs/13.x/boost) skill for AI-assisted development

AI coding assistants can now load context about this package on-demand through Laravel Boost. The skill covers all four bulk commands, model discovery configuration, glob pattern setup for modular apps, and common pitfalls like the direct `Searchable` trait use requirement. Run `php artisan boost:update` after upgrading to pick it up.

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.5.2...1.6.0

## 1.5.2 - 2026-03-30

* update composer.json

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.5.1...1.5.2

## 1.5.1 - 2026-03-30

* update composer.json

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.5.0...1.5.1

## 1.5.0 - 2026-03-30

### What's Changed

* Add `scout:queue-import-all` command for queued bulk imports across all searchable models
* Bump minimum `laravel/scout` requirement from `^10.12.2` to `^10.17`
* Rewrite README documentation

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.4.0...1.5.0

## 1.4.0 - 2026-03-18

* add laravel 13 support

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.3.1...1.4.0

## 1.3.1 - 2026-03-04

### What's Changed

* bump dependencies
* drop php 8.1 support
* drop laravel 10 support

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.3.0...1.3.1

## 1.3.0 - 2025-02-25

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/mozex/laravel-scout-bulk-actions/pull/13
* Add Laravel 12 compatibility by @mozex in https://github.com/mozex/laravel-scout-bulk-actions/pull/14

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.2.4...1.3.0

## 1.2.4 - 2024-10-08

### What's Changed

* Update laravel/prompts requirement from ^0.1.15|^0.2.0 to ^0.1.15|^0.2.0|^0.3.0

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.2.3...1.2.4

## 1.2.3 - 2024-09-28

- Fix `laravel/prompts` dependency

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.2.2...1.2.3

## 1.2.2 - 2024-09-28

### What's Changed

* Update laravel/prompts requirement from ^0.1.11 to ^0.2.0 by @dependabot in https://github.com/mozex/laravel-scout-bulk-actions/pull/11

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.2.1...1.2.2

## 1.2.1 - 2024-08-15

### What's Changed

* Bump dependencies

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.2.0...1.2.1

## 1.2.0 - 2024-03-01

### What's Changed

* bump versions
* add Laravel 11 support

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.1.0...1.2.0

## 1.1.0 - 2023-10-15

### What's Changed

- Add support for Laravel Prompts
- Improve tests
- Bump dependency versions
- Refactoring

**Full Changelog**: https://github.com/mozex/laravel-scout-bulk-actions/compare/1.0.2...1.1.0

## 1.0.2 - 2023-07-01

- Refactoring

## 1.0.1 - 2023-06-30

- Code Styling

## 1.0.0 - 2023-06-29

- Initial Release
