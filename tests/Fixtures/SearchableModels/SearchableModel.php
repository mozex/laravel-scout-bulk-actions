<?php

namespace Mozex\ScoutBulkActions\Tests\Fixtures\SearchableModels;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class SearchableModel extends Model
{
    use Searchable;
}
