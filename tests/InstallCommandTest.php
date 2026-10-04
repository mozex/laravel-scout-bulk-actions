<?php

use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;

const STAR_QUESTION = 'Would you like to show some love by starring laravel-scout-bulk-actions on GitHub?';

beforeEach(function (): void {
    Process::fake();

    @unlink($this->app->configPath('scout-bulk-actions.php'));
});

afterEach(function (): void {
    @unlink($this->app->configPath('scout-bulk-actions.php'));
});

function assertOpenedRepository(): void
{
    Process::assertRan(
        fn (PendingProcess $process): bool => in_array('https://github.com/mozex/laravel-scout-bulk-actions', (array) $process->command, true)
    );
}

it('publishes the config file', function (): void {
    $this->artisan('scout-bulk-actions:install')
        ->expectsConfirmation(STAR_QUESTION, 'no')
        ->assertSuccessful();

    expect(file_get_contents($this->app->configPath('scout-bulk-actions.php')))
        ->toBe(file_get_contents(__DIR__.'/../config/scout-bulk-actions.php'));
});

it('leaves an existing config file alone', function (): void {
    file_put_contents($this->app->configPath('scout-bulk-actions.php'), '<?php return [];');

    $this->artisan('scout-bulk-actions:install')
        ->expectsConfirmation(STAR_QUESTION, 'no')
        ->assertSuccessful();

    expect(file_get_contents($this->app->configPath('scout-bulk-actions.php')))->toBe('<?php return [];');
});

it('opens the repository when the user agrees to star it', function (): void {
    $this->artisan('scout-bulk-actions:install')
        ->expectsConfirmation(STAR_QUESTION, 'yes')
        ->doesntExpectOutputToContain('please consider starring')
        ->doesntExpectOutputToContain("You'll find")
        ->assertSuccessful();

    assertOpenedRepository();
});

it('opens nothing when the user declines', function (): void {
    $this->artisan('scout-bulk-actions:install')
        ->expectsConfirmation(STAR_QUESTION, 'no')
        ->assertSuccessful();

    Process::assertNothingRan();
});

it('prints the repository link when the browser cannot be opened', function (): void {
    Process::fake(['*' => Process::result(exitCode: 1)]);

    $this->artisan('scout-bulk-actions:install')
        ->expectsConfirmation(STAR_QUESTION, 'yes')
        ->expectsOutputToContain("You'll find laravel-scout-bulk-actions at https://github.com/mozex/laravel-scout-bulk-actions")
        ->assertSuccessful();
});

it('prints the repository link when opening the browser throws', function (): void {
    Process::fake(fn () => throw new RuntimeException('No opener.'));

    $this->artisan('scout-bulk-actions:install')
        ->expectsConfirmation(STAR_QUESTION, 'yes')
        ->expectsOutputToContain("You'll find laravel-scout-bulk-actions at https://github.com/mozex/laravel-scout-bulk-actions")
        ->assertSuccessful();
});

it('opens the repository with a note instead of asking when nobody can answer', function (): void {
    $this->artisan('scout-bulk-actions:install', ['--no-interaction' => true])
        ->expectsOutputToContain('If laravel-scout-bulk-actions saves you time, please consider starring it on GitHub: https://github.com/mozex/laravel-scout-bulk-actions')
        ->assertSuccessful();

    assertOpenedRepository();

    expect($this->app->configPath('scout-bulk-actions.php'))->toBeFile();
});
