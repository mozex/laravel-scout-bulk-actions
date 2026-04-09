---
name: scout-bulk-actions
description: Work with Scout Bulk Actions for bulk importing, flushing, queue-importing, and refreshing all Laravel Scout searchable models at once. Use this skill when running bulk Scout index operations, configuring model discovery directories, setting up glob patterns for modular codebases, or troubleshooting why models aren't being discovered. Also activate when the user mentions scout:import-all, scout:flush-all, scout:queue-import-all, scout:refresh, or wants to re-index all searchable models without running commands one by one.
---

# Scout Bulk Actions

This package auto-discovers every Eloquent model that uses Scout's `Searchable` trait, then runs index operations across all of them in a single command. It wraps Scout's native `scout:import`, `scout:flush`, and `scout:queue-import` so you don't have to call each model individually.

## When to use this skill

Use this when:

- Importing, flushing, or re-indexing all searchable models at once
- Configuring which directories the package scans for models
- Setting up model discovery for modular Laravel apps with glob patterns
- Debugging why a model isn't being picked up by the bulk commands
- Choosing between synchronous and queued bulk imports

## Commands

### scout:import-all

Discovers all searchable models and imports each one into the search index.

```bash
php artisan scout:import-all
php artisan scout:import-all --chunk=500
php artisan scout:import-all --force
```

- `--chunk=N` (`-c N`): Records per batch. Falls back to `scout.chunk.searchable` config if omitted.
- `--force`: Skips the production confirmation prompt.

### scout:flush-all

Removes all records from the search index for every discovered model.

```bash
php artisan scout:flush-all
php artisan scout:flush-all --force
```

- `--force`: Skips the production confirmation prompt.

### scout:queue-import-all

Same as `scout:import-all`, but dispatches queued jobs instead of running synchronously. Use this for large datasets.

```bash
php artisan scout:queue-import-all
php artisan scout:queue-import-all --chunk=500 --queue=scout
```

- `--chunk=N` (`-c N`): Records per queued job. Falls back to `scout.chunk.searchable` config if omitted.
- `--queue=NAME`: Target queue name. Falls back to `scout.queue.queue` config if omitted.
- `--force`: Skips the production confirmation prompt.

### scout:refresh

Flushes then imports for a clean re-index. Works for one model or all of them.

```bash
# All models
php artisan scout:refresh

# Single model
php artisan scout:refresh "App\Models\Post"

# With chunk size
php artisan scout:refresh --chunk=500
```

- `model` (optional argument): Fully qualified class name. If omitted, it calls `scout:flush-all` then `scout:import-all` internally.
- `--chunk=N` (`-c N`): Passed through to the import step.
- `--force`: Skips the production confirmation prompt.

When refreshing all models, the command passes `--force` to the sub-commands automatically so you won't get prompted twice.

## Model Discovery

The package finds models by scanning directories listed in `config/scout-bulk-actions.php`. Publish the config:

```bash
php artisan vendor:publish --tag=scout-bulk-actions-config
```

### Configuration

```php
// config/scout-bulk-actions.php
return [
    'model_directories' => [
        app_path('Models'),
        // base_path('Modules/*/Models'),
    ],
];
```

Glob patterns work because the package uses Symfony Finder internally. For modular Laravel apps, add patterns like `base_path('Modules/*/Models')` to scan across all modules.

### How discovery works

For each `.php` file in the configured directories, the package:

1. Converts the file path to a fully qualified class name
2. Checks the class exists (autoloader can resolve it)
3. Verifies it's not abstract via `ReflectionClass`
4. Confirms it extends `Illuminate\Database\Eloquent\Model`
5. Confirms it uses `Laravel\Scout\Searchable` directly

All five checks must pass. A file that fails any check is silently skipped.

### Direct trait use only

This one trips people up. The `Searchable` trait must be applied directly on the model class itself. The package uses `class_uses()`, which only checks the class's own traits, not traits inherited from a parent.

So if you have a `BaseSearchableModel` that uses `Searchable` and child models extending it, those children won't be discovered. Each model needs its own `use Searchable` declaration.

## Behavior Details

**Production safety.** All commands prompt for confirmation when `APP_ENV=production`. Pass `--force` in CI/CD pipelines and automated deployments.

**Failure stops everything.** If any model fails during a bulk operation, the command stops immediately and returns exit code `1`. It won't attempt the remaining models.

**Refresh aborts on flush failure.** `scout:refresh` runs flush first. If flushing fails, import never runs. This applies to both single-model and all-model refreshes.

**Progress bars.** `scout:import-all`, `scout:flush-all`, and `scout:queue-import-all` display progress via Laravel Prompts. `scout:refresh` delegates to those commands, so their output shows through.
