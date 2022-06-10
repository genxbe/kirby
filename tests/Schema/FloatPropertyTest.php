<?php

namespace Kirby\Schema;

use TypeError;

/**
 * @covers \Kirby\Schema\FloatProperty
 */
class FloatPropertyTest extends PropertyTestCase
{
    protected $className = 'FloatProperty';

    public function provider(): array
    {
        return [
            [null, null],
            [1, 1.0],
            ['1.1', 1.1],
            [true, 1.0],
            [[], new TypeError()]
        ];
    }
}
