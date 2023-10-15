<?php

use Laravel\Scout\Console\FlushCommand;
use Laravel\Scout\Console\ImportCommand;
use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;
use Mozex\ScoutBulkActions\Commands\FlushAllCommand;
use Mozex\ScoutBulkActions\Commands\ImportAllCommand;
use Mozex\ScoutBulkActions\Commands\RefreshCommand;
use Mozex\ScoutBulkActions\Tests\InputMatcher;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

uses(FindsSearchableModels::class);

it('will ask for confirmation if env is production', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:refresh')
        ->expectsConfirmation(
            'Are you sure you want to run this command?',
        )
        ->doesntExpectOutput('Flushing started.')
        ->doesntExpectOutput('Flushing finished successfully.')
        ->doesntExpectOutput('Importing started.')
        ->doesntExpectOutput('Importing finished successfully.')
        ->assertFailed();
});

it('will continue when confirmation has been answered yes', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:refresh')
        ->expectsConfirmation(
            'Are you sure you want to run this command?',
            'yes'
        )
        ->expectsOutput('Flushing started.')
        ->expectsOutput('Flushing finished successfully.')
        ->expectsOutput('Importing started.')
        ->expectsOutput('Importing finished successfully.')
        ->assertSuccessful();
});

it('will not ask for confirmation when it has force option', function (): void {
    app()['env'] = 'production';

    $this->artisan('scout:refresh', ['--force' => true])
        ->expectsOutput('Flushing started.')
        ->expectsOutput('Flushing finished successfully.')
        ->expectsOutput('Importing started.')
        ->expectsOutput('Importing finished successfully.')
        ->assertSuccessful();
});

it('will call flush all and import all command', function () {
    $refreshCommand = new RefreshCommand();
    $flushAllCommand = new FlushAllCommand();
    $importAllCommand = new ImportAllCommand();

    $console = Mockery::mock(ConsoleApplication::class)->makePartial();
    $console->__construct();
    $refreshCommand->setLaravel(app());
    $refreshCommand->setApplication($console);

    $mockedFlushAllCommand = Mockery::mock(FlushAllCommand::class);
    $mockedImportAllCommand = Mockery::mock(ImportAllCommand::class);

    $quote = DIRECTORY_SEPARATOR === '\\' ? '"' : "'";

    $console->shouldReceive('find')
        ->once()
        ->with($flushAllCommand->getName())
        ->andReturn($mockedFlushAllCommand);

    $mockedFlushAllCommand->shouldReceive('run')
        ->once()
        ->with(
            new InputMatcher('--force=1 '.$quote.$flushAllCommand->getName().$quote),
            Mockery::any(),
        );

    $console->shouldReceive('find')
        ->once()
        ->with($importAllCommand->getName())
        ->andReturn($mockedImportAllCommand);

    $mockedImportAllCommand->shouldReceive('run')
        ->once()
        ->with(
            new InputMatcher('--force=1 '.$quote.$importAllCommand->getName().$quote),
            Mockery::any(),
        );

    expect($refreshCommand->run(new ArrayInput([]), new NullOutput))->toEqual(0);
});

it('will call flush and import command if model is specified', function () {
    $refreshCommand = new RefreshCommand();
    $flushCommand = new FlushCommand();
    $importCommand = new ImportCommand();

    $console = Mockery::mock(ConsoleApplication::class)->makePartial();
    $console->__construct();
    $refreshCommand->setLaravel(app());
    $refreshCommand->setApplication($console);

    $mockedFlushCommand = Mockery::mock(FlushCommand::class);
    $mockedImportCommand = Mockery::mock(ImportCommand::class);

    $quote = DIRECTORY_SEPARATOR === '\\' ? '"' : "'";

    $console->shouldReceive('find')
        ->once()
        ->with($flushCommand->getName())
        ->andReturn($mockedFlushCommand);

    $mockedFlushCommand->shouldReceive('run')
        ->once()
        ->with(
            new InputMatcher("{$quote}App\Models\User{$quote} ".$quote.$flushCommand->getName().$quote),
            Mockery::any(),
        );

    $console->shouldReceive('find')
        ->once()
        ->with($importCommand->getName())
        ->andReturn($mockedImportCommand);

    $mockedImportCommand->shouldReceive('run')
        ->once()
        ->with(
            new InputMatcher("{$quote}App\Models\User{$quote} ".$quote.$importCommand->getName().$quote),
            Mockery::any(),
        );

    expect($refreshCommand->run(new ArrayInput(['model' => 'App\Models\User']), new NullOutput))->toEqual(0);
});

it('will not call import all if flush all fails', function () {
    $refreshCommand = new RefreshCommand();
    $flushAllCommand = new FlushAllCommand();
    $importAllCommand = new ImportAllCommand();

    $console = Mockery::mock(ConsoleApplication::class)->makePartial();
    $console->__construct();
    $refreshCommand->setLaravel(app());
    $refreshCommand->setApplication($console);

    $mockedFlushAllCommand = Mockery::mock(FlushAllCommand::class);
    $mockedImportAllCommand = Mockery::mock(ImportAllCommand::class);

    $quote = DIRECTORY_SEPARATOR === '\\' ? '"' : "'";

    $console->shouldReceive('find')
        ->once()
        ->with($flushAllCommand->getName())
        ->andReturn($mockedFlushAllCommand);

    $mockedFlushAllCommand->shouldReceive('run')
        ->once()
        ->with(
            new InputMatcher('--force=1 '.$quote.$flushAllCommand->getName().$quote),
            Mockery::any(),
        )
        ->andReturn(1);

    $console->shouldNotReceive('find')
        ->with($importAllCommand->getName());

    $mockedImportAllCommand->shouldNotReceive('run')
        ->with(
            new InputMatcher('--force=1 '.$quote.$importAllCommand->getName().$quote),
            Mockery::any(),
        );

    expect($refreshCommand->run(new ArrayInput([]), new NullOutput))->toEqual(1);
});

it('will not call import if flush fails while model is specified', function () {
    $refreshCommand = new RefreshCommand();
    $flushCommand = new FlushCommand();
    $importCommand = new ImportCommand();

    $console = Mockery::mock(ConsoleApplication::class)->makePartial();
    $console->__construct();
    $refreshCommand->setLaravel(app());
    $refreshCommand->setApplication($console);

    $mockedFlushCommand = Mockery::mock(FlushCommand::class);
    $mockedImportCommand = Mockery::mock(ImportCommand::class);

    $quote = DIRECTORY_SEPARATOR === '\\' ? '"' : "'";

    $console->shouldReceive('find')
        ->once()
        ->with($flushCommand->getName())
        ->andReturn($mockedFlushCommand);

    $mockedFlushCommand->shouldReceive('run')
        ->once()
        ->with(
            new InputMatcher("{$quote}App\Models\User{$quote} ".$quote.$flushCommand->getName().$quote),
            Mockery::any(),
        )
        ->andReturn(1);

    $console->shouldNotReceive('find')
        ->with($importCommand->getName());

    $mockedImportCommand->shouldNotReceive('run')
        ->with(
            new InputMatcher("{$quote}App\Models\User{$quote} ".$quote.$importCommand->getName().$quote),
            Mockery::any(),
        );

    expect($refreshCommand->run(new ArrayInput(['model' => 'App\Models\User']), new NullOutput))->toEqual(1);
});
