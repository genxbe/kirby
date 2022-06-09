<?php

namespace Kirby\Schema;

class PropertyTestCase extends TestCase
{
    protected $className = 'Property';
    protected $options = [];

    public function provider(): array
    {
        return [];
    }

    /**
     * @dataProvider provider
     * @covers ::apply
     */
    public function testApply($value, $expected)
    {
        $className = $this->className;
        $instance  = new (__NAMESPACE__ . '\\' . $className)('test', $this->options);

        if (is_a($expected, 'Throwable') === true) {
            $this->expectException(get_class($expected));
        }

        $this->assertSame($expected, $instance->apply($value));
    }

}
