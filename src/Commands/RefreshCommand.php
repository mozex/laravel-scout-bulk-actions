<?php

namespace Mozex\ScoutBulkActions\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;

class RefreshCommand extends Command
{
    use ConfirmableTrait;
    use FindsSearchableModels;

    public $signature = 'scout:refresh
            {model? : Class name of model to bulk import}
            {--c|chunk= : The number of records to import at a time (Defaults to configuration value: `scout.chunk.searchable`)}
            {--force : Force the operation to run when in production}';

    public $description = 'Flush models then Import them into the search index.';

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $model = $this->argument('model');

        return $model
            ? $this->handleModel($model)
            : $this->handleAll();
    }

    protected function handleModel(string $model): int
    {
        $flushOutput = $this->call('scout:flush', ['model' => $model]);

        if ($flushOutput !== self::SUCCESS) {
            return $flushOutput;
        }

        $this->call('scout:import', array_filter([
            'model' => $model,
            '--chunk' => $this->option('chunk'),
        ]));

        return self::SUCCESS;
    }

    protected function handleAll(): int
    {
        $flushOutput = $this->call('scout:flush-all', ['--force' => true]);

        if ($flushOutput !== self::SUCCESS) {
            return $flushOutput;
        }

        $this->call('scout:import-all', array_filter([
            '--force' => true,
            '--chunk' => $this->option('chunk'),
        ]));

        return self::SUCCESS;
    }
}
