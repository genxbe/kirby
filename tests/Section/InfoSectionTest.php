<?php

namespace Kirby\Section;

use Kirby\Cms\Page;

/**
 * @covers \Kirby\Section\InfoSection
 */
class InfoSectionTest extends TestCase
{
    public function section(array $options = [])
    {
        $model = new Page(['slug' => 'test']);
        return new InfoSection($model, 'test', $options);
    }

    /**
     * @covers ::props
     */
    public function testProps()
    {
        $section = $this->section([
            'label' => 'Info',
            'help'  => 'Some help',
            'text'  => 'Some text',
            'theme' => 'info'
        ]);

        $expected = [
            'label' => 'Info',
            'help'  => '<p>Some help</p>',
            'text'  => '<p>Some text</p>',
            'theme' => 'info'
        ];

        $this->assertSame($expected, $section->props());
    }

    /**
     * @covers ::text
     */
    public function testText()
    {
        // default
        $section = $this->section();
        $this->assertSame(null, $section->text());

        // custom
        $section = $this->section(['text' => 'Hello world']);
        $this->assertSame('<p>Hello world</p>', $section->text());

        // translated
        $section = $this->section(['text' => ['en' => 'Hello world']]);
        $this->assertSame('<p>Hello world</p>', $section->text());

        // query
        $section = $this->section(['text' => '{{ page.slug }}']);
        $this->assertSame('<p>test</p>', $section->text());
    }

    /**
     * @covers ::theme
     */
    public function testTheme()
    {
        // default
        $section = $this->section();
        $this->assertSame(null, $section->theme());

        // custom
        $section = $this->section(['theme' => 'info']);
        $this->assertSame('info', $section->theme());
    }

    /**
     * @covers ::type
     */
    public function testType()
    {
        $section = $this->section();
        $this->assertSame('info', $section->type());
    }
}
