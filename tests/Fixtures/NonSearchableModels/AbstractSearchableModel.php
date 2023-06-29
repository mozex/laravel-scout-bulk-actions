<?php

namespace Mozex\ScoutBulkActions\Tests\Fixtures\NonSearchableModels;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

abstract class AbstractSearchableModel extends Model
{
    use Searchable;
}
