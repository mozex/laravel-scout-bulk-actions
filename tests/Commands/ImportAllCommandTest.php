<?php

use Laravel\Scout\Console\ImportCommand;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;
use Mozex\ScoutBulkActions\Commands\ImportAllCommand;

uses(FindsSearchableModels::class);

it('will ask for confirmation if env is production', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:import-all')
        ->expectsConfirmation(
            'Are you sure you want to run this command?',
        )->assertFailed();
});

it('will continue when confirmation has been answered yes', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:import-all')
        ->expectsConfirmation(
            'Are you sure you want to run this command?',
            'yes'
        );
})->expectExceptionMessage('Progress bar must have at least one item.');

it('will not ask for confirmation when it has force option', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:import-all', ['--force' => true]);
})->expectExceptionMessage('Progress bar must have at least one item.');

it('will call import command for each model', function () {
    app()->setBasePath(dirname(__DIR__, 2));

    config()->set('scout-bulk-actions.model_directories', [
        base_path('tests/Fixtures'),
    ]);

    config()->set('scout-bulk-actions.namespace', 'Mozex\\ScoutBulkActions');

    $output = mockExpectedCommandWithModels(
        command: ImportAllCommand::class,
        expectedCommand: ImportCommand::class,
        models: $this->getSearchableModels()->toArray()
    );

    expect($output)->toEqual(0);
});
