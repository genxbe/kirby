<?php

namespace Kirby\Schema;

use TypeError;

/**
 * @covers \Kirby\Schema\IntegerProperty
 */
class IntegerPropertyTest extends PropertyTestCase
{
    protected $className = 'IntegerProperty';

    public function provider(): array
    {
        return [
            [null, null],
            [1, 1],
            ['1', 1],
            [true, 1],
            [[], new TypeError]
        ];
    }
}
