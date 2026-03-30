<?php

namespace Mozex\ScoutBulkActions\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;

use function Laravel\Prompts\error;
use function Laravel\Prompts\progress;

class QueueImportAllCommand extends Command
{
    use ConfirmableTrait;
    use FindsSearchableModels;

    public $signature = 'scout:queue-import-all
            {--c|chunk= : The number of records to queue in a single job (Defaults to configuration value: `scout.chunk.searchable`)}
            {--queue= : The queue that should be used (Defaults to configuration value: `scout.queue.queue`)}
            {--force : Force the operation to run when in production}';

    public $description = 'Queue import all models into the search index via chunked, queued jobs.';

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $models = $this->getSearchableModels();

        $progress = progress(
            label: 'Queuing records for import',
            steps: $models->count(),
        );

        foreach ($models as $model) {
            if (! $this->queueImportModel($model)) {
                return self::FAILURE;
            }

            $progress->advance();
        }

        $progress->finish();

        return self::SUCCESS;
    }

    protected function queueImportModel(string $model): bool
    {
        if ($this->callSilently('scout:queue-import', array_filter([
            'model' => $model,
            '--chunk' => $this->option('chunk'),
            '--queue' => $this->option('queue'),
        ]))) {
            error(sprintf('Queue importing [%s] has been failed.', $model));

            return false;
        }

        return true;
    }
}
