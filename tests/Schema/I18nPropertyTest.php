<?php

namespace Kirby\Schema;

use stdClass;
use TypeError;

/**
 * @covers \Kirby\Schema\I18nPropertyTest
 */
class I18nPropertyTest extends PropertyTestCase
{
    protected $className = 'I18nProperty';

    public function provider(): array
    {
        return [
            [null, null],
            ['test', 'test'],
            [['en' => 'test'], 'test'],
            [new stdClass, new TypeError]
        ];
    }
}
