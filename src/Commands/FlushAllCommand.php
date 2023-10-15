<?php

namespace Mozex\ScoutBulkActions\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;

use function Laravel\Prompts\error;
use function Laravel\Prompts\progress;

class FlushAllCommand extends Command
{
    use ConfirmableTrait;
    use FindsSearchableModels;

    public $signature = 'scout:flush-all {--force : Force the operation to run when in production}';

    public $description = 'Flush the records of all models from the index.';

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $models = $this->getSearchableModels();

        $progress = progress(
            label: 'Flushing records',
            steps: $models->count(),
        );

        foreach ($models as $model) {
            if (! $this->flushModel($model)) {
                return self::FAILURE;
            }

            $progress->advance();
        }

        $progress->finish();

        return self::SUCCESS;
    }

    protected function flushModel(string $model): bool
    {
        if ($this->callSilently('scout:flush', [
            'model' => $model,
        ])) {
            error(sprintf('Flushing [%s] has been failed.', $model));

            return false;
        }

        return true;
    }
}
