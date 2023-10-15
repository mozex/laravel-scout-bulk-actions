<?php

namespace Mozex\ScoutBulkActions\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;

class ImportAllCommand extends Command
{
    use ConfirmableTrait;
    use FindsSearchableModels;

    public $signature = 'scout:import-all
            {--c|chunk= : The number of records to import at a time (Defaults to configuration value: `scout.chunk.searchable`)}
            {--force : Force the operation to run when in production}';

    public $description = 'Import all models into the search index.';

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $this->info('Importing started.');

        $models = $this->getSearchableModels();

        $bar = $this->output->createProgressBar($models->count());

        foreach ($models as $model) {
            if (! $this->importModel($model)) {
                return self::FAILURE;
            }

            $bar->advance();
        }

        $this->newLine();
        $this->info('Importing finished successfully.');

        return self::SUCCESS;
    }

    protected function importModel(string $model): bool
    {
        if ($this->callSilently('scout:import', array_filter([
            'model' => $model,
            '--chunk' => $this->option('chunk'),
        ]))) {
            $this->newLine();

            $this->error(sprintf('Importing [%s] has been failed.', $model));

            if (! $this->confirm('Do you want to continue?')) {
                $this->newLine();

                return false;
            }
        }

        return true;
    }
}
