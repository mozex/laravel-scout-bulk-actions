<?php

namespace Mozex\ScoutBulkActions\Tests;

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
}
