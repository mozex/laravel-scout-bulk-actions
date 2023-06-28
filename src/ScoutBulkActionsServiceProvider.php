<?php

namespace Mozex\ScoutBulkActions;

use Mozex\ScoutBulkActions\Commands\FlushAllCommand;
use Mozex\ScoutBulkActions\Commands\ImportAllCommand;
use Mozex\ScoutBulkActions\Commands\RefreshCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ScoutBulkActionsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-scout-bulk-actions')
            ->hasConfigFile()
            ->hasCommands([
                FlushAllCommand::class,
                ImportAllCommand::class,
                RefreshCommand::class,
            ]);
    }
}
