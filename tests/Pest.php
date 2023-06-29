<?php

use Mozex\ScoutBulkActions\Tests\InputMatcher;
use Mozex\ScoutBulkActions\Tests\TestCase;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

uses(TestCase::class)->in(__DIR__);

function mockExpectedCommandWithModels(string $command, string $expectedCommand, array $models): int
{
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
                new InputMatcher($quote.$model.$quote.' '.$quote.$expectedCommandInstance->getName().$quote),
                Mockery::any(),
            );
    }

    return $commandInstance->run(new ArrayInput([]), new NullOutput);
}
