<?php

namespace Kirby\Schema;

/**
 * @covers \Kirby\Schema\Schema
 */
class SchemaTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::has
     */
    public function test__construct()
    {
        // without properties
        $schema = new Schema();

        $this->assertCount(0, $schema->properties());

        // with properties
        $schema = new Schema([
            'a' => [
                'type' => 'string',
            ],
            'b' => [
                'type' => 'integer'
            ],
        ]);

        $this->assertTrue($schema->has('a'));
        $this->assertTrue($schema->has('b'));
    }

    /**
     * @covers ::__get
     * @covers ::__set
     */
    public function test__setAndGet()
    {
        $schema = new Schema();
        $schema->title = $property = new StringProperty('title');

        $this->assertSame($property, $schema->title);
    }

    /**
     * @covers ::add
     */
    public function testAdd()
    {
        // simple
        $schema = new Schema();
        $property = $schema->add('test', [
            'type' => 'string'
        ]);

        $this->assertInstanceOf('Kirby\Schema\StringProperty', $property);

        // with default
        $schema = new Schema();
        $property = $schema->add('test', [
            'type'    => 'string',
            'default' => 'foo'
        ]);

        $this->assertSame('foo', $property->default);

        // required
        $schema = new Schema();
        $property = $schema->add('test', [
            'type'     => 'string',
            'required' => true
        ]);

        $this->assertTrue($property->required);

        // with implicit type
        $schema = new Schema();
        $property = $schema->add('integer');

        $this->assertInstanceOf('Kirby\Schema\IntegerProperty', $property);
    }

    /**
     * @covers ::apply
     */
    public function testApply()
    {
        $schema = new Schema([
            'title' => [
                'type' => 'string',
            ],
            'year' => [
                'type' => 'integer'
            ],
            'text' => [
                'type'    => 'string',
                'default' => 'Lorem ipsum'
            ]
        ]);

        $data = $schema->apply([
            'title' => 'Test',
            'year'  => '2022'
        ]);

        $this->assertSame('Test', $data['title']);
        $this->assertSame(2022, $data['year']);
        $this->assertSame('Lorem ipsum', $data['text']);
    }

    /**
     * @covers ::add
     * @covers ::properties
     */
    public function testProperties()
    {
        $schema = new Schema();

        $property = $schema->add('title', [
            'type' => 'string'
        ]);

        $this->assertSame(['title' => $property], $schema->properties());
    }

    /**
     * @covers ::add
     * @covers ::has
     * @covers ::remove
     */
    public function testRemove()
    {
        $schema = new Schema();

        $this->assertFalse($schema->has('title'));

        $schema->add('title', [
            'type' => 'string'
        ]);

        $this->assertTrue($schema->has('title'));

        $schema->remove('title');

        $this->assertFalse($schema->has('title'));
    }
}
