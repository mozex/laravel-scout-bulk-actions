<?php

namespace Mozex\ScoutBulkActions\Tests;

use Mockery\Matcher\MatcherAbstract;
use Symfony\Component\Console\Input\ArrayInput;

class InputMatcher extends MatcherAbstract
{
    /**
     * @param  ArrayInput  $actual
     */
    public function match(&$actual): bool
    {
        return (string) $actual == $this->_expected;
    }

    public function __toString(): string
    {
        return '';
    }
}
