<?php

namespace Mozex\ScoutBulkActions\Commands\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

trait FindsSearchableModels
{
    /**
     * Get all searchable models that should be used for flushing and importing.
     *
     * @return Collection<int, string>
     */
    public function getSearchableModels(): Collection
    {
        return collect($this->getSpecifiedFiles())
            ->keys()
            ->map($this->makeNamespaceFromPath(...))
            ->filter($this->isSearchableModel(...))
            ->values();
    }

    /**
     * Get files from the specified directories in config file.
     */
    protected function getSpecifiedFiles(): Finder
    {
        return (new Finder())->in(config('scout-bulk-actions.model_directories'))->files();
    }

    /**
     * Make namespace from the provided file path.
     */
    protected function makeNamespaceFromPath(string $path): string
    {
        return str($path)
            ->after(realpath(base_path()).DIRECTORY_SEPARATOR)
            ->replace(['/', '.php'], ['\\', ''])
            ->explode('\\')
            ->map(fn (string $dir) => ucfirst($dir))
            ->prepend(config('scout-bulk-actions.namespace'))
            ->filter()
            ->implode('\\');
    }

    /**
     * Check if a namespace belongs to a searchable model.
     */
    protected function isSearchableModel(string $namespace): bool
    {
        return class_exists($namespace)
            && ! (new ReflectionClass($namespace))->isAbstract()
            && is_subclass_of($namespace, Model::class)
            && in_array(Searchable::class, class_uses($namespace));
    }
}
