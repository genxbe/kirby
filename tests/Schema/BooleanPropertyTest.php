<?php

namespace Kirby\Schema;

use TypeError;

/**
 * @covers \Kirby\Schema\BooleanProperty
 */
class BooleanPropertyTest extends PropertyTestCase
{
    protected $className = 'BooleanProperty';

    public function provider(): array
    {
        return [
            [null, null],
            [true, true],
            [false, false],
            [1, true],
            ['1', true],
            [0, false],
            ['0', false],
            [[], new TypeError()]
        ];
    }
}
