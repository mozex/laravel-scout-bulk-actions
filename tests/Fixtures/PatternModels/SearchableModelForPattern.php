<?php

namespace Mozex\ScoutBulkActions\Tests\Fixtures\PatternModels;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class SearchableModelForPattern extends Model
{
    use Searchable;
}
