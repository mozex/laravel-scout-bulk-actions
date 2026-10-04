<?php

namespace Mozex\ScoutBulkActions;

use Illuminate\Support\Facades\Process;
use Mozex\ScoutBulkActions\Commands\FlushAllCommand;
use Mozex\ScoutBulkActions\Commands\ImportAllCommand;
use Mozex\ScoutBulkActions\Commands\QueueImportAllCommand;
use Mozex\ScoutBulkActions\Commands\RefreshCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Throwable;

class ScoutBulkActionsServiceProvider extends PackageServiceProvider
{
    protected string $repository = 'https://github.com/mozex/laravel-scout-bulk-actions';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-scout-bulk-actions')
            ->hasConfigFile()
            ->hasCommands([
                FlushAllCommand::class,
                ImportAllCommand::class,
                QueueImportAllCommand::class,
                RefreshCommand::class,
            ])
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->publishConfigFile()
                    ->endWith(fn (InstallCommand $command) => $this->askToStar($command));
            });
    }

    /**
     * A person gets the question, defaulting to yes. A run nobody can answer
     * (--no-interaction, or no terminal on stdin, as with CI and AI agents)
     * takes that default without asking, so it gets a note explaining the
     * browser tab instead.
     */
    protected function askToStar(InstallCommand $command): void
    {
        if (! $this->isInteractive($command)) {
            $command->info('If laravel-scout-bulk-actions saves you time, please consider starring it on GitHub: '.$this->repository);

            $this->openInBrowser();

            return;
        }

        if (! $command->confirm('Would you like to show some love by starring laravel-scout-bulk-actions on GitHub?', true)) {
            return;
        }

        if ($this->openInBrowser()) {
            return;
        }

        $command->info("You'll find laravel-scout-bulk-actions at ".$this->repository);
    }

    /**
     * Laravel's own rule for prompts (stdin must be a terminal, except under
     * unit tests, where the console output is faked), except that
     * --no-interaction always wins, which keeps that path testable.
     */
    protected function isInteractive(InstallCommand $command): bool
    {
        if ($command->option('no-interaction') === true) {
            return false;
        }

        if ($this->app->runningUnitTests()) {
            return true;
        }

        return defined('STDIN') && stream_isatty(STDIN);
    }

    /**
     * Best effort: any failure returns false. On Linux the opener runs in the
     * background, because xdg-open without a detected desktop runs the browser
     * in the foreground and would hold the command until the browser closes.
     */
    protected function openInBrowser(): bool
    {
        $command = match (PHP_OS_FAMILY) {
            'Darwin' => ['open', $this->repository],
            'Windows' => ['cmd', '/c', 'start', '', $this->repository],
            default => ['sh', '-c', 'command -v xdg-open > /dev/null && (xdg-open "$1" > /dev/null 2>&1 &)', 'sh', $this->repository],
        };

        try {
            return Process::run($command)->successful();
        } catch (Throwable) {
            return false;
        }
    }
}
