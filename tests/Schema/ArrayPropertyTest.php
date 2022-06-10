<?php

namespace Kirby\Schema;

use TypeError;

/**
 * @covers \Kirby\Schema\ArrayProperty
 */
class ArrayPropertyTest extends PropertyTestCase
{
    protected $className = 'ArrayProperty';

    public function provider(): array
    {
        return [
            [null, null],
            [['test'], ['test']],
            [1, new TypeError()]
        ];
    }
}
