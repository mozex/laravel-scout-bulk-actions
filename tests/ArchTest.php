<?php

it('will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'sleep'])
    ->each->not->toBeUsed();
