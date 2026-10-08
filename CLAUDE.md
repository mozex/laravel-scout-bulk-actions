# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel Scout Bulk Actions (`mozex/laravel-scout-bulk-actions`) — a Laravel package that provides Artisan commands for bulk importing, flushing, and refreshing all Laravel Scout searchable models at once.

## Commands

```bash
# Run all checks (formatting + static analysis + tests)
composer test

# Individual checks
composer lint            # Fix code formatting (Pint)
composer test:lint       # Check code formatting without fixing
composer test:types      # PHPStan static analysis (level 6)
composer test:unit       # Run Pest tests

# Run a single test file
./vendor/bin/pest tests/Commands/ImportAllCommandTest.php

# Run a single test by name
./vendor/bin/pest --filter="test name here"
```

## Architecture

Three Artisan commands share model-discovery logic via the `FindsSearchableModels` trait:

- **`scout:import-all`** (`ImportAllCommand`) — Discovers all searchable models and calls `scout:import` on each. Supports `--chunk` and `--force`.
- **`scout:flush-all`** (`FlushAllCommand`) — Discovers all searchable models and calls `scout:flush` on each. Supports `--force`.
- **`scout:refresh`** (`RefreshCommand`) — Flushes then imports. Accepts an optional `{model}` argument for single-model refresh, otherwise delegates to the above two commands.

**Model Discovery** (`FindsSearchableModels` trait): Scans directories configured in `config/scout-bulk-actions.php` using Symfony Finder with glob pattern support. Validates each file via Reflection — must be a non-abstract Eloquent Model using Scout's `Searchable` trait.

**Command Chaining**: `RefreshCommand` calls `FlushAllCommand`/`ImportAllCommand` as sub-commands. All commands exit early on failure.

**Safety**: Commands use `ConfirmableTrait` for production confirmation, bypassed with `--force`.

**`scout-bulk-actions:install` is spatie's `hasInstallCommand()`; the GitHub star question is ours** (`ScoutBulkActionsServiceProvider::askToStar()`, wired through `endWith()`), because spatie's `askToStarRepoOnGitHub()` has fixed generic wording and opens the browser through `exec()`, which a test can't fake. The question names the package and defaults to yes. A run nobody can answer (`--no-interaction`, or no terminal on stdin, as with CI and AI agents) skips the question, takes that default, and prints a star note so the browser tab is explained. `isInteractive()` mirrors Laravel's own prompt rule, including treating unit tests as interactive, which keeps the tests deterministic. The question goes through `$command->confirm()`, never Laravel Prompts' `confirm()`: spatie publishes with `callSilently('vendor:publish')`, and that nested command reconfigures Prompts' global output to a `NullOutput`, so a Prompts question afterwards is invisible while it still waits for Enter. The faked console in tests hides this. The browser opens through the `Process` facade, so the tests fake it on every OS. Opening is best effort: on Linux, `xdg-open` is backgrounded through `sh`, because without a detected desktop it runs the browser in the foreground, which would hold the command until the browser closed and then trip the 60-second process timeout. Any failure on the interactive path prints the URL instead.

## Testing

- **Framework**: Pest (supports v3 and v4)
- **Laravel integration**: Orchestra Testbench
- **Mocking**: Mockery for Console Application and sub-command calls; custom `InputMatcher` for verifying command arguments
- **Prompts**: Tests call `Prompt::fallbackWhen(true)` to disable interactive prompts
- **Architecture tests**: `ArchTest.php` prevents debug functions (`dd`, `dump`, `ray`, `sleep`)
- **Fixtures**: `tests/Fixtures/` contains searchable/non-searchable model stubs for discovery testing

## CI Matrix

PHP 8.2–8.5 × Laravel 12–13 × Pest 3–4 (with prefer-lowest and prefer-stable).
