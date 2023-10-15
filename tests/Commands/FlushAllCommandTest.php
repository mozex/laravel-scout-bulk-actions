<?php

use Laravel\Scout\Console\FlushCommand;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;
use Mozex\ScoutBulkActions\Commands\FlushAllCommand;

uses(FindsSearchableModels::class);

it('will ask for confirmation if env is production', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:flush-all')
        ->expectsConfirmation(
            'Are you sure you want to run this command?',
        )
        ->doesntExpectOutput('Flushing started.')
        ->doesntExpectOutput('Flushing finished successfully.')
        ->assertFailed();
});

it('will continue when confirmation has been answered yes', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:flush-all')
        ->expectsConfirmation(
            'Are you sure you want to run this command?',
            'yes'
        )
        ->expectsOutput('Flushing started.')
        ->expectsOutput('Flushing finished successfully.')
        ->assertSuccessful();
});

it('will not ask for confirmation when it has force option', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:flush-all', ['--force' => true])
        ->expectsOutput('Flushing started.')
        ->expectsOutput('Flushing finished successfully.')
        ->assertSuccessful();
});

it('will call flush command for each model', function () {
    app()->setBasePath(dirname(__DIR__, 2));

    config()->set('scout-bulk-actions.model_directories', [
        base_path('tests/Fixtures'),
    ]);

    config()->set('scout-bulk-actions.namespace', 'Mozex\\ScoutBulkActions');

    $output = mockExpectedCommandWithModels(
        command: FlushAllCommand::class,
        expectedCommand: FlushCommand::class,
        models: $this->getSearchableModels()->toArray()
    );

    expect($output)->toEqual(0);
});
