<?php

use Mozex\ScoutBulkActions\Commands\Concerns\FindsSearchableModels;
use Mozex\ScoutBulkActions\Tests\Fixtures\PatternModels\SearchableModelForPattern;
use Mozex\ScoutBulkActions\Tests\Fixtures\SearchableModels\SearchableModel;

uses(FindsSearchableModels::class);

it('can get searchable models', function () {
    app()->setBasePath(dirname(__DIR__, 2));

    config()->set('scout-bulk-actions.model_directories', [
        base_path('tests/Fixtures'),
    ]);

    config()->set('scout-bulk-actions.namespace', 'Mozex\\ScoutBulkActions');

    expect($this->getSearchableModels())
        ->toHaveCount(2)
        ->first()->toBe(SearchableModel::class)
        ->last()->toBe(SearchableModelForPattern::class);
});

it('can get searchable models with pattern', function () {
    app()->setBasePath(dirname(__DIR__, 2));

    config()->set('scout-bulk-actions.model_directories', [
        base_path('tests/Fixtures/Pattern*'),
    ]);

    config()->set('scout-bulk-actions.namespace', 'Mozex\\ScoutBulkActions');

    expect($this->getSearchableModels())
        ->toHaveCount(1)
        ->first()->toBe(SearchableModelForPattern::class);
});
