<?php

namespace Mozex\ScoutBulkActions\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;

class FlushAllCommand extends Command
{
    use FindsSearchableModels;
    use ConfirmableTrait;

    public $signature = 'scout:flush-all {--force : Force the operation to run when in production}';

    public $description = 'Flush the records of all models from the index.';

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $this->info('Flushing started.');

        $models = $this->getSearchableModels();

        $bar = $this->output->createProgressBar($models->count());

        foreach ($models as $model) {
            if (! $this->flushModel($model)) {
                return self::FAILURE;
            }

            $bar->advance();
        }

        $this->newLine();
        $this->info('Flushing finished successfully.');

        return self::SUCCESS;
    }

    protected function flushModel(string $model): bool
    {
        if ($this->callSilently('scout:flush', [
            'model' => $model,
        ])) {
            $this->newLine();

            $this->error(sprintf('Flushing [%s] has been failed.', $model));

            if (! $this->confirm('Do you want to continue?')) {
                $this->newLine();

                return false;
            }
        }

        return true;
    }
}
