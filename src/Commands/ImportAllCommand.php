<?php

namespace Mozex\ScoutBulkActions\Commands;

use Illuminate\Console\Command;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;

class ImportAllCommand extends Command
{
    use FindsSearchableModels;

    public $signature = 'scout:import-all
            {--c|chunk= : The number of records to import at a time (Defaults to configuration value: `scout.chunk.searchable`)}';

    public $description = 'Import all models into the search index.';

    public function handle(): int
    {

        return self::SUCCESS;
    }
}
