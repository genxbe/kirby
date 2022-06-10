<?php

namespace Kirby\Schema;

use stdClass;
use TypeError;

/**
 * @covers \Kirby\Schema\StringProperty
 */
class StringPropertyTest extends PropertyTestCase
{
    protected $className = 'StringProperty';

    public function provider(): array
    {
        return [
            [null, null],
            ['test', 'test'],
            [1, '1'],
            [new stdClass(), new TypeError()]
        ];
    }
}
