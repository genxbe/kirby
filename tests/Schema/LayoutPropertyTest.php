<?php

namespace Kirby\Schema;

use Kirby\Exception\InvalidArgumentException;

/**
 * @covers \Kirby\Schema\LayoutProperty
 */
class LayoutPropertyTest extends PropertyTestCase
{
    protected $className = 'LayoutProperty';

    public function provider(): array
    {
        return [
            [null, null],
            ['cards', 'cards'],
            ['list', 'list'],
            ['cardlets', 'cardlets'],
            ['table', 'table'],
            ['kanban', new InvalidArgumentException],
            [[], new InvalidArgumentException]
        ];
    }
}
