<?php

namespace Mozex\ScoutBulkActions\Commands;

use Illuminate\Console\Command;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;

class FlushAllCommand extends Command
{
    use FindsSearchableModels;

    public $signature = 'scout:flush-all';

    public $description = 'Flush the records of all models from the index.';

    public function handle(): int
    {

        return self::SUCCESS;
    }
}
