<?php

use Mozex\ScoutBulkActions\Tests\InputMatcher;
use Mozex\ScoutBulkActions\Tests\TestCase;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

uses(TestCase::class)->in(__DIR__);

/**
 * @param  array<int, string>  $models
 * @param  array<string, mixed>  $input  Options passed to the bulk command being run.
 * @param  array<int, string>  $expectedOptions  Options expected to reach the wrapped Scout command.
 */
function mockExpectedCommandWithModels(
    string $command,
    string $expectedCommand,
    array $models,
    array $input = [],
    array $expectedOptions = [],
): int {
    $commandInstance = new $command;
    $expectedCommandInstance = new $expectedCommand;

    $console = Mockery::mock(ConsoleApplication::class)->makePartial();
    $console->__construct();
    $commandInstance->setLaravel(app());
    $commandInstance->setApplication($console);

    $mockedExpectedCommand = Mockery::mock($expectedCommand);

    $quote = DIRECTORY_SEPARATOR === '\\' ? '"' : "'";

    foreach ($models as $model) {
        $console->shouldReceive('find')
            ->once()
            ->with($expectedCommandInstance->getName())
            ->andReturn($mockedExpectedCommand);

        $mockedExpectedCommand->shouldReceive('run')
            ->once()
            ->with(
                new InputMatcher(
                    collect([$quote.$model.$quote])
                        ->merge($expectedOptions)
                        ->push($quote.$expectedCommandInstance->getName().$quote)
                        ->implode(' ')
                ),
                Mockery::any(),
            );
    }

    return $commandInstance->run(new ArrayInput($input), new NullOutput);
}
