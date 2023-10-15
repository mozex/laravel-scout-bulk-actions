<?php

namespace Mozex\ScoutBulkActions\Tests;

use Laravel\Prompts\Prompt;
use Mozex\ScoutBulkActions\ScoutBulkActionsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ScoutBulkActionsServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Prompt::fallbackWhen(true);
    }
}
