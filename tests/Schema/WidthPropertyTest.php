<?php

namespace Kirby\Schema;

use Kirby\Exception\InvalidArgumentException;

/**
 * @covers \Kirby\Schema\WidthProperty
 */
class WidthPropertyTest extends PropertyTestCase
{
    protected $className = 'WidthProperty';

    public function provider(): array
    {
        return [
            [null, null],
            ['1/1', '1/1'],
            ['1/2', '1/2'],
            ['1/3', '1/3'],
            ['1/4', '1/4'],
            ['2/3', '2/3'],
            ['1/5', new InvalidArgumentException()],
            [[], new InvalidArgumentException()]
        ];
    }
}
