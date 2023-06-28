<?php

namespace Mozex\ScoutBulkActions\Tests;

use Mozex\ScoutBulkActions\ScoutBulkActionsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ScoutBulkActionsServiceProvider::class,
        ];
    }
}
