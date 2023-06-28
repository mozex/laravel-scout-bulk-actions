<?php

namespace Mozex\ScoutBulkActions\Commands;

use Illuminate\Console\Command;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;

class RefreshCommand extends Command
{
    use FindsSearchableModels;

    public $signature = 'scout:refresh
            {model : Class name of model to bulk import}
            {--c|chunk= : The number of records to import at a time (Defaults to configuration value: `scout.chunk.searchable`)}';

    public $description = 'Import all models into the search index.';

    public function handle(): int
    {

        return self::SUCCESS;
    }
}
