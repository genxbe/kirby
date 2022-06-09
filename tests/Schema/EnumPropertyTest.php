<?php

namespace Kirby\Schema;

use Kirby\Exception\InvalidArgumentException;

/**
 * @covers \Kirby\Schema\EnumProperty
 */
class EnumPropertyTest extends PropertyTestCase
{
    protected $className = 'EnumProperty';
    protected $options = [
        'values' => ['a', 'b', 'c']
    ];

    public function provider(): array
    {
        return [
            [null, null],
            ['a', 'a'],
            ['b', 'b'],
            ['c', 'c'],
            ['d', new InvalidArgumentException],
            [[], new InvalidArgumentException]
        ];
    }
}
