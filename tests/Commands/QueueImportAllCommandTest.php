<?php

use Laravel\Scout\Console\QueueImportCommand;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;
use Mozex\ScoutBulkActions\Commands\QueueImportAllCommand;

uses(FindsSearchableModels::class);

it('will ask for confirmation if env is production', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:queue-import-all')
        ->expectsConfirmation(
            'Are you sure you want to run this command?',
        )->assertFailed();
});

it('will continue when confirmation has been answered yes', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:queue-import-all')
        ->expectsConfirmation(
            'Are you sure you want to run this command?',
            'yes'
        );
})->throws(InvalidArgumentException::class, 'Progress bar must have at least one item.');

it('will not ask for confirmation when it has force option', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:queue-import-all', ['--force' => true]);
})->throws(InvalidArgumentException::class, 'Progress bar must have at least one item.');

it('will call queue import command for each model', function () {
    app()->setBasePath(dirname(__DIR__, 2));

    config()->set('scout-bulk-actions.model_directories', [
        base_path('tests/Fixtures'),
    ]);

    config()->set('scout-bulk-actions.namespace', 'Mozex\\ScoutBulkActions');

    $output = mockExpectedCommandWithModels(
        command: QueueImportAllCommand::class,
        expectedCommand: QueueImportCommand::class,
        models: $this->getSearchableModels()->toArray()
    );

    expect($output)->toEqual(0);
});
