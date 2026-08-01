<?php

namespace Mozex\ScoutBulkActions\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;

use function Laravel\Prompts\error;
use function Laravel\Prompts\progress;

class ImportAllCommand extends Command
{
    use ConfirmableTrait;
    use FindsSearchableModels;

    public $signature = 'scout:import-all
            {--c|chunk= : The number of records to import at a time (Defaults to configuration value: `scout.chunk.searchable`)}
            {--fresh : Flush each model from the index before importing it (Requires Laravel Scout 10.18+)}
            {--force : Force the operation to run when in production}';

    public $description = 'Import all models into the search index.';

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $models = $this->getSearchableModels();

        $progress = progress(
            label: 'Importing records',
            steps: $models->count(),
        );

        foreach ($models as $model) {
            if (! $this->importModel($model)) {
                return self::FAILURE;
            }

            $progress->advance();
        }

        $progress->finish();

        return self::SUCCESS;
    }

    protected function importModel(string $model): bool
    {
        if ($this->callSilently('scout:import', array_filter([
            'model' => $model,
            '--chunk' => $this->option('chunk'),
            '--fresh' => $this->option('fresh'),
        ]))) {
            error(sprintf('Importing [%s] has been failed.', $model));

            return false;
        }

        return true;
    }
}
